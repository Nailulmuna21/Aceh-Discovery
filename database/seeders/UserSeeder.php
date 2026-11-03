<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Email & sandi diambil dari .env (lihat blok SEED_* di .env.example).
        // Nilai cadangan di sini hanya untuk pengembangan lokal.
        $this->buat(
            nama: env('SEED_ADMIN_NAME', 'Admin Aceh Discovery'),
            email: env('SEED_ADMIN_EMAIL', 'admin@acehdiscovery.test'),
            sandi: env('SEED_ADMIN_PASSWORD', 'admin1112'),
            peran: User::PERAN_ADMIN,
        );

        $this->buat(
            nama: 'Pengguna Contoh',
            email: env('SEED_USER_EMAIL', 'user@acehdiscovery.test'),
            sandi: env('SEED_USER_PASSWORD', 'user1234'),
            peran: User::PERAN_USER,
        );
    }

    private function buat(string $nama, string $email, string $sandi, string $peran): void
    {
        // 'peran' tidak ada di $fillable, jadi diisi dengan forceFill() (khusus seeder)
        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $nama,
            'password' => $sandi,          // otomatis di-hash oleh cast 'hashed' di model User
            'peran' => $peran,
            'email_verified_at' => now(),
        ])->save();
    }
}
