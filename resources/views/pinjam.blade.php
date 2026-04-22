<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang Laboratorium</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg,
                #1565c0 0%,
                #ffffff 20%,
                #ffffff 80%,
                #1565c0 100%
            );
            background-attachment: fixed;
        }

        :root {
            --bg-secondary: #FFFFFF;
            --accent-primary: #6366F1;
            --accent-hover: #4F46E5;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --border-color: #E5E7EB;
        }

        .formula-display {
            background-color: #F3F4F6;
            color: var(--text-primary);
            font-weight: 600;
            letter-spacing: 0.025em;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            border-radius: 0.5rem;
            text-align: center;
            padding: 0.5rem;
        }

        .modal-enter { animation: fadeIn 0.3s ease-out; }
        .modal-leave { animation: fadeOut 0.3s ease-in; }
        @keyframes fadeIn  { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        @keyframes fadeOut { from { opacity:1; transform:scale(1); }   to { opacity:0; transform:scale(0.95); } }

        .search-bar-container.is-sticky .search-bar-inner {
            background-color: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 4px -2px rgb(0 0 0/0.08);
            border-bottom: 1px solid var(--border-color);
        }

        #wave-bottom {
            position: fixed; bottom: 0; left: 0;
            width: 100%; height: 32%;
            z-index: -1; pointer-events: none; overflow: hidden;
        }
        #wave-bottom svg { position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; }

        #wave-top {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 32%;
            z-index: -1; pointer-events: none; overflow: hidden;
            transform: scaleY(-1);
        }
        #wave-top svg { position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; }
    </style>
</head>
<body class="text-[var(--text-primary)]">

<div class="container mx-auto p-4 sm:p-6 md:p-8 max-w-7xl">

    {{-- NAVBAR TOP RIGHT --}}
    <div class="absolute top-4 right-4 sm:top-6 sm:right-6 md:top-8 md:right-8 z-50">
        @auth
        <div class="flex items-center gap-2 bg-white pl-2 pr-3 py-2 rounded-full shadow-md">
            <img src="{{ Auth::user()->avatar }}" alt="Foto Profil {{ Auth::user()->name }}" class="w-8 h-8 rounded-full border-2 border-gray-200">
            <span class="font-semibold text-sm hidden sm:inline">{{ Auth::user()->name }}</span>

            {{-- ===== BELL NOTIFIKASI ===== --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative p-1.5 text-gray-500 hover:text-indigo-600 transition-colors focus:outline-none">
                    <i class="fa-solid fa-bell text-base"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center
                                     w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full">
                            {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN --}}
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50"
                     style="display:none;">

                    {{-- Header dropdown --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 text-sm">Notifikasi</h3>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.read-all') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-indigo-600 hover:underline">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- ===== LIST NOTIFIKASI (support surat peminjaman & surat bebas lab) ===== --}}
                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                        @forelse(Auth::user()->notifications->take(10) as $notif)
                            @php
                                $tipe = $notif->data['tipe'] ?? '';
                                $isBebas = $tipe === 'surat_bebas_lab';
                                $isDisetujui = $tipe === 'peminjaman_disetujui';
                                $isDikonfirmasi = $tipe === 'pengembalian_dikonfirmasi';
                            @endphp
                            <div class="flex items-start px-4 py-3 hover:bg-gray-50 transition
                                        {{ is_null($notif->read_at) ? ($isBebas ? 'bg-green-50' : 'bg-indigo-50') : '' }}">

                                {{-- Icon --}}
                                <div class="flex-shrink-0 mt-0.5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                {{ is_null($notif->read_at) ? ($isBebas ? 'bg-green-100' : ($isDisetujui ? 'bg-blue-100' : ($isDikonfirmasi ? 'bg-purple-100' : 'bg-indigo-100'))) : 'bg-gray-100' }}">
                                        @if($isBebas)
                                            <i class="fa-solid fa-certificate text-xs {{ is_null($notif->read_at) ? 'text-green-600' : 'text-gray-400' }}"></i>
                                        @elseif($isDisetujui)
                                            <i class="fa-solid fa-check-circle text-xs {{ is_null($notif->read_at) ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                        @elseif($isDikonfirmasi)
                                            <i class="fa-solid fa-rotate-left text-xs {{ is_null($notif->read_at) ? 'text-purple-600' : 'text-gray-400' }}"></i>
                                        @else
                                            <i class="fa-solid fa-file-lines text-xs {{ is_null($notif->read_at) ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                                        @endif
                                    </div>
                                </div>

                                {{-- Konten --}}
                                <div class="ml-3 flex-1 min-w-0">
                                    {{-- Badge tipe --}}
                                    @if($isBebas)
                                        <span class="inline-block text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full mb-1">
                                            ✓ Surat Bebas Lab
                                        </span>
                                    @elseif($isDisetujui)
                                        <span class="inline-block text-xs font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full mb-1">
                                            ✅ Peminjaman Disetujui
                                        </span>
                                    @elseif($isDikonfirmasi)
                                        <span class="inline-block text-xs font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full mb-1">
                                            🔄 Pengembalian Dikonfirmasi
                                        </span>
                                    @else
                                        <span class="inline-block text-xs font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-full mb-1">
                                            📄 Surat Peminjaman
                                        </span>
                                    @endif
                                    <p class="text-sm font-medium text-gray-800 leading-snug">
                                        {{ $notif->data['pesan'] ?? 'Notifikasi baru' }}
                                    </p>

                                    @if(isset($notif->data['nomor_surat']))
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notif->data['nomor_surat'] }}</p>
                                    @endif

                                    @if(isset($notif->data['nama_barang']))
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="fa-solid fa-flask text-xs mr-1"></i>{{ $notif->data['nama_barang'] }}
                                        </p>
                                    @endif

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </p>

                                    {{-- Download hanya untuk Surat Bebas Lab --}}
                                    @if($isBebas && isset($notif->data['url_download']))
                                        <a href="{{ $notif->data['url_download'] }}"
                                           class="inline-flex items-center gap-1 mt-1.5 text-xs font-semibold
                                                  text-green-600 hover:text-green-800">
                                            <i class="fa-solid fa-file-arrow-down text-xs"></i>
                                            Download Surat Bebas Lab
                                        </a>
                                    @endif
                                </div>

                                {{-- Dot unread --}}
                                @if(is_null($notif->read_at))
                                    <div class="flex-shrink-0 ml-2 mt-2 w-2 h-2 rounded-full
                                                {{ $isBebas ? 'bg-green-500' : 'bg-indigo-500' }}"></div>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <i class="fa-solid fa-bell-slash fa-2x text-gray-200 mb-2"></i>
                                <p class="text-sm text-gray-400">Belum ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>
                    {{-- ===== END LIST NOTIFIKASI ===== --}}

                </div>
            </div>
            {{-- ===== END BELL ===== --}}

            <a href="{{ route('logout.get') }}"
               class="text-gray-400 hover:text-red-600 transition-colors"
               title="Logout"
               onclick="return confirm('Yakin ingin keluar?')">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
        @endauth
    </div>

    <header class="mb-8 text-center pt-4 md:pt-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-[var(--text-primary)]">Katalog Barang Laboratorium</h1>
        <p class="text-base sm:text-lg text-black mt-2">Pilih barang yang ingin Anda pinjam.</p>
    </header>

    <div id="search-container" class="search-bar-container sticky top-0 z-30 mb-8 transition-all duration-300 ease-in-out">
        <div class="search-bar-inner py-3 md:py-4 transition-all duration-300 ease-in-out">
            <div class="relative max-w-2xl mx-auto">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Cari nama alat atau bahan..."
                       class="w-full py-3 pl-12 pr-4 border border-gray-300 rounded-full shadow-sm bg-white focus:ring-2 focus:ring-[var(--accent-primary)] focus:border-[var(--accent-primary)] transition">
            </div>
        </div>
    </div>

    <div id="items-container">
        <section id="alat-lab" class="catalog-section">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] border-b-2 border-[var(--border-color)] pb-3 mb-6">Alat Laboratorium</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($alats as $item)
                <div class="item-card relative bg-[var(--bg-secondary)] rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 duration-300 flex flex-col">
                    @if($item->stok <= 0)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10 p-2">
                        <span class="text-sm md:text-base font-bold text-red-600 border-2 border-red-500 bg-white px-3 py-1 rounded-md">Stok Habis</span>
                    </div>
                    @endif
                    <div class="h-36 md:h-48 bg-gray-100 flex items-center justify-center">
                        @if(!empty($item->images))
                            <img src="{{ asset('storage/' . $item->images[0]) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-flask text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="font-semibold text-sm sm:text-base truncate item-name">{{ $item->nama }}</h3>
                        <p class="text-xs text-[var(--text-secondary)]">Stok: <span class="font-medium text-gray-800">{{ $item->stok }}</span> unit</p>
                        <p class="text-xs text-[var(--text-secondary)] mb-2">Kondisi: <span class="font-medium text-gray-800">{{ $item->kondisi ?? 'N/A' }}</span></p>
                        <button class="add-to-cart-btn mt-auto w-full bg-[var(--accent-primary)] text-white py-2 px-3 rounded-md font-semibold text-sm hover:bg-[var(--accent-hover)] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                            data-id="Alat-{{ $item->id }}" data-nama="{{ $item->nama }}" data-stok="{{ $item->stok }}" data-unit="unit" data-tipe="Alat"
                            @if($item->stok <= 0) disabled @endif>
                            <i class="fa-solid fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8 show-more-container" style="display:none;">
                <button class="show-more-btn inline-flex items-center justify-center rounded-full bg-indigo-100 px-6 py-2 font-semibold text-indigo-700 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:bg-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span>Lihat Semua</span><i class="fa-solid fa-angles-down ml-2"></i>
                </button>
            </div>
        </section>

        <section id="bahan-padat" class="catalog-section mt-10 md:mt-12">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] border-b-2 border-[var(--border-color)] pb-3 mb-6">Bahan Padat</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($bahan_padats as $item)
                <div class="item-card relative bg-[var(--bg-secondary)] rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 duration-300 flex flex-col">
                    @if($item->sisa_bahan <= 0)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10 p-2">
                        <span class="text-sm md:text-base font-bold text-red-600 border-2 border-red-500 bg-white px-3 py-1 rounded-md">Habis</span>
                    </div>
                    @endif
                    <div class="h-36 md:h-48 p-2 md:p-4">
                        <div class="formula-display text-2xl md:text-4xl">
                            @if(!empty($item->rumus_kimia)) {{ $item->rumus_kimia }}
                            @else <i class="fa-solid fa-cubes text-4xl text-gray-400"></i>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="font-semibold text-sm sm:text-base truncate item-name">{{ $item->nama }}</h3>
                        <p class="text-xs text-[var(--text-secondary)]">Sisa: <span class="font-medium text-gray-800">{{ $item->sisa_bahan }}</span> {{ $item->unit }}</p>
                        <p class="text-xs text-[var(--text-secondary)]">Letak: <span class="font-medium text-gray-800">{{ $item->letak }}</span></p>
                        <p class="text-xs text-[var(--text-secondary)] mb-2">Kedaluwarsa: <span class="font-medium text-gray-800">{{ $item->expired ? \Carbon\Carbon::parse($item->expired)->format('d M Y') : 'N/A' }}</span></p>
                        <button class="add-to-cart-btn mt-auto w-full bg-[var(--accent-primary)] text-white py-2 px-3 rounded-md font-semibold text-sm hover:bg-[var(--accent-hover)] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                            data-id="BahanPadat-{{ $item->id }}" data-nama="{{ $item->nama }}" data-stok="{{ $item->sisa_bahan }}" data-unit="{{ $item->unit }}" data-tipe="BahanPadat"
                            @if($item->sisa_bahan <= 0) disabled @endif>
                            <i class="fa-solid fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8 show-more-container" style="display:none;">
                <button class="show-more-btn inline-flex items-center justify-center rounded-full bg-indigo-100 px-6 py-2 font-semibold text-indigo-700 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:bg-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span>Lihat Semua</span><i class="fa-solid fa-angles-down ml-2"></i>
                </button>
            </div>
        </section>

        <section id="bahan-cair" class="catalog-section mt-10 md:mt-12">
            <h2 class="text-2xl font-bold text-[var(--text-primary)] border-b-2 border-[var(--border-color)] pb-3 mb-6">Bahan Cair</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($bahan_cairan_lamas as $item)
                <div class="item-card relative bg-[var(--bg-secondary)] rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 duration-300 flex flex-col">
                    @if($item->sisa_bahan <= 0)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10 p-2">
                        <span class="text-sm md:text-base font-bold text-red-600 border-2 border-red-500 bg-white px-3 py-1 rounded-md">Habis</span>
                    </div>
                    @endif
                    <div class="h-36 md:h-48 p-2 md:p-4">
                        <div class="formula-display text-2xl md:text-4xl">
                            {!! !empty($item->rumus_kimia) ? $item->rumus_kimia : '<i class="fa-solid fa-vial text-4xl text-gray-400"></i>' !!}
                        </div>
                    </div>
                    <div class="p-3 flex flex-col flex-grow">
                        <h3 class="font-semibold text-sm sm:text-base truncate item-name">{{ $item->nama }}</h3>
                        <p class="text-xs text-[var(--text-secondary)]">Sisa: <span class="font-medium text-gray-800">{{ $item->sisa_bahan }}</span> {{ $item->unit }}</p>
                        <p class="text-xs text-[var(--text-secondary)]">Letak: <span class="font-medium text-gray-800">{{ $item->letak }}</span></p>
                        <p class="text-xs text-[var(--text-secondary)] mb-2">Kedaluwarsa: <span class="font-medium text-gray-800">{{ $item->expired ? \Carbon\Carbon::parse($item->expired)->format('d M Y') : 'N/A' }}</span></p>
                        <button class="add-to-cart-btn mt-auto w-full bg-[var(--accent-primary)] text-white py-2 px-3 rounded-md font-semibold text-sm hover:bg-[var(--accent-hover)] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                            data-id="BahanCairanLama-{{ $item->id }}" data-nama="{{ $item->nama }}" data-stok="{{ $item->sisa_bahan }}" data-unit="{{ $item->unit }}" data-tipe="BahanCairanLama"
                            @if($item->sisa_bahan <= 0) disabled @endif>
                            <i class="fa-solid fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8 show-more-container" style="display:none;">
                <button class="show-more-btn inline-flex items-center justify-center rounded-full bg-indigo-100 px-6 py-2 font-semibold text-indigo-700 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:bg-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span>Lihat Semua</span><i class="fa-solid fa-angles-down ml-2"></i>
                </button>
            </div>
        </section>

        <div id="no-results-message" class="text-center py-16 hidden">
            <i class="fa-solid fa-box-open fa-4x text-gray-300"></i>
            <h3 class="mt-4 text-xl font-semibold text-gray-800">Tidak Ada Hasil</h3>
            <p class="mt-2 text-gray-500">Kami tidak dapat menemukan barang yang cocok.</p>
        </div>
    </div>
</div>

<button id="cart-button" class="fixed bottom-5 right-5 bg-[var(--accent-primary)] text-white w-14 h-14 md:w-16 md:h-16 rounded-full shadow-lg flex items-center justify-center text-2xl transform transition-transform hover:scale-110 z-20">
    <i class="fa-solid fa-clipboard-list"></i>
    <span id="cart-item-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-white">0</span>
</button>

<div id="cart-modal-overlay" class="fixed inset-0 bg-black bg-opacity-60 z-40 hidden">
    <div id="cart-modal" class="modal-enter fixed inset-0 flex items-center justify-center p-4">
        <form id="loan-form" action="{{ route('pinjam.store') }}" method="POST" class="bg-[var(--bg-secondary)] rounded-xl shadow-2xl w-full sm:max-w-lg max-h-[90vh] flex flex-col">
            @csrf
            <div class="flex justify-between items-center p-4 md:p-5 border-b border-[var(--border-color)]">
                <h2 class="text-xl md:text-2xl font-bold">Daftar Peminjaman</h2>
                <button type="button" id="close-modal-btn" class="text-gray-400 hover:text-gray-800 text-3xl">&times;</button>
            </div>
            <div class="p-4 md:p-6 overflow-y-auto">
                <div id="cart-items-container"></div>
                <div id="cart-empty-message" class="text-center py-10">
                    <i class="fa-solid fa-list-ul fa-3x text-gray-300"></i>
                    <p class="mt-4 text-[var(--text-secondary)]">Daftar peminjaman masih kosong.</p>
                </div>
                <div id="borrower-form-container" class="mt-6 pt-6 border-t border-[var(--border-color)]" style="display:none;">
                    <h3 class="text-lg md:text-xl font-bold mb-4">Data Diri Peminjam</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_peminjam" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_peminjam" name="nama_peminjam" required value="{{ old('nama_peminjam') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[var(--accent-primary)] focus:border-[var(--accent-primary)] sm:text-sm">
                        </div>
                        <div>
                            <label for="nim_peminjam" class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                            <input type="text" id="nim_peminjam" name="nim_peminjam" required value="{{ old('nim_peminjam') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[var(--accent-primary)] focus:border-[var(--accent-primary)] sm:text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="no_hp_input" class="block text-sm font-medium text-gray-700">Nomor HP Aktif <span class="text-red-500">*</span></label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">+62</span>
                                </div>
                                <input type="tel" id="no_hp_input" required pattern="[0-9\s-]*"
                                       class="block w-full rounded-md border-gray-300 pl-12 pr-3 py-2 focus:border-[var(--accent-primary)] focus:ring-[var(--accent-primary)] sm:text-sm"
                                       placeholder="812 3456 7890"
                                       value="{{ old('no_hp') ? substr(old('no_hp'), 3) : '' }}">
                            </div>
                        </div>
                        <input type="hidden" id="no_hp" name="no_hp">
                    </div>
                </div>
                <div id="hidden-inputs-for-cart"></div>
            </div>
            <div id="modal-footer" class="p-4 md:p-5 border-t border-[var(--border-color)] bg-gray-50 rounded-b-xl" style="display:none;">
                <button type="submit" class="w-full bg-[var(--accent-primary)] text-white py-3 px-6 rounded-lg font-semibold text-base md:text-lg hover:bg-[var(--accent-hover)] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--accent-primary)]">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const listButton            = document.getElementById('cart-button');
    const listModalOverlay      = document.getElementById('cart-modal-overlay');
    const listModal             = document.getElementById('cart-modal');
    const closeModalBtn         = document.getElementById('close-modal-btn');
    const listItemCount         = document.getElementById('cart-item-count');
    const listItemsContainer    = document.getElementById('cart-items-container');
    const listEmptyMessage      = document.getElementById('cart-empty-message');
    const borrowerFormContainer = document.getElementById('borrower-form-container');
    const modalFooter           = document.getElementById('modal-footer');
    const mainForm              = document.getElementById('loan-form');
    const hiddenInputsContainer = document.getElementById('hidden-inputs-for-cart');
    const searchInput           = document.getElementById('searchInput');
    const allSections           = document.querySelectorAll('.catalog-section');
    const noResultsMessage      = document.getElementById('no-results-message');
    const searchContainer       = document.getElementById('search-container');

    let loanList = {};
    const INITIAL_ITEMS_TO_SHOW = 8;
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

    const toggleModal = (show) => {
        if (show) {
            listModalOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            listModal.classList.remove('modal-leave');
            listModal.classList.add('modal-enter');
        } else {
            listModal.classList.remove('modal-enter');
            listModal.classList.add('modal-leave');
            setTimeout(() => { listModalOverlay.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
        }
    };

    const renderList = () => {
        listItemsContainer.innerHTML = '';
        const items = Object.values(loanList);
        if (items.length === 0) {
            listEmptyMessage.style.display      = 'block';
            borrowerFormContainer.style.display = 'none';
            modalFooter.style.display           = 'none';
        } else {
            listEmptyMessage.style.display      = 'none';
            borrowerFormContainer.style.display = 'block';
            modalFooter.style.display           = 'block';
            items.forEach(item => {
                const el = document.createElement('div');
                el.className = 'flex flex-wrap items-center justify-between gap-x-4 gap-y-2 py-3 border-b border-gray-200';
                const step = item.tipe === 'Alat' ? '1' : '0.01';
                el.innerHTML = `
                    <div class="flex-grow min-w-[120px]">
                        <p class="font-semibold text-gray-800">${item.nama}</p>
                        <p class="text-sm text-gray-500">Stok: ${item.stok} ${item.unit}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="quantity-change-btn w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-200 hover:bg-gray-300 font-bold flex-shrink-0" data-id="${item.id}" data-change="-1">-</button>
                        <input type="number" value="${item.quantity}" min="1" max="${item.stok}" step="${step}" class="w-16 md:w-20 text-center border-gray-300 rounded-md quantity-input" data-id="${item.id}">
                        <button type="button" class="quantity-change-btn w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-200 hover:bg-gray-300 font-bold flex-shrink-0" data-id="${item.id}" data-change="1">+</button>
                    </div>
                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 text-lg ml-auto md:ml-0" data-id="${item.id}"><i class="fa-solid fa-trash-can"></i></button>
                `;
                listItemsContainer.appendChild(el);
            });
        }
        listItemCount.textContent   = items.length;
        listItemCount.style.display = items.length > 0 ? 'flex' : 'none';
    };

    const addItemToList = (button) => {
        if (!isLoggedIn) {
            Swal.fire({ icon:'warning', title:'Login Diperlukan', text:'Anda harus login terlebih dahulu menggunakan email Uin AR-raniry untuk dapat meminjam barang.', confirmButtonText:'Login dengan Google', confirmButtonColor:'#4F46E5' })
                .then(r => { if (r.isConfirmed) window.location.href = '{{ route("google.redirect") }}'; });
            return;
        }
        const data = button.dataset;
        if (loanList[data.id]) { Swal.fire({toast:true,position:'top-end',icon:'info',title:'Barang sudah di daftar',showConfirmButton:false,timer:1500}); return; }
        loanList[data.id] = { id:data.id, nama:data.nama, stok:parseFloat(data.stok), unit:data.unit, tipe:data.tipe, quantity:1 };
        renderList();
        Swal.fire({toast:true,position:'top-end',icon:'success',title:'Ditambahkan ke daftar',showConfirmButton:false,timer:1500});
    };

    const changeItemQuantity = (id, change) => {
        if (!loanList[id]) return;
        const item = loanList[id], step = item.tipe === 'Alat' ? 1 : 0.01;
        let qty = parseFloat((item.quantity + change * step).toFixed(2));
        if (qty >= step && qty <= item.stok) { item.quantity = qty; }
        else if (qty > item.stok) { item.quantity = item.stok; Swal.fire({toast:true,position:'top-end',icon:'error',title:'Melebihi stok!',showConfirmButton:false,timer:1500}); }
        else { removeItemFromList(id); }
        renderList();
    };

    const removeItemFromList = (id) => { delete loanList[id]; renderList(); };

    listButton.addEventListener('click', () => toggleModal(true));
    closeModalBtn.addEventListener('click', () => toggleModal(false));
    listModalOverlay.addEventListener('click', e => { if (e.target === listModalOverlay) toggleModal(false); });
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => btn.addEventListener('click', e => { e.preventDefault(); addItemToList(e.currentTarget); }));

    listItemsContainer.addEventListener('click', e => {
        const btn = e.target.closest('button'); if (!btn) return;
        const id = btn.dataset.id;
        if (btn.classList.contains('quantity-change-btn')) changeItemQuantity(id, parseInt(btn.dataset.change));
        else if (btn.classList.contains('remove-item-btn')) removeItemFromList(id);
    });

    listItemsContainer.addEventListener('change', e => {
        if (!e.target.classList.contains('quantity-input')) return;
        const id = e.target.dataset.id; if (!loanList[id]) return;
        let qty = parseFloat(e.target.value);
        if (isNaN(qty) || qty < 0) qty = 1;
        if (qty > loanList[id].stok) { qty = loanList[id].stok; Swal.fire({toast:true,position:'top-end',icon:'error',title:'Melebihi stok!',showConfirmButton:false,timer:1500}); }
        if (qty === 0) { removeItemFromList(id); return; }
        loanList[id].quantity = qty; e.target.value = qty;
    });

    mainForm.addEventListener('submit', e => {
        e.preventDefault();
        const noHpInput = document.getElementById('no_hp_input');
        document.getElementById('no_hp').value = '+62' + noHpInput.value.replace(/[^0-9]/g,'');
        if (!document.getElementById('nama_peminjam').value || !document.getElementById('nim_peminjam').value || !noHpInput.value) {
            Swal.fire('Data Tidak Lengkap','Mohon isi semua data diri peminjam.','warning'); return;
        }
        hiddenInputsContainer.innerHTML = '';
        Object.values(loanList).forEach((item, i) => {
            const [type, id] = item.id.split('-');
            hiddenInputsContainer.innerHTML += `<input type="hidden" name="items[${i}][item_id]" value="${id}"><input type="hidden" name="items[${i}][item_type]" value="${type}"><input type="hidden" name="items[${i}][jumlah_pinjam]" value="${item.quantity}">`;
        });
        Swal.fire({ title:'Konfirmasi Peminjaman', text:`Anda akan mengajukan peminjaman untuk ${Object.keys(loanList).length} jenis barang. Lanjutkan?`, icon:'question', showCancelButton:true, confirmButtonColor:'#4F46E5', cancelButtonColor:'#d33', confirmButtonText:'Ya, Lanjutkan!', cancelButtonText:'Batal' })
            .then(r => { if (r.isConfirmed) mainForm.submit(); });
    });

    allSections.forEach(section => {
        const items = Array.from(section.querySelectorAll('.grid .item-card'));
        const smc   = section.querySelector('.show-more-container');
        if (!smc) return;
        const updateVisibility = (expanded) => {
            items.forEach((item, i) => { item.style.display = (expanded || i < INITIAL_ITEMS_TO_SHOW) ? 'flex' : 'none'; });
            smc.style.display = (expanded || items.length <= INITIAL_ITEMS_TO_SHOW) ? 'none' : 'block';
            section.dataset.expanded = expanded;
        };
        updateVisibility(false);
        smc.querySelector('.show-more-btn').addEventListener('click', () => updateVisibility(true));
    });

    searchInput.addEventListener('input', function () {
        const term = this.value.toLowerCase().trim();
        let total = 0;
        allSections.forEach(section => {
            let visible = 0;
            section.querySelectorAll('.item-card').forEach(card => {
                const name    = card.querySelector('.item-name').textContent.toLowerCase();
                const formula = card.querySelector('.formula-display')?.textContent.toLowerCase().trim() ?? '';
                const match   = name.includes(term) || formula.includes(term);
                card.style.display = match ? 'flex' : 'none';
                if (match) visible++;
            });
            section.style.display = visible > 0 ? 'block' : 'none';
            total += visible;
            const smc = section.querySelector('.show-more-container');
            if (smc) smc.style.display = 'none';
        });
        noResultsMessage.style.display = total === 0 ? 'block' : 'none';
        if (term === '') {
            allSections.forEach(section => {
                section.style.display = 'block';
                const items    = Array.from(section.querySelectorAll('.grid .item-card'));
                const expanded = section.dataset.expanded === 'true';
                const smc      = section.querySelector('.show-more-container');
                items.forEach((item, i) => { item.style.display = (expanded || i < INITIAL_ITEMS_TO_SHOW) ? 'flex' : 'none'; });
                if (smc && items.length > INITIAL_ITEMS_TO_SHOW && !expanded) smc.style.display = 'block';
            });
        }
    });

    if (searchContainer) {
        const threshold = searchContainer.offsetTop;
        window.addEventListener('scroll', () => searchContainer.classList.toggle('is-sticky', window.scrollY > threshold));
    }

    renderList();
    if ("{{ $errors->any() }}") toggleModal(true);

    //{{-- ===== NOTIFIKASI SUKSES + TOMBOL DOWNLOAD SURAT ===== --}}
    @if (session('success'))
        @if (session('nomor_surat'))
            Swal.fire({
                icon: 'success',
                title: 'Peminjaman Berhasil! 🎉',
                html: `
                    <p class="text-gray-600 mb-3">{{ session('success') }}</p>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-3 mb-4 text-left">
                        <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide mb-1">Nomor Surat</p>
                        <p class="text-indigo-800 font-bold text-sm">{{ session('nomor_surat') }}</p>
                    </div>
                    <p class="text-sm text-gray-500">Menunggu persetujuan dari admin laboratorium.</p>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#4F46E5',
                allowOutsideClick: false,
            });
        @else
            Swal.fire({toast:true,position:'top-end',icon:'success',title:"{{ session('success') }}",showConfirmButton:false,timer:3500,timerProgressBar:true,
                didOpen:t=>{ t.addEventListener('mouseenter',Swal.stopTimer); t.addEventListener('mouseleave',Swal.resumeTimer); }});
        @endif
    @endif

    //{{-- ===== NOTIFIKASI ERROR ===== --}}
    @if ($errors->any())
        Swal.fire({toast:true,position:'top-end',icon:'error',title:"{{ $errors->first() }}",showConfirmButton:false,timer:5000,timerProgressBar:true,
            didOpen:t=>{ t.addEventListener('mouseenter',Swal.stopTimer); t.addEventListener('mouseleave',Swal.resumeTimer); }});
    @endif
});
</script>

<footer class="bg-[var(--accent-primary)] mt-16 md:mt-24 relative z-10">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <h3 class="text-xl font-bold text-white">Laboratorium Terpadu</h3>
                <p class="mt-4 text-indigo-100 text-base pr-4">Pusat layanan praktikum, penelitian, dan pengembangan ilmu pengetahuan di lingkungan Fakultas Sains & Teknologi.</p>
            </div>
            <div>
                <h4 class="font-semibold text-base text-white tracking-wider">Tautan Terkait</h4>
                <ul class="mt-4 space-y-3">
                    <li><a href="https://ar-raniry.ac.id/" class="text-indigo-100 hover:text-white transition-colors">Website Utama UIN</a></li>
                    <li><a href="https://fst.uin.ar-raniry.ac.id/" class="text-indigo-100 hover:text-white transition-colors">Fakultas Sains & Teknologi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-base text-white tracking-wider">Kontak</h4>
                <ul class="mt-4 space-y-3">
                    <li class="flex items-start">
                        <i class="fa-solid fa-map-marker-alt text-indigo-200 mt-1 mr-3 flex-shrink-0"></i>
                        <span class="text-indigo-100">Jl. Syeikh Abdul Rauf, Kopelma Darussalam, Banda Aceh</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-envelope text-indigo-200 mr-3 flex-shrink-0"></i>
                        <a href="mailto:fst.prodikimia@ar-raniry.ac.id" class="text-indigo-100 hover:text-white transition-colors">fst.prodikimia@ar-raniry.ac.id</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-phone text-indigo-200 mr-3 flex-shrink-0"></i>
                        <a class="text-indigo-100 hover:text-white transition