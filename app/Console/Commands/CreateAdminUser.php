<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--name= : The name of the administrator}
                            {--email= : The email address of the administrator}
                            {--password= : The password for the administrator (optional, prompts if omitted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely create or promote an administrator account for Harivanshi Seva Kunj';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('══════════════════════════════════════════════════════════');
        $this->info(' Harivanshi Seva Kunj — Administrator Account Setup');
        $this->info('══════════════════════════════════════════════════════════');

        $name = $this->option('name') ?: $this->ask('Administrator name', 'Admin');
        $email = $this->option('email') ?: $this->ask('Administrator email');

        $emailValidator = Validator::make(['email' => $email], [
            'email' => ['required', 'email'],
        ]);

        if ($emailValidator->fails()) {
            $this->error('Invalid email address format.');
            return self::FAILURE;
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->warn("A user with email [{$email}] already exists.");

            if (! $this->confirm('Do you want to grant this user administrator privileges and update password?', true)) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }

            $password = $this->option('password') ?: $this->secret('New password (min 8 characters)');

            if (empty($password) || strlen($password) < 8) {
                $this->error('Password must be at least 8 characters long.');
                return self::FAILURE;
            }

            $existingUser->forceFill([
                'name' => $name ?: $existingUser->name,
                'password' => Hash::make($password),
                'is_admin' => true,
            ])->save();

            $this->info("✓ User [{$email}] has been successfully updated to an Administrator.");
            return self::SUCCESS;
        }

        $password = $this->option('password') ?: $this->secret('Password (min 8 characters)');

        if (empty($password) || strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("✓ Administrator [{$user->email}] created successfully with ID: {$user->id}.");
        $this->info("You can now securely log in at: /admin/login");

        return self::SUCCESS;
    }
}
