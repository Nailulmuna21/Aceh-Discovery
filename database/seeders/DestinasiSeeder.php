<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Destinasi;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DestinasiSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, gambar, jenis_wisata, slug_daerah, slug_kategori]
        $data = [
            ['Masjid Raya Baiturrahman', 'masjid-raya-baiturrahman',
                'Ikon megah bergaya arsitektur Mughal-Belanda yang menjadi saksi sejarah dan pusat wisata religi Kota Banda Aceh.',
                'https://placehold.co/600x400/1b4332/ffffff?text=Masjid+Raya+Baiturrahman', 'Wisata Sejarah & Religi', 'banda-aceh', 'sejarah-religi'],
            ['Pantai Lampuuk', 'pantai-lampuuk',
                'Menawarkan hamparan pasir putih dan ombak yang digemari peselancar, dipadukan pemandangan Bukit Lampuuk yang hijau.',
                'https://placehold.co/600x400/52b788/ffffff?text=Pantai+Lampuuk', 'Wisata Pantai', 'aceh-besar', 'pantai'],
            ['Waduk Paya Tampi', 'waduk-paya-tampi',
                'Spot rekreasi keluarga favorit di Bireuen, dengan area duduk lesehan di tepi air dan udara sejuk khas pedesaan.',
                'https://placehold.co/600x400/2d6a4f/ffffff?text=Waduk+Paya+Tampi', 'Wisata Alam', 'bireuen', 'alam'],
            ['Danau Lut Tawar', 'danau-lut-tawar',
                'Danau di Dataran Tinggi Gayo yang memikat dengan udara sejuk, panorama pegunungan, serta secangkir Kopi Gayo hangat.',
                'https://placehold.co/600x400/40916c/ffffff?text=Danau+Lut+Tawar', 'Wisata Alam', 'aceh-tengah', 'alam'],
            ['Pantai Kembang Tanjong', 'pantai-kembang-tanjong',
                'Menyuguhkan suasana pesisir yang tenang, cocok untuk berjalan santai sambil menikmati kuliner khas Pidie di sekitarnya.',
                'https://placehold.co/600x400/74c69d/1b4332?text=Pantai+Kembang+Tanjong', 'Wisata Pantai', 'pidie', 'pantai'],
        ];

        foreach ($data as [$nama, $slug, $deskripsi, $gambar, $jenis, $daerah, $kategori]) {
            Destinasi::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar,
                'jenis_wisata' => $jenis,
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_DESTINASI)->where('slug', $kategori)->value('id'),
            ]);
        }
    }
}
