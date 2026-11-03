<?php

namespace Database\Seeders;

use App\Models\Budaya;
use App\Models\Daerah;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BudayaSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, gambar, sorotan, slug_daerah, slug_kategori]
        $data = [
            ['Tari Saman Seribu Tangan', 'tari-saman',
                'Tarian suku Gayo yang sangat terkenal di kancah internasional dengan kecepatan gerakan tangan, tepukan dada, dan kekompakan formasi yang luar biasa. Diakui resmi oleh UNESCO sebagai Warisan Budaya Takbenda.',
                'https://placehold.co/400x400/2d6a4f/ffffff?text=Tari+Saman', 'Pengakuan Resmi UNESCO', 'gayo-lues', 'tari'],
            ['Rumoh Aceh Tradisional', 'rumah-adat-aceh',
                'Rumah panggung tradisional berstruktur kayu pilihan yang dirancang tanpa menggunakan paku besi tunggal pun. Konstruksi fleksibel ini tahan gempa dan dihiasi ukiran filosofis.',
                'https://placehold.co/400x400/52b788/ffffff?text=Rumoh+Aceh', 'Tahan Gempa & Filosofis', 'aceh-besar', 'arsitektur'],
            ['Tradisi Peusijuek (Tepung Tawar)', 'peusijuek',
                'Ritual adat sakral sebagai ungkapan rasa syukur, permohonan keselamatan, kedamaian, dan kesejahteraan. Rutin dilaksanakan pada pernikahan, kelahiran, hingga penyambutan tamu.',
                'https://placehold.co/400x250/1b4332/ffffff?text=Peusijuek', 'Simbol Kerukunan Sosial', Daerah::SLUG_SEMUA, 'upacara'],
            ['Rapai Geleng & Ratoh Duek', 'rapai',
                'Seni tabuh rebana tradisional berpadu gerak tari energik penuh kekompakan moral.',
                'https://placehold.co/600x400/ddb892/ffffff?text=Rapai+Geleng', 'Warisan Seni Pesisir', 'aceh-selatan', 'tari'],
        ];

        foreach ($data as [$nama, $slug, $deskripsi, $gambar, $sorotan, $daerah, $kategori]) {
            Budaya::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar,
                'sorotan' => $sorotan,
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_BUDAYA)->where('slug', $kategori)->value('id'),
            ]);
        }
    }
}
