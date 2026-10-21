<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

class CopyDatabaseCommand extends Command
{
    protected $signature = 'db:copy
                            {from : Source connection name (e.g. mysql)}
                            {to : Target connection name (e.g. supabase), already migrated and empty}
                            {--chunk=500 : Rows inserted per batch}';

    protected $description = 'Copy all application data from one database connection to another (e.g. MySQL to Supabase)';

    /** Transient tables that are rebuilt on their own and are not worth moving. */
    private const SKIPPED = ['migrations', 'cache', 'cache_locks', 'sessions', 'jobs', 'job_batches', 'failed_jobs'];

    public function handle(): int
    {
        $from = DB::connection($this->argument('from'));
        $to = DB::connection($this->argument('to'));

        $sourceTables = $this->tableNames($from);
        $targetTables = $this->tableNames($to);

        $missing = array_diff($sourceTables, $targetTables, self::SKIPPED);
        if ($missing) {
            $this->error('Target is missing tables: ' . implode(', ', $missing));
            $this->line("Run: php artisan migrate --database={$this->argument('to')}");
            return self::FAILURE;
        }

        $tables = $this->orderByForeignKeys($to, array_values(array_diff($sourceTables, self::SKIPPED)));

        $notEmpty = array_filter($tables, fn ($table) => $to->table($table)->exists());
        if ($notEmpty) {
            $this->error('Target already has data in: ' . implode(', ', $notEmpty));
            $this->line('Copy only into a freshly migrated, unseeded database so rows are not duplicated.');
            return self::FAILURE;
        }

        $chunk = max(1, (int) $this->option('chunk'));

        $to->transaction(function () use ($from, $to, $tables, $chunk) {
            foreach ($tables as $table) {
                $columns = collect($to->getSchemaBuilder()->getColumns($table))->keyBy('name');
                $booleans = $columns->filter(fn ($c) => in_array(strtolower($c['type_name']), ['bool', 'boolean']))->keys()->all();

                $copied = 0;
                $query = $from->table($table);
                $orderColumn = $columns->has('id') ? 'id' : $columns->keys()->first();

                $query->orderBy($orderColumn)->chunk($chunk, function ($rows) use ($to, $table, $columns, $booleans, &$copied) {
                    $payload = $rows->map(function ($row) use ($columns, $booleans) {
                        $row = array_intersect_key((array) $row, $columns->all());
                        foreach ($booleans as $column) {
                            if (array_key_exists($column, $row) && $row[$column] !== null) {
                                $row[$column] = (bool) $row[$column];
                            }
                        }
                        return $row;
                    })->all();

                    $to->table($table)->insert($payload);
                    $copied += count($payload);
                });

                $this->line(sprintf('  %-24s %d rows', $table, $copied));
            }
        });

        if ($to->getDriverName() === 'pgsql') {
            $this->resetSequences($to, $tables);
        }

        return $this->verify($from, $to, $tables);
    }

    private function tableNames(Connection $connection): array
    {
        return collect($connection->getSchemaBuilder()->getTables())
            ->filter(fn ($t) => ($t['schema'] ?? null) === null
                || $connection->getDriverName() !== 'pgsql'
                || $t['schema'] === 'public')
            ->pluck('name')
            ->unique()
            ->values()
            ->all();
    }

    /** Parents before children, so foreign keys hold while inserting. */
    private function orderByForeignKeys(Connection $connection, array $tables): array
    {
        $schema = $connection->getSchemaBuilder();
        $dependsOn = [];
        foreach ($tables as $table) {
            $dependsOn[$table] = collect($schema->getForeignKeys($table))
                ->pluck('foreign_table')
                ->filter(fn ($parent) => $parent !== $table && in_array($parent, $tables))
                ->unique()
                ->all();
        }

        $ordered = [];
        while (count($ordered) < count($tables)) {
            $ready = array_filter($tables, fn ($t) => ! in_array($t, $ordered) && ! array_diff($dependsOn[$t], $ordered));
            if (! $ready) {
                throw new \RuntimeException('Circular foreign keys between: ' . implode(', ', array_diff($tables, $ordered)));
            }
            sort($ready);
            array_push($ordered, ...$ready);
        }

        return $ordered;
    }

    /** Rows were inserted with explicit ids, so move each id sequence past the highest id. */
    private function resetSequences(Connection $to, array $tables): void
    {
        foreach ($tables as $table) {
            if (! $to->getSchemaBuilder()->hasColumn($table, 'id')) {
                continue;
            }
            $sequence = $to->selectOne('select pg_get_serial_sequence(?, ?) as seq', [$table, 'id'])->seq ?? null;
            if ($sequence) {
                $to->statement(
                    "select setval(?, coalesce((select max(id) from \"{$table}\"), 1), (select count(*) > 0 from \"{$table}\"))",
                    [$sequence]
                );
            }
        }
    }

    private function verify(Connection $from, Connection $to, array $tables): int
    {
        $mismatches = [];
        foreach ($tables as $table) {
            $source = $from->table($table)->count();
            $target = $to->table($table)->count();
            if ($source !== $target) {
                $mismatches[] = "{$table} ({$source} vs {$target})";
            }
        }

        if ($mismatches) {
            $this->error('Row counts differ: ' . implode(', ', $mismatches));
            return self::FAILURE;
        }

        $this->info('Done. Row counts match for all ' . count($tables) . ' tables.');
        return self::SUCCESS;
    }
}
