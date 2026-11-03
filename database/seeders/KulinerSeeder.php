<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Kategori;
use App\Models\Kuliner;
use Illuminate\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, bahan_utama, harga_min, harga_max, gambar, slug_daerah, slug_kategori]
        $data = [
            ['Mie Aceh', 'mie-aceh',
                'Mie kuning tebal dengan irisan daging sapi, kambing, atau makanan laut dalam sup kari yang pedas, hangat, dan kaya rempah pilihan khas Serambi Mekkah.',
                'Mie kuning, daging/udang, rempah kari', 20000, 35000,
                'https://placehold.co/600x600/e9d8a6/1b4332?text=Mie+Aceh', 'banda-aceh', 'makanan-berat'],
            ['Kuah Pliek U', 'kuah-pliek-u',
                'Gulai sayuran bergizi khas Aceh dengan bumbu utama patarana (pliek u) fermentasi kelapa, melinjo, daun pepaya, dan kecombrang yang membangkitkan selera.',
                'Pliek u, sayuran, melinjo', 15000, 25000,
                'https://placehold.co/600x600/dda15e/1b4332?text=Kuah+Pliek+U', 'pidie', 'makanan-berat'],
            ['Kopi Sanger', 'kopi-sanger',
                'Perpaduan pas antara kopi hitam saring khas Aceh dengan susu kental manis dan gula, dikocok dan diaduk hingga menghasilkan busa lembut yang nikmat.',
                'Kopi robusta, susu kental manis', 8000, 15000,
                'https://placehold.co/600x600/bc6c25/ffffff?text=Kopi+Sanger', 'aceh-besar', 'minuman'],
            ['Ayam Tangkap', 'ayam-tangkap',
                'Ayam kampung garing yang digoreng bersama daun temurui, pandan, dan cabai hijau harum.',
                'Ayam kampung, daun temurui, cabai hijau', 25000, 45000,
                'https://placehold.co/600x400/52b788/ffffff?text=Ayam+Tangkap', 'aceh-besar', 'makanan-berat'],
        ];

        foreach ($data as [$nama, $slug, $deskripsi, $bahan, $min, $max, $gambar, $daerah, $kategori]) {
            Kuliner::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'bahan_utama' => $bahan,
                'harga_min' => $min,
                'harga_max' => $max,
                'gambar' => $gambar,
                // cari id lewat slug (value('id') = ambil 1 kolom saja)
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_KULINER)->where('slug', $kategori)->value('id'),
            ]);
        }
    }
}
