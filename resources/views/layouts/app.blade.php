<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Audit PTPN 1 Regional 7</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        /* Styling tambahan untuk Navbar */
        .navbar-custom {
            background-color: #198754; /* Warna Hijau PTPN */
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
            margin-right: 10px;
        }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active {
            color: #ffffff;
            font-weight: bold;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center; 
        }
        
        /* Styling Logo di Navbar (DIBERSIHKAN) */
        .navbar-brand img {
            height: 40px; /* Ukuran logo disesuaikan */
            margin-right: 12px;
            /* Background putih & oval sudah dihapus */
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- --- BAGIAN NAVBAR --- --}}
        <nav class="navbar navbar-expand-md navbar-dark navbar-custom shadow-sm">
            <div class="container">
                
                {{-- 1. JUDUL / LOGO KIRI --}}
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="/image/ptpn.png" alt="Logo PTPN">
                    PTPN 1 Regional 7
                </a>

                {{-- Tombol Toggler (Muncul di HP) --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    
                    {{-- 2. MENU TENGAH (NAVBAR LINKS) --}}
                    <ul class="navbar-nav me-auto ms-3">
                        @auth
                            {{-- Menu: BERANDA --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                     Beranda
                                </a>
                            </li>

                            {{-- Menu Khusus: AUDITOR --}}
                            @if(Auth::user()->role == 'auditor')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('audit.create') ? 'active' : '' }}" href="{{ route('audit.create') }}">
                                        Buat Jadwal
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('riwayat.*') ? 'active' : '' }}" href="{{ route('riwayat.index') }}">
                                        Riwayat Unit
                                    </a>
                                </li>
                            @endif

                            {{-- Menu Khusus: AUDITEE (Unit) --}}
                            @if(Auth::user()->role == 'auditee')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('audit.request.form') ? 'active' : '' }}" href="{{ route('audit.request.form') }}">
                                        Ajuan Audit Baru
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    {{-- 3. MENU KANAN (PROFIL USER) --}}
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle bg-white text-success fw-bold rounded px-3 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} 
                                    <span class="badge bg-warning text-dark ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right text-danger me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>