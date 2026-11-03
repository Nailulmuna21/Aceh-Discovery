<?php

namespace Database\Seeders;

use App\Models\Daerah;
use Illuminate\Database\Seeder;

class DaerahSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari schema.sql lama. 'semua' = baris khusus "Seluruh Wilayah Aceh"
        $data = [
            ['Kota Banda Aceh', 'banda-aceh'],
            ['Kabupaten Aceh Besar', 'aceh-besar'],
            ['Bireuen', 'bireuen'],
            ['Aceh Tengah', 'aceh-tengah'],
            ['Pidie', 'pidie'],
            ['Gayo Lues', 'gayo-lues'],
            ['Aceh Selatan', 'aceh-selatan'],
            ['Seluruh Wilayah Aceh', Daerah::SLUG_SEMUA],
        ];

        // updateOrCreate = aman dijalankan berulang (tidak membuat data ganda)
        foreach ($data as [$nama, $slug]) {
            Daerah::updateOrCreate(['slug' => $slug], ['nama_daerah' => $nama]);
        }
    }
}
