<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CopyDatabaseCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (['src', 'dst'] as $name) {
            config(["database.connections.{$name}" => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]]);
            DB::purge($name);
        }
    }

    private function migrate(string $connection): void
    {
        $this->artisan('migrate', ['--database' => $connection, '--force' => true])->assertSuccessful();
    }

    private function seedSource(): void
    {
        $src = DB::connection('src');
        $now = now();

        $src->table('users')->insert(['id' => 7, 'name' => 'Admin', 'email' => 'a@example.com', 'password' => 'x', 'created_at' => $now, 'updated_at' => $now]);
        $src->table('roles')->insert(['id' => 3, 'name' => 'admin', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now]);
        $src->table('model_has_roles')->insert(['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 7]);
        $src->table('organizers')->insert(['id' => 2, 'name' => 'Org', 'email' => 'o@example.com', 'password' => 'x', 'approved' => 1, 'created_at' => $now, 'updated_at' => $now]);
        $src->table('events')->insert(['id' => 5, 'title' => 'Bazar', 'organizer_id' => 2, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
        $src->table('sub_events')->insert(['id' => 9, 'event_id' => 5, 'title' => 'Hari 1', 'start_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
        $src->table('tickets')->insert(['id' => 4, 'sub_event_id' => 9, 'price' => 35000, 'currency' => 'IDR', 'created_at' => $now, 'updated_at' => $now]);
        $src->table('sessions')->insert(['id' => 'abc', 'payload' => 'x', 'last_activity' => 1]);
    }

    public function test_copies_all_rows_in_foreign_key_order()
    {
        $this->migrate('src');
        $this->migrate('dst');
        $this->seedSource();

        $this->artisan('db:copy', ['from' => 'src', 'to' => 'dst'])
            ->expectsOutputToContain('Row counts match')
            ->assertSuccessful();

        $dst = DB::connection('dst');
        $this->assertSame('Bazar', $dst->table('events')->where('id', 5)->value('title'));
        $this->assertSame(9, (int) $dst->table('tickets')->where('id', 4)->value('sub_event_id'));
        $this->assertTrue((bool) $dst->table('organizers')->where('id', 2)->value('approved'));
        $this->assertSame(1, $dst->table('model_has_roles')->count());
        $this->assertSame(0, $dst->table('sessions')->count(), 'transient tables are skipped');
    }

    public function test_refuses_to_copy_into_a_database_that_already_has_data()
    {
        $this->migrate('src');
        $this->migrate('dst');
        $this->seedSource();
        DB::connection('dst')->table('users')->insert(['name' => 'X', 'email' => 'x@example.com', 'password' => 'x']);

        $this->artisan('db:copy', ['from' => 'src', 'to' => 'dst'])
            ->expectsOutputToContain('Target already has data in: users')
            ->assertFailed();

        $this->assertSame(1, DB::connection('dst')->table('users')->count());
    }

    public function test_refuses_when_target_is_not_migrated()
    {
        $this->migrate('src');

        $this->artisan('db:copy', ['from' => 'src', 'to' => 'dst'])
            ->expectsOutputToContain('Target is missing tables')
            ->assertFailed();
    }
}
