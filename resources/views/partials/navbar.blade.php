{{-- Navbar publik. Menu menyala sesuai route; tombol Masuk berubah jadi Keluar bila sudah login (F14). --}}
<nav class="navbar navbar-expand-lg fixed-top aceh-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('beranda') }}">
            <div class="brand-icon" aria-hidden="true">
                {{-- Logo: mawar kompas bersudut 8 (penjelajahan) dengan bulan sabit (warna latar lingkaran) di pusatnya (identitas Aceh). Warna mengikuti tema lewat currentColor. --}}
                <svg class="brand-logo" viewBox="0 0 64 64" width="30" height="30" fill="currentColor" focusable="false">
                    <circle cx="32" cy="32" r="28" fill="none" stroke="currentColor" stroke-width="2.2" opacity=".5"/>
                    <path opacity=".5" transform="rotate(45 32 32)" d="M32 13 L35.6 28.4 L51 32 L35.6 35.6 L32 51 L28.4 35.6 L13 32 L28.4 28.4Z"/>
                    <path d="M32 5 L37.6 26.4 L59 32 L37.6 37.6 L32 59 L26.4 37.6 L5 32 L26.4 26.4Z"/>
                    <path class="brand-logo-sabit" d="M35 23.5 A9 9 0 1 0 35 40.5 A11.5 11.5 0 0 1 35 23.5Z"/>
                </svg>
            </div>
            <span class="brand-text">Aceh Discovery</span>
        </a>
        {{-- Toggle tema: versi mobile selalu terlihat di samping hamburger; versi desktop ada di dalam menu --}}
        <div class="d-flex align-items-center gap-2 ms-auto me-2 d-lg-none">
            <button type="button" class="tema-toggle js-tema-toggle " aria-label="Ganti ke tema terang" aria-pressed="true" title="Ganti tema">
                <i class="fa-solid fa-moon ikon-bulan" aria-hidden="true"></i><i class="fa-solid fa-sun ikon-matahari" aria-hidden="true"></i>
            </button>
        </div>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center {{ request()->routeIs('beranda') ? 'gap-lg-4' : 'gap-lg-3' }} mb-2 mb-lg-0">
                <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('beranda')]) href="{{ route('beranda') }}">Beranda</a></li>
                <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('kuliner.*')]) href="{{ route('kuliner.index') }}">Kuliner</a></li>
                <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('budaya.*')]) href="{{ route('budaya.index') }}">Budaya &amp; Tradisi</a></li>
                <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('destinasi.*')]) href="{{ route('destinasi.index') }}">Destinasi Wisata</a></li>
                @auth
                    <li class="nav-item"><a @class(['nav-link', 'active' => request()->routeIs('favorit.*')]) href="{{ route('favorit.index') }}">Favorit</a></li>
                @endauth
                <li class="nav-item d-none d-lg-block ms-lg-2">
                    <button type="button" class="tema-toggle js-tema-toggle " aria-label="Ganti ke tema terang" aria-pressed="true" title="Ganti tema">
                <i class="fa-solid fa-moon ikon-bulan" aria-hidden="true"></i><i class="fa-solid fa-sun ikon-matahari" aria-hidden="true"></i>
            </button>
                </li>
                <li class="nav-item ms-lg-2">
                    @auth
                        {{-- Keluar harus POST + CSRF (bukan link biasa) --}}
                        <form method="POST" action="{{ route('keluar') }}">
                            @csrf
                            <button type="submit" class="btn btn-aceh-outline py-2 px-4">Keluar</button>
                        </form>
                    @else
                        <a class="btn btn-aceh-outline text-decoration-none py-2 px-4" href="{{ route('masuk') }}">
                            <i class="fa-solid fa-user me-2"></i>Masuk
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>
