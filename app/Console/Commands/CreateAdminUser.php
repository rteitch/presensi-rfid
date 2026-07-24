<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun pengguna Admin baru untuk aplikasi presensi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Masukkan Nama Lengkap Admin');
        $email = $this->argument('email') ?: $this->ask('Masukkan Email Admin');
        
        if (User::where('email', $email)->exists()) {
            $this->error("Email '{$email}' sudah terdaftar dalam sistem.");
            return 1;
        }

        $password = $this->argument('password') ?: $this->secret('Masukkan Password (minimal 8 karakter)');

        if (strlen($password) < 6) {
            $this->error("Password terlalu pendek (minimal 6 karakter).");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $user->assignRole('admin');

        $this->info("✅ Akun Admin '{$name}' ({$email}) berhasil dibuat!");
        return 0;
    }
}
