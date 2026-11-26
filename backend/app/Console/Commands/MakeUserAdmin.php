<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'spirutwin:make-admin {username : Username akun yang akan diberi akses admin}';

    protected $description = 'Memberikan akses admin kepada akun yang sudah terdaftar';

    public function handle(): int
    {
        $user = User::where('username', $this->argument('username'))->first();
        if (! $user) {
            $this->error('Akun tidak ditemukan.');

            return self::FAILURE;
        }
        $user->update(['role' => 'admin']);
        $this->info("Akun {$user->username} sekarang memiliki akses admin.");

        return self::SUCCESS;
    }
}
