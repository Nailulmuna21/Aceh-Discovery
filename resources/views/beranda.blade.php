@extends('layouts.app')
@section('judul', 'Aceh Discovery — Portal Eksplorasi Kuliner, Warisan Budaya & Destinasi Aceh')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/beranda.css') }}">
    {{-- Hero: video & poster di-host online (lihat config/video.php, .env HERO_VIDEO_URL / HERO_POSTER_URL) --}}
    @if ($heroPoster = \App\Support\Media::url(config('video.hero.poster')))
        <link rel="preload" as="image" href="{{ $heroPoster }}" fetchpriority="high">
    @endif
@endpush

@push('js')
    <script src="{{ asset('assets/js/video-lazy.js') }}" defer></script>
@endpush

@section('isi')
    <!-- HERO -->
    {{-- Latar hero = gambar poster hero (HERO_POSTER_URL) + tint tipis (lihat .hero-editorial--foto di beranda.css) --}}
    @php $latarHero = \App\Support\Media::url(config('video.hero.poster')); @endphp
    <section class="hero-editorial {{ $latarHero ? 'hero-editorial--foto' : '' }}"
        @if ($latarHero) style="--hero-foto: url('{{ $latarHero }}')" @endif>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-tag-pill">
                        <span class="tag-dot"></span>
                        <span>Portal Penjelajahan Serambi Mekkah</span>
                    </div>
                    <h1 class="hero-heading">
                        Jelajahi <em>Cita Rasa</em> & Warisan Abadi Tanah Aceh.
                    </h1>
                    <p class="hero-description">
                        Sebuah ruang kurasi untuk menemukan rempah otentik, magisnya tarian tradisi UNESCO, hingga lanskap pesisir dan dataran tinggi nusantara.
                    </p>
                    {{-- Mencari di kuliner + budaya + destinasi sekaligus (route cari.index) --}}
                    <form action="{{ route('cari.index') }}" method="GET" class="search-box-container mb-4" role="search">
                        <div class="input-group search-box-pill">
                            <span class="input-group-text bg-transparent border-0 text-teal ps-2">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="search" name="q" class="form-control" maxlength="100" autocomplete="off" aria-label="Kata kunci pencarian" placeholder="Cari Mie Aceh, Tari Saman, Sabang, Gayo...">
                            <button type="submit" class="btn btn-aceh-primary">Telusuri</button>
                        </div>
                    </form>
                    <div class="d-flex align-items-center gap-4 pt-2">
                        <div>
                            <div class="h4 font-editorial fw-bold m-0 text-teal">23+</div>
                            <small class="text-muted">Kabupaten & Kota</small>
                        </div>
                        <div class="vr opacity-25" style="height: 30px;"></div>
                        <div>
                            <div class="h4 font-editorial fw-bold m-0 text-teal">{{ $jumlahKuliner }}</div>
                            <small class="text-muted">Kuliner Otentik</small>
                        </div>
                        <div class="vr opacity-25" style="height: 30px;"></div>
                        <div>
                            <div class="h4 font-editorial fw-bold m-0 text-teal">UNESCO</div>
                            <small class="text-muted">Warisan Dunia</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual-container">
                        <div class="hero-slab" aria-hidden="true"></div>
                        {{-- Bingkai KUBAH masjid: bayangan (luar) > cincin putih (tengah) > video (dalam), semuanya di-clip bentuk kubah --}}
                        <div class="hero-kubah-bayang">
                            <div class="hero-kubah">
                                <div class="hero-main-frame">
                                    <x-video-lazy :item="config('video.hero')" mode="eager" label="Pemandangan udara Masjid Raya Baiturrahman, Banda Aceh" />
                                </div>
                            </div>
                        </div>
                        {{-- Definisi bentuk kubah (skala 0-1, ikut ukuran elemen). GANTI DI SINI untuk mengubah lekuk kubah. --}}
                        <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
                            <defs>
                                <clipPath id="kubah-hero" clipPathUnits="objectBoundingBox">
                                    <path d="M0,1 L0,0.62 C0,0.44 0.2,0.35 0.36,0.25 C0.44,0.19 0.48,0.1 0.5,0 C0.52,0.1 0.56,0.19 0.64,0.25 C0.8,0.35 1,0.44 1,0.62 L1,1 Z"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <div class="hero-badge-top">
                            <i class="fa-solid fa-location-dot text-teal me-2"></i>Aceh, Indonesia
                        </div>
                        <div class="hero-floating-card">
                            <div class="floating-icon"><i class="fa-solid fa-mug-hot"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Warisan Leluhur</h6>
                                <p class="small text-muted m-0">Dari kuliner otentik hingga budaya peninggalan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CERITA & BERITA TERBARU (tabel berita: 8 terbaru) — marquee otomatis ke kiri, looping, CSS-only -->
    <section class="section-berita">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Kabar Terkini</span>
                    <h3 class="fw-bold font-editorial m-0 mt-1">Terbaru di Aceh Discovery</h3>
                </div>
            </div>
        </div>

        @if ($berita->isEmpty())
            <div class="container">
                <x-pesan-kosong class="py-4 w-100">Belum ada berita.</x-pesan-kosong>
            </div>
        @else
            @php
                // Satu "grup" harus cukup lebar menutupi layar supaya loop mulus -> minimal 6 kartu per grup.
                // One group must be wider than the viewport so the loop has no gap -> at least 6 cards per group.
                $ulang = (int) ceil(6 / $berita->count());
                $kartuPerGrup = $ulang * $berita->count();
            @endphp
            <div class="berita-marquee" role="region" aria-label="Cerita dan berita terbaru">
                {{-- --n = jumlah kartu per grup -> durasi ikut jumlah kartu, jadi kecepatan selalu konstan --}}
                <div class="berita-track" style="--n: {{ $kartuPerGrup }};">
                    @foreach ([false, true] as $klon)
                        {{-- grup ke-2 = salinan untuk looping; disembunyikan dari screen reader --}}
                        <div class="berita-group" @if ($klon) aria-hidden="true" data-klon @endif>
                            @for ($r = 0; $r < $ulang; $r++)
                                @foreach ($berita as $n)
                                    <div class="berita-item">
                                        <div class="news-ticker-card p-4 h-100">
                                            <span class="badge-berita mb-2">{{ $n->label ?: 'Cerita' }}</span>
                                            <h5 class="fw-bold mb-2 font-editorial">{{ $n->judul }}</h5>
                                            <p class="text-muted small mb-3">{{ $n->ringkasan }}</p>
                                            <span class="text-secondary small"><i class="fa-regular fa-clock me-1"></i> {{ $n->tanggal->translatedFormat('j F Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endfor
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- FEATURED (Sorotan Editorial) — 4 video online, lazy-load lewat video-lazy.js (gambar latar = poster/cadangan) -->
    <section class="section-featured-stories">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Sorotan Editorial</span>
                    <h2 class="font-editorial display-6 fw-bold mt-2">Cerita Utama dari Aceh</h2>
                    <p class="text-muted">Eksplorasi pilar utama yang membentuk identitas dan keharuman Tanah Rencong.</p>
                </div>
            </div>

            </div>

            {{-- 1. TARI SAMAN — landscape 4:3 (kiri) --}}
            <div class="featured-block tema-{{ config('video.editorial.tari-saman.latar.tema') }}" data-sisi-teks="kanan">
                <x-latar-editorial :item="config('video.editorial.tari-saman.latar')" />
                <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="featured-video-frame" style="--vid-w: {{ config('video.editorial.tari-saman.lebar') }}; --vid-h: {{ config('video.editorial.tari-saman.tinggi') }};">
                            <span class="badge-featured-category accent-sky">UNESCO Heritage</span>
                            <x-video-lazy :item="config('video.editorial.tari-saman')" label="Penari Saman bergerak serempak dalam formasi" />
                        </div>
                    </div>
                    <div class="col-lg-6 ps-lg-5">
                        <div class="featured-meta">
                            <span><i class="fa-solid fa-masks-theater text-teal"></i> Gayo Lues</span>
                            <span><i class="fa-solid fa-certificate text-teal"></i> UNESCO</span>
                        </div>
                        <h3 class="featured-title">Harmoni Ribuan Tangan dalam Ritme Keagungan Tari Saman</h3>
                        <p class="featured-excerpt">
                            Dikenal sebagai "Tari Seribu Tangan", pertunjukan tanpa alat musik ini mengandalkan tepukan dada dan gerakan serentak nan dinamis.
                        </p>
                        <a href="{{ route('budaya.index', ['q' => 'saman']) }}" class="btn btn-aceh-glass">
                            Lihat Tari Saman <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                </div>
            </div>

            {{-- 2. KOPI GAYO — portrait 9:16 (kanan) --}}
            <div class="featured-block tema-{{ config('video.editorial.kopi-gayo.latar.tema') }}" data-sisi-teks="kiri">
                <x-latar-editorial :item="config('video.editorial.kopi-gayo.latar')" />
                <div class="container">
                <div class="row align-items-center g-4 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <div class="featured-video-frame is-portrait" style="--vid-w: {{ config('video.editorial.kopi-gayo.lebar') }}; --vid-h: {{ config('video.editorial.kopi-gayo.tinggi') }};">
                            <span class="badge-featured-category">Kopi Khas</span>
                            <x-video-lazy :item="config('video.editorial.kopi-gayo')" label="Biji kopi Arabika Gayo disangrai" />
                        </div>
                    </div>
                    <div class="col-lg-6 pe-lg-5">
                        <div class="featured-meta">
                            <span><i class="fa-solid fa-map-pin text-teal"></i> Aceh Tengah</span>
                            <span><i class="fa-solid fa-star"></i> 4.9</span>
                        </div>
                        <h3 class="featured-title">Kopi Gayo, Rasa Khas yang Tumbuh di Tanah Tinggi Aceh</h3>
                        <p class="featured-excerpt">
                            Tumbuh di dataran tinggi Gayo pada ketinggian sekitar 1.200 mdpl, kopi Arabika ini dikenal dengan cita rasa yang bersih, aroma yang kaya, dan menjadi salah satu kopi terbaik dunia.
                        </p>
                        <a href="{{ route('kuliner.show', 'kopi-gayo') }}" class="btn btn-aceh-glass">
                            Lihat Kopi Gayo <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                </div>
            </div>

            {{-- 3. RUMOH ACEH — portrait 9:16 (kiri) --}}
            <div class="featured-block tema-{{ config('video.editorial.rumoh-aceh.latar.tema') }}" data-sisi-teks="kanan">
                <x-latar-editorial :item="config('video.editorial.rumoh-aceh.latar')" />
                <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="featured-video-frame is-portrait" style="--vid-w: {{ config('video.editorial.rumoh-aceh.lebar') }}; --vid-h: {{ config('video.editorial.rumoh-aceh.tinggi') }};">
                            <span class="badge-featured-category accent-sky">Arsitektur Tradisional</span> <!-- GANTI DI SINI: label badge -->
                            <x-video-lazy :item="config('video.editorial.rumoh-aceh')" label="Bangunan tradisional Aceh dengan atap bertingkat dan ukiran khas" />
                        </div>
                    </div>
                    <div class="col-lg-6 ps-lg-5">
                        <div class="featured-meta">
                            <span><i class="fa-solid fa-map-pin text-teal"></i> Banda Aceh</span> <!-- GANTI DI SINI: lokasi -->
                            <span><i class="fa-solid fa-landmark text-teal"></i> Warisan Arsitektur</span> <!-- GANTI DI SINI: meta kedua -->
                        </div>
                        <!-- GANTI DI SINI: judul, deskripsi, dan tujuan CTA Rumoh Aceh -->
                        <h3 class="featured-title">Rumoh Aceh, Rumah Panggung yang Menyimpan Filosofi Tanah Rencong</h3>
                        <p class="featured-excerpt">
                            Berdiri di atas tiang-tiang kayu dengan ukiran khas, Rumoh Aceh dirancang selaras dengan alam dan menjadi cermin nilai kebersamaan masyarakat Aceh.
                        </p>
                        <a href="{{ route('budaya.index', ['q' => 'rumoh']) }}" class="btn btn-aceh-glass">
                            Kenali Rumoh Aceh <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                </div>
            </div>

            {{-- 4. SABANG — landscape 4:3 (kanan) --}}
            <div class="featured-block mb-0 tema-{{ config('video.editorial.sabang.latar.tema') }}" data-sisi-teks="kiri">
                <x-latar-editorial :item="config('video.editorial.sabang.latar')" />
                <div class="container">
                <div class="row align-items-center g-4 flex-lg-row-reverse">
                    <div class="col-lg-6">
                        <div class="featured-video-frame" style="--vid-w: {{ config('video.editorial.sabang.lebar') }}; --vid-h: {{ config('video.editorial.sabang.tinggi') }};">
                            <span class="badge-featured-category">Destinasi Bahari</span> <!-- GANTI DI SINI: label badge -->
                            <x-video-lazy :item="config('video.editorial.sabang')" label="Penyelam dan kawanan ikan di perairan Batee Tokong, Sabang" />
                        </div>
                    </div>
                    <div class="col-lg-6 pe-lg-5">
                        <div class="featured-meta">
                            <span><i class="fa-solid fa-map-pin text-teal"></i> Sabang</span> <!-- GANTI DI SINI: lokasi -->
                            <span><i class="fa-solid fa-water text-teal"></i> Wisata Selam</span> <!-- GANTI DI SINI: meta kedua -->
                        </div>
                        <!-- GANTI DI SINI: judul, deskripsi, dan tujuan CTA Sabang -->
                        <h3 class="featured-title">Menyelam di Sabang, Surga Bawah Laut di Ujung Barat Nusantara</h3>
                        <p class="featured-excerpt">
                            Perairan Pulau Weh menyimpan terumbu karang dan kawanan ikan yang berenang di jernihnya biru laut, ruang bermain favorit para penyelam.
                        </p>
                        <a href="{{ route('destinasi.index', ['daerah' => 'sabang']) }}" class="btn btn-aceh-glass">
                            Jelajahi Sabang <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                </div>
            </div>
    </section>


    <!-- DISCOVER: KULINER POPULER (4 kuliner terbaru dari database) -->
    <section class="section-discover">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
                <div>
                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Rasa Otentik</span>
                    <h2 class="font-editorial display-6 fw-bold m-0">Kuliner Populer Aceh</h2>
                </div>
                <a href="{{ route('kuliner.index') }}" class="btn btn-aceh-outline">Semua Kuliner</a>
            </div>

            {{-- Chip disaring di klien: chip-filter.js mengirim event ke marquee.js yang membangun ulang strip.
                 Baris berdaerah "semua" selalu tampil (F03) --}}
            <div class="d-flex flex-wrap gap-2 mb-4" id="filterDaerahChips" data-chip-filter data-chip-marquee="#marqueeKuliner">
                <x-chip-daerah slug="semua" teks="Semua Daerah" :aktif="true" />
                @foreach ($daerahChip as $d)
                    <x-chip-daerah :slug="$d->slug" :teks="$d->nama_daerah" />
                @endforeach
            </div>
        </div>

        {{-- Strip penuh selebar layar: kartu bergeser otomatis ke kiri, looping --}}
        <div class="mq" id="marqueeKuliner" data-marquee role="region" aria-label="Kuliner populer Aceh">
            <div class="mq-track" id="kulinerPopulerContainer">
                <div class="mq-group">
                    @forelse ($kulinerPopuler as $k)
                        <div class="mq-item" data-daerah="{{ $k->daerah?->slug ?? 'semua' }}">
                            <x-card-mini
                                :href="route('kuliner.show', $k)"
                                badge="Kuliner" tipe="kuliner"
                                :lokasi="$k->daerah?->nama_daerah ?? 'Seluruh Aceh'"
                                :judul="$k->nama" :deskripsi="$k->deskripsi" :gambar="$k->gambar_url" :peringkat="$k->peringkat" />
                        </div>
                    @empty
                        <div class="container"><x-pesan-kosong class="py-4 w-100">Belum ada data kuliner.</x-pesan-kosong></div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- DISCOVER: BUDAYA & TRADISI PILIHAN (3 budaya terbaru dari database) -->
    <section class="section-discover-budaya">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
                <div>
                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Warisan Leluhur</span>
                    <h2 class="font-editorial display-6 fw-bold m-0">Budaya &amp; Tradisi Pilihan</h2>
                </div>
                <a href="{{ route('budaya.index') }}" class="btn btn-aceh-outline">Semua Budaya</a>
            </div>

        </div>

        {{-- Arah kebalikan dari strip kuliner (geser ke kanan) supaya dua strip tidak terasa kembar --}}
        <div class="mq mq-kanan" id="marqueeBudaya" data-marquee role="region" aria-label="Budaya dan tradisi pilihan">
            <div class="mq-track" id="budayaPilihanContainer">
                <div class="mq-group">
                    @forelse ($budayaPilihan as $b)
                        <div class="mq-item">
                            <x-card-mini
                                :href="route('budaya.index', ['q' => $b->nama])"
                                :badge="$b->kategori?->nama_kategori ?: 'Budaya'" tipe="budaya"
                                :lokasi="$b->daerah?->nama_daerah ?? 'Seluruh Aceh'"
                                :judul="$b->nama" :deskripsi="$b->deskripsi" :gambar="$b->gambar_url"
                                tombolTeks="Pelajari Budaya" />
                        </div>
                    @empty
                        <div class="container"><x-pesan-kosong class="py-4 w-100">Belum ada data budaya.</x-pesan-kosong></div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- EDITORIAL IDENTITY -->
    <div class="container">
        <section class="section-editorial-identity">
            <div class="row align-items-center g-5 px-4 px-md-5">
                <div class="col-lg-6">
                    <div class="collage-wrapper">
                        <span class="collage-slab" aria-hidden="true"></span>
                        <figure class="collage-main">
                            <img src="https://posaceh.com/wp-content/uploads/2025/12/Sambut-tamu.jpg" alt="Peumulia jamee" loading="lazy">
                        </figure>
                        <figure class="collage-sub">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTUlesN9ktNJ3sRGc4rDWQX54XUCNScvMgqhoyMH7KQEwwGjvjtO07JsHE&s=10" alt="Kedai kopi aceh" loading="lazy">
                        </figure>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="text-uppercase tracking-wider text-meadow fw-bold small">Identitas Kebudayaan</span>
                    <h2 class="font-editorial display-6 fw-bold text-white mt-2 mb-4">
                        Lebih dari Sekadar Destinasi Wisata
                    </h2>
                    <p class="text-light opacity-75 mb-4">
                        Aceh adalah ruang perjumpaan nilai-nilai spiritual, keramahan hangat kedai kopi 24 jam, sejarah kejayaan kesultanan, dan ketangguhan masyarakat yang senantiasa menyambut tamu bagaikan keluarga sendiri.
                    </p>
                    <div class="identity-bullet">
                        <div class="identity-bullet-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Mulia Poe Teumpeun (Muliakan Tamu)</h6>
                            <p class="small text-light opacity-75 m-0">Falsafah adat memuliakan setiap musafir.</p>
                        </div>
                    </div>
                    <div class="identity-bullet">
                        <div class="identity-bullet-icon"><i class="fa-solid fa-mug-hot"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Kultur Seribu Kedai Kopi</h6>
                            <p class="small text-light opacity-75 m-0">Pusat diskusi & kehangatan sosial.</p>
                        </div>
                    </div>
                    <div class="pt-3">
                        <a href="{{ route('budaya.index') }}" class="btn btn-aceh-accent">
                            Jelajahi Kebudayaan <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- PENGALAMAN PENGUNJUNG (12 terbaru; yang terbaru aktif) -->
    <section class="section-community">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Cerita Penjelajah</span>
                    <h2 class="font-editorial fw-bold m-0 mt-1">Pengalaman Pengunjung</h2>
                </div>
                <button type="button" class="btn btn-aceh-primary" data-bs-toggle="modal" data-bs-target="#modalUnggahPengalaman">
                    <i class="fa-solid fa-camera me-2"></i>Unggah
                </button>
            </div>

            <div id="carouselPengalaman" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselPengalamanInner">
                    @if ($pengalaman->isEmpty())
                        <div class="carousel-item active" data-kosong>
                            <x-pesan-kosong class="mb-0 w-100">Belum ada pengalaman yang dibagikan. Jadilah yang pertama!</x-pesan-kosong>
                        </div>
                    @else
                        {{-- urut lama -> baru, slide terakhir (terbaru) yang aktif, seperti versi lama --}}
                        @foreach ($pengalaman->reverse()->values() as $p)
                            <x-slide-pengalaman :p="$p" :aktif="$loop->last" />
                        @endforeach
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button class="carousel-nav-btn" type="button" data-bs-target="#carouselPengalaman" data-bs-slide="prev" aria-label="Sebelumnya">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="carousel-nav-btn" type="button" data-bs-target="#carouselPengalaman" data-bs-slide="next" aria-label="Berikutnya">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section-closing-cta">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="text-uppercase tracking-wider text-meadow fw-bold small">Mulai Petualangan Anda</span>
                    <h2 class="cta-heading mt-2">Siap untuk Menjelajahi Keajaiban Aceh?</h2>
                    <p class="lead opacity-90 mb-5 text-light">
                        Temukan rute perjalanan kuliner terbaik, tempat bersejarah, dan pengalaman kebudayaan otentik bersama Aceh Discovery.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('destinasi.index') }}" class="btn btn-lg btn-aceh-accent px-5 py-3 fs-6">
                            Mulai Eksplorasi Sekarang
                        </a>
                        <a href="{{ route('kuliner.index') }}" class="text-light btn btn-lg btn-aceh-glass px-5 py-3 fs-6">
                            Jelajahi Kuliner
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <x-modal-pengalaman :daftar-daerah="$daftarDaerah" />
@endsection

@push('js')
    <script src="{{ asset('assets/js/chip-filter.js') }}" defer></script>
    <script src="{{ asset('assets/js/marquee.js') }}" defer></script>
    <script src="{{ asset('assets/js/luminance.js') }}" defer></script>
    <script src="{{ asset('assets/js/pengalaman.js') }}" defer></script>
    <script src="{{ asset('assets/js/suka-pengalaman.js') }}" defer></script>
@endpush
