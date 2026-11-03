@extends('layouts.app')
@section('judul', 'Budaya & Tradisi Aceh — Aceh Discovery')
@section('meta_deskripsi', 'Tarian, rumah adat, tradisi, dan kesenian Aceh: warisan budaya Serambi Mekkah.')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/budaya.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-video.css') }}">
@endpush

@section('footer_kolom')
    <h6 class="footer-heading">Budaya Populer</h6>
    <ul class="list-unstyled footer-links mb-0">
        <li><a href="{{ route('budaya.index', ['q' => 'saman']) }}">Tari Saman</a></li>
        <li><a href="{{ route('budaya.index', ['q' => 'rumoh']) }}">Rumoh Aceh</a></li>
        <li><a href="{{ route('budaya.index', ['q' => 'peusijuek']) }}">Peusijuek</a></li>
        <li><a href="{{ route('budaya.index', ['q' => 'rapai']) }}">Rapai Geleng</a></li>
    </ul>
@endsection

@section('latar', 'budaya')

@section('isi')
    <section class="page-hero has-video">
        <x-video-youtube :item="config('video.header.budaya')" />
        <div class="container">
            <div class="hero-tag-pill">
                <span class="tag-dot"></span>
                <span>Warisan Luhur Serambi Mekkah</span>
            </div>
            <h1>Pusaka <em>Budaya</em> &amp; Kesenian Aceh.</h1>
            <p class="lead-custom">
                Menyelami nilai filosofis, tarian tradisional, adat istiadat, dan warisan luhur masyarakat Serambi Mekkah.
            </p>
        </div>
    </section>

    <div class="container my-5">
        <form class="custom-card-bg p-4 mb-5" method="GET" action="{{ route('budaya.index') }}" data-live-search data-target="#daftarBudayaContainer">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" id="inputPencarianBudaya" class="form-control" value="{{ $q }}"
                               placeholder="Cari tarian, rumah adat, tradisi, atau kesenian..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kategori" id="filterKategoriBudaya" class="form-select" aria-label="Filter kategori budaya">
                        <option value="">Semua Kategori Budaya</option>
                        @foreach ($daftarKategori as $k)
                            <option value="{{ $k->slug }}" @selected($kategoriAktif === $k->slug)>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- tanpa JS: tekan Enter di kolom pencarian atau pilih kategori lalu tombol ini --}}
            <noscript><button type="submit" class="btn btn-primary-custom mt-3">Cari</button></noscript>
        </form>

        <div class="text-center mb-5">
            <h3 class="fw-bold display-6 mb-2 text-teal font-editorial">Jelajah Warisan Leluhur</h3>
            <div class="ornate-divider" style="max-width: 320px; margin: 0 auto;">
                <span class="text-teal"><i class="fa-solid fa-seedling"></i></span>
            </div>
        </div>

        <div id="daftarBudayaContainer" aria-live="polite">
            @include('budaya._daftar')
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/live-search.js') }}" defer></script>
    <script src="{{ asset('assets/js/youtube-bg.js') }}" defer></script>
@endpush
