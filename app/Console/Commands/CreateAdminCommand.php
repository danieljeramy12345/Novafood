<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novafood:make-admin 
                            {--name= : The name of the administrator}
                            {--email= : The email address of the administrator}
                            {--password= : The password for the administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or promote an administrator user account securely';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Enter admin name', 'NovaFood Administrator');
        $email = $this->option('email') ?: $this->ask('Enter admin email', 'admin@novafood.com');

        $existing = User::where('email', $email)->first();

        if ($existing) {
            if ($this->confirm("User [{$email}] already exists. Promote to admin role?", true)) {
                $existing->role = 'admin';
                $existing->is_active = true;
                if ($this->option('password') || $this->confirm('Update password as well?', false)) {
                    $password = $this->option('password') ?: $this->secret('Enter new password');
                    $existing->password = Hash::make($password);
                }
                $existing->save();
                $this->info("User [{$email}] has been successfully promoted to administrator.");
                return Command::SUCCESS;
            }
            return Command::FAILURE;
        }

        $password = $this->option('password') ?: $this->secret('Enter admin password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("Administrator [{$admin->name} <{$admin->email}>] created successfully!");
        return Command::SUCCESS;
    }
}
