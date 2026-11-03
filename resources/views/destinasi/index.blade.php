@extends('layouts.app')
@section('judul', 'Destinasi Wisata Aceh — Aceh Discovery')
@section('meta_deskripsi', 'Rekomendasi destinasi wisata Aceh: situs sejarah, alam pegunungan, dan pesisir pantai.')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/destinasi.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-video.css') }}">
@endpush

@section('footer_kolom')
    <h6 class="footer-heading">Destinasi Populer</h6>
    <ul class="list-unstyled footer-links mb-0">
        <li><a href="{{ route('destinasi.index', ['daerah' => 'banda-aceh']) }}">Masjid Raya Baiturrahman</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'aceh-besar']) }}">Pantai Lampuuk</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'aceh-tengah']) }}">Danau Lut Tawar</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'bireuen']) }}">Waduk Paya Tampi</a></li>
    </ul>
@endsection

@section('latar', 'destinasi')

@section('isi')
    <section class="page-hero has-video">
        <x-video-youtube :item="config('video.header.destinasi')" />
        <div class="container">
            <div class="hero-tag-pill">
                <span class="tag-dot"></span>
                <span>Jelajah Keindahan Alam &amp; Sejarah</span>
            </div>
            <h1>Rekomendasi <em>Destinasi</em> Wisata Aceh.</h1>
            <p class="lead-custom">
                Kumpulan tempat wisata pilihan di Aceh, mulai dari situs sejarah, alam pegunungan, hingga pesisir pantai.
            </p>
        </div>
    </section>

    {{-- Chip berupa tautan ?daerah=slug (jalan tanpa JS); chip-filter.js menggantinya jadi tanpa reload --}}
    <section class="container my-5 py-3">
        <div class="d-flex flex-wrap justify-content-center gap-2" id="filterDaerahChipsDestinasi"
             data-chip-filter data-chip-remote="#daftarDestinasiContainer">
            <x-chip-daerah slug="semua" teks="Semua Daerah" :aktif="$daerahAktif === ''" :href="route('destinasi.index')" />
            @foreach ($daerahChip as $d)
                <x-chip-daerah :slug="$d->slug" :teks="$d->nama_daerah" :aktif="$daerahAktif === $d->slug" :href="route('destinasi.index', ['daerah' => $d->slug])" />
            @endforeach
        </div>
    </section>

    <div class="container dest-container" id="daftarDestinasiContainer" aria-live="polite">
        @include('destinasi._daftar')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/live-search.js') }}" defer></script>
    <script src="{{ asset('assets/js/youtube-bg.js') }}" defer></script>
    <script src="{{ asset('assets/js/chip-filter.js') }}" defer></script>
@endpush
