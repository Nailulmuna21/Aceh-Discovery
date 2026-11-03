<?php

/*
 | Media beranda (hero + Sorotan Editorial).
 |
 | HERO      : video di-host ONLINE (URL penuh), diisi lewat .env -> HERO_VIDEO_URL (+ HERO_POSTER_URL opsional).
 |             Tidak ada file video di project / storage. Nilai tanpa http(s):// dianggap path di disk 'public'.
 | EDITORIAL : 4 video juga di-host online (.env -> EDITORIAL_<NAMA>_VIDEO_URL). Gambar 'latar' dipakai sebagai
 |             latar kartu DAN sebagai poster/cadangan: tampil selagi video dimuat, atau terus tampil bila
 |             video gagal dimuat (sinyal lemah, hemat data, URL salah) atau URL video belum diisi.
 |
 | lebar/tinggi = rasio frame (piksel). Dipakai CSS (aspect-ratio) supaya frame tidak melompat saat memuat.
 */
return [
    'hero' => [
        // GANTI DI .env: HERO_VIDEO_URL=https://cdn.contoh.com/hero-beranda.mp4
        'video'  => env('HERO_VIDEO_URL'),
        'poster' => env('HERO_POSTER_URL'),   // opsional: gambar pratinjau (.webp/.jpg) selagi video dimuat
        'lebar'  => 720,
        'tinggi' => 1280,   // 9:16 portrait (ditampilkan di frame 4:5 dengan object-fit: cover)
    ],

    'editorial' => [
        'tari-saman' => [
            'video'  => env('EDITORIAL_TARI_SAMAN_VIDEO_URL'),   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 768,
            'tinggi' => 576,    // 4:3 landscape
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => 'https://asset.kompas.com/crops/UGizVk8VLpWxoLx-SZx5McP0JlA=/23x0:1124x734/1200x800/data/photo/2021/09/05/6134a2c204847.jpg', 'tema' => 'gelap'],
        ],
        'kopi-gayo' => [
            'video'  => env('EDITORIAL_KOPI_GAYO_VIDEO_URL'),   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 576,
            'tinggi' => 1024,   // 9:16 portrait
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => 'https://asset.kompas.com/crops/oMjmWbCal11TCh8rUL8NJhpLOAo=/0x0:0x0/1200x800/data/photo/2021/03/18/6052f6fa9e664.jpg', 'tema' => 'gelap'],
        ],
        'rumoh-aceh' => [
            'video'  => env('EDITORIAL_RUMOH_ACEH_VIDEO_URL'),   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 576,
            'tinggi' => 1024,   // 9:16 portrait
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => 'https://indonesiakaya.com/wp-content/uploads/2020/10/1__DSC_0451_Rumoh_Aceh_secara_umum_terbagi_tiga_ruang_yaitu_serambi_depan_serambi_tengah_dan_serambi_belakang-1.jpg', 'tema' => 'gelap'],
        ],
        'sabang' => [
            'video'  => env('EDITORIAL_SABANG_VIDEO_URL'),   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 960,
            'tinggi' => 720,    // 4:3 landscape
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => 'https://media.istockphoto.com/id/1059661652/id/foto/pemandangan-udara-kilometer-zero-monument-indonesia.jpg?s=2048x2048&w=is&k=20&c=vhF0a8BKeHol9LBOsFoUuzq6NuyE0nGvkNp-d_93A1M=', 'tema' => 'gelap'],
        ],
    ],

    /*
     | Video YouTube untuk background header halaman Kuliner / Budaya / Destinasi.
     | Yang disimpan hanya ID video + detik mulai/selesai (potongan yang diputar berulang).
     | YouTube-ID + start/end seconds only (segment is looped by youtube-bg.js).
     |
     |   kuliner   : 0:00 - 0:47
     |   budaya    : 0:40 - 1:09
     |   destinasi : 0:00 - 1:00
     |
     | GANTI DI SINI bila ingin mengganti video / potongan waktunya.
     */
    'header' => [
        'kuliner'   => ['youtube' => 'L2XmE_Dd1UY', 'mulai' => 0,  'selesai' => 47],
        'budaya'    => ['youtube' => '-m3QvGuFZMs', 'mulai' => 40, 'selesai' => 69],
        'destinasi' => ['youtube' => 'H9fAPXXgwV4', 'mulai' => 0,  'selesai' => 60],
    ],
];
