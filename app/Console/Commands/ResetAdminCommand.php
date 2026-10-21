<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ResetAdminCommand extends Command
{
    protected $signature = 'admin:reset
                            {email=admin@example.com : Email of the admin account}
                            {--password= : New password (prompted if omitted, generated when non-interactive)}
                            {--name=Administrator : Name used when the account has to be created}';

    protected $description = 'Reset (or create) the Filament admin account password and ensure it has the admin role';

    public function handle(): int
    {
        $email = $this->argument('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email: {$email}");
            return self::FAILURE;
        }

        $password = $this->option('password');
        $generated = false;

        if ($password === null) {
            if ($this->input->isInteractive()) {
                $password = $this->secret('New password (leave empty to generate one)');
            }

            if ($password === null || $password === '') {
                $password = Str::password(16, symbols: false);
                $generated = true;
            } elseif ($password !== $this->secret('Confirm password')) {
                $this->error('Passwords do not match.');
                return self::FAILURE;
            }
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $created = !$user->exists;

        if ($created) {
            $user->name = $this->option('name');
        }

        // The User model casts password as "hashed", so assign the plain value
        $user->password = $password;
        $user->email_verified_at ??= now();
        $user->remember_token = Str::random(60);
        $user->save();

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info(($created ? 'Created' : 'Reset') . " admin account: {$email}");

        if ($generated) {
            $this->line("Generated password: <comment>{$password}</comment>");
        }

        $this->line('Login at: ' . url('/admin'));

        return self::SUCCESS;
    }
}
