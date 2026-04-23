<div class="space-y-4">

    {{-- Info Peminjam --}}
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-800">
        <h3 style="font-weight:600;color:#6b7280;margin-bottom:12px;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;">
            Informasi Peminjam
        </h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;">
            <div><span style="color:#9ca3af;">Nama</span><p style="font-weight:600;">{{ $record->nama_peminjam }}</p></div>
            <div><span style="color:#9ca3af;">NIM</span><p style="font-weight:600;">{{ $record->nim_peminjam }}</p></div>
            <div><span style="color:#9ca3af;">No. HP</span><p style="font-weight:600;">{{ $record->no_hp }}</p></div>
            <div><span style="color:#9ca3af;">Nomor Surat</span><p style="font-weight:600;">{{ $record->nomor_surat }}</p></div>
        </div>
    </div>

    {{-- Warning ada barang rusak --}}
    @if($items->where('kondisi_pengembalian', 'rusak')->count() > 0)
    <div style="border:1px solid #fca5a5;border-radius:8px;padding:12px;background:#fef2f2;display:flex;gap:8px;">
        <svg style="width:20px;height:20px;color:#ef4444;flex-shrink:0;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p style="font-size:13px;font-weight:600;color:#b91c1c;">Terdapat alat yang rusak</p>
            <p style="font-size:12px;color:#dc2626;margin-top:2px;">Surat Bebas Lab tidak dapat diterbitkan selama ada alat yang dilaporkan rusak.</p>
        </div>
    </div>
    @endif

    {{-- Daftar Barang --}}
    <div>
        <h3 style="font-weight:600;color:#6b7280;margin-bottom:8px;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;">
            Daftar Barang ({{ $items->count() }} barang)
        </h3>
        <div class="space-y-2">
            @foreach($items as $i => $item)
            @php
                $tipe    = class_basename($item->peminjamable_type);
                $isAlat  = $tipe === 'Alat';
                $isRusak = $item->kondisi_pengembalian === 'rusak';
                $labelTipe = match($tipe) {
                    'Alat'            => 'Alat',
                    'BahanPadat'      => 'Bahan Padat',
                    'BahanCairanLama' => 'Bahan Cair',
                    default           => $tipe,
                };
                $satuanJumlah = $isAlat
                    ? $item->jumlah . ' unit'
                    : $item->jumlah . ' ' . ($item->peminjamable?->unit ?? '');
                $badgeBg = match($item->status) {
                    'Menunggu Persetujuan' => '#f3f4f6',
                    'Disetujui'            => '#fef3c7',
                    'Dikembalikan'         => $isRusak ? '#fee2e2' : '#d1fae5',
                    default                => '#f3f4f6',
                };
                $badgeColor = match($item->status) {
                    'Menunggu Persetujuan' => '#374151',
                    'Disetujui'            => '#92400e',
                    'Dikembalikan'         => $isRusak ? '#991b1b' : '#065f46',
                    default                => '#374151',
                };
            @endphp

            <div x-data="{ open: false }"
                 style="border-radius:8px;border:1px solid {{ $isRusak ? '#fca5a5' : '#e5e7eb' }};overflow:hidden;">

                {{-- Baris utama --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:{{ $isRusak ? '#fff5f5' : 'transparent' }};">
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <span style="color:#9ca3af;font-size:13px;width:18px;text-align:center;flex-shrink:0;">{{ $i + 1 }}</span>
                        <div>
                            <p style="font-weight:600;font-size:13px;">{{ $item->peminjamable?->nama ?? '-' }}</p>
                            <p style="font-size:11px;color:#6b7280;">
                                {{ $labelTipe }} &bull; {{ $satuanJumlah }}
                                @if(!$isAlat) &bull; <span style="color:#d97706;">Bahan sekali pakai</span>@endif
                            </p>
                        </div>
                    </div>

                    {{-- Badge status --}}
                    <span style="padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeColor }};flex-shrink:0;margin:0 10px;">
                        {{ $item->status }}
                        @if($item->status === 'Dikembalikan' && $item->kondisi_pengembalian)
                            — {{ $isRusak ? '⚠ Rusak' : '✓ Baik' }}
                        @endif
                    </span>

                    {{-- Tombol aksi --}}
                    <div style="flex-shrink:0;display:flex;gap:6px;align-items:center;">

                        @if($item->status === 'Menunggu Persetujuan')
                            <a href="{{ route('admin.peminjaman.setujui-item', $item->id) }}"
                                onclick="return confirm('Setujui peminjaman barang ini?')"
                                style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;background:#16a34a;color:#fff;text-decoration:none;">
                                ✓ Setujui
                            </a>

                        @elseif($item->status === 'Disetujui')
                            <button type="button" @click="open = !open"
                                style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;background:#2563eb;color:#fff;border:none;cursor:pointer;">
                                ↩ Konfirmasi Kembali
                            </button>

                        @elseif($item->status === 'Dikembalikan')
                            @if($isRusak)
                                <span style="font-size:12px;font-weight:600;color:#dc2626;">⚠ Rusak</span>
                                <a href="{{ route('admin.peminjaman.tandai-baik', $item->id) }}"
                                    onclick="return confirm('Tandai alat ini sudah DIGANTI/DIPERBAIKI dan kondisinya BAIK?')"
                                    style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#16a34a;color:#fff;text-decoration:none;">
                                    ✓ Tandai Diganti
                                </a>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#16a34a;">
                                    ✓ Selesai
                                </span>
                            @endif
                        @endif

                    </div>
                </div>

                {{-- Panel konfirmasi kondisi per item (slide Alpine) --}}
                @if($item->status === 'Disetujui')
                <div x-show="open" x-transition
                     style="border-top:1px solid #e5e7eb;padding:12px 14px;background:#f9fafb;">
                    @if($isAlat)
                        <p style="font-size:12px;font-weight:600;color:#6b7280;margin-bottom:8px;">Pilih kondisi alat:</p>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                            <a href="{{ route('admin.peminjaman.kembalikan-item', [$item->id, 'baik']) }}"
                                onclick="return confirm('Konfirmasi: alat dikembalikan kondisi BAIK?')"
                                style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;background:#16a34a;color:#fff;text-decoration:none;">
                                ✓ Kondisi Baik
                            </a>
                            <a href="{{ route('admin.peminjaman.kembalikan-item', [$item->id, 'rusak']) }}"
                                onclick="return confirm('Alat ini RUSAK? Surat Bebas Lab tidak dapat diterbitkan.')"
                                style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;background:#dc2626;color:#fff;text-decoration:none;">
                                ⚠ Kondisi Rusak
                            </a>
                            <button @click="open = false"
                                style="padding:8px 12px;font-size:12px;border-radius:8px;border:1px solid #d1d5db;background:transparent;color:#6b7280;cursor:pointer;">
                                Batal
                            </button>
                        </div>
                    @else
                        <p style="font-size:12px;color:#92400e;margin-bottom:8px;">
                            Konfirmasi bahan selesai digunakan. <strong>Stok tidak bertambah kembali.</strong>
                        </p>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <a href="{{ route('admin.peminjaman.kembalikan-item', [$item->id, 'baik']) }}"
                                onclick="return confirm('Konfirmasi bahan ini telah selesai digunakan?')"
                                style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;background:#2563eb;color:#fff;text-decoration:none;">
                                ✓ Konfirmasi Selesai
                            </a>
                            <button @click="open = false"
                                style="padding:8px 12px;font-size:12px;border-radius:8px;border:1px solid #d1d5db;background:transparent;color:#6b7280;cursor:pointer;">
                                Batal
                            </button>
                        </div>
                    @endif
                </div>
                @endif

            </div>
            @endforeach
        </div>

        {{-- ✅ KONFIRMASI SEMUA PENGEMBALIAN — tanpa cek kondisi, langsung konfirmasi semua baik --}}
        @if($items->where('status', 'Disetujui')->count() > 1)
        <div style="margin-top:12px;">
            <a href="{{ route('admin.peminjaman.kembalikan-semua', urlencode($record->nomor_surat)) }}"
                onclick="return confirm('Konfirmasi semua pengembalian sebagai kondisi BAIK?')"
                style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:8px;font-size:13px;font-weight:600;background:#1d4ed8;color:#fff;text-decoration:none;">
                ↩ Konfirmasi Semua Pengembalian ({{ $items->where('status', 'Disetujui')->count() }} barang)
            </a>
        </div>
        @endif

        {{-- Setujui Semua (untuk yg masih Menunggu) --}}
        @if($items->where('status', 'Menunggu Persetujuan')->count() > 1)
        <div style="margin-top:8px;display:flex;justify-content:flex-end;">
            <a href="{{ route('admin.peminjaman.setujui-semua', urlencode($record->nomor_surat)) }}"
                onclick="return confirm('Setujui semua barang yang masih menunggu?')"
                style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;background:#16a34a;color:#fff;text-decoration:none;">
                ✓ Setujui Semua ({{ $items->where('status', 'Menunggu Persetujuan')->count() }} barang)
            </a>
        </div>
        @endif
    </div>
</div>