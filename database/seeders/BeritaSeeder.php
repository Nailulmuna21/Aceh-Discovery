<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, ringkasan, label, tanggal]
        $data = [
            ['Menelusuri Sejarah Kuah Beulangong Tradisional Pidie',
                'Kelezatan rempah pilihan dalam tradisi kenduri akbar masyarakat Pidie yang diwariskan turun-temurun.',
                'Kuliner Legendaris', '2026-06-22'],
            ['Makna Filosofis Dibalik Gerakan Dinamis Tari Saman Gayo',
                'Kekompakan, disiplin, dan pesan keagamaan yang terkandung dalam Warisan Budaya Tak Benda UNESCO.',
                'Seni & Budaya', '2026-06-21'],
            ['Mengenal Tradisi Peusijuek (Tepung Tawar) dalam Adat Aceh',
                'Simbol rasa syukur, doa keselamatan, dan kedamaian menyambut tamu kehormatan atau momen bahagia.',
                'Kearifan Lokal', '2026-06-20'],
        ];

        // berita tidak punya slug; judul dipakai sebagai kunci supaya seeder aman diulang
        foreach ($data as [$judul, $ringkasan, $label, $tanggal]) {
            Berita::updateOrCreate(['judul' => $judul], [
                'ringkasan' => $ringkasan,
                'label' => $label,
                'tanggal' => $tanggal,
            ]);
        }
    }
}
