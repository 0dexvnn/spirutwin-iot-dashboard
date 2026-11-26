<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'spirutwin:create-admin {username : Username admin} {password : Password admin} {--name=Administrator SPIRUTWIN : Nama admin}';

    protected $description = 'Membuat akun administrator tanpa membuat data petani';

    public function handle(): int
    {
        $username = $this->argument('username');

        if (User::where('username', $username)->exists()) {
            $this->error('Username sudah digunakan.');

            return self::FAILURE;
        }

        User::create([
            'name' => $this->option('name'),
            'username' => $username,
            'email' => $username.'@accounts.spirutwin.local',
            'role' => 'admin',
            'password' => $this->argument('password'),
        ]);

        $this->info("Akun admin {$username} berhasil dibuat.");

        return self::SUCCESS;
    }
}
