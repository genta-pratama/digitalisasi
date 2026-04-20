{{--
    Komponen Bell Notifikasi
    Taruh di layout utama (misal: resources/views/layouts/app.blade.php)
    Di dalam navbar, tambahkan: @include('components.notification-bell')
--}}

<div class="relative" x-data="{ open: false }">

    {{-- TOMBOL BELL --}}
    <button @click="open = !open"
            class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">

        {{-- Icon Bell --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002
                     0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159
                     c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge jumlah notifikasi belum dibaca --}}
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center
                         px-2 py-1 text-xs font-bold leading-none text-white
                         bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    {{-- DROPDOWN NOTIFIKASI --}}
    <div x-show="open"
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
         style="display: none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-xs text-blue-600 hover:underline">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- List Notifikasi --}}
        <div class="max-h-80 overflow-y-auto">
            @forelse(auth()->user()->notifications->take(10) as $notif)
                <div class="flex items-start px-4 py-3 border-b border-gray-100
                            hover:bg-gray-50 transition
                            {{ is_null($notif->read_at) ? 'bg-blue-50' : '' }}">

                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    {{ is_null($notif->read_at) ? 'bg-blue-100' : 'bg-gray-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 {{ is_null($notif->read_at) ? 'text-blue-600' : 'text-gray-500' }}"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                         a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $notif->data['pesan'] ?? 'Notifikasi baru' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>

                        {{-- Tombol Download --}}
                        @if(isset($notif->data['url_download']))
                            <a href="{{ $notif->data['url_download'] }}"
                               class="inline-flex items-center mt-2 text-xs text-blue-600
                                      hover:text-blue-800 hover:underline font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0
                                             0l-4-4m4 4V4"/>
                                </svg>
                                Download Surat PDF
                            </a>
                        @endif
                    </div>

                    {{-- Dot belum dibaca --}}
                    @if(is_null($notif->read_at))
                        <div class="flex-shrink-0 ml-2 mt-2">
                            <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-3"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                                 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3
                                 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2 border-t border-gray-200">
            <a href="{{ route('peminjaman.index') }}"
               class="text-xs text-blue-600 hover:underline">
                Lihat semua peminjaman →
            </a>
        </div>
    </div>
</div>