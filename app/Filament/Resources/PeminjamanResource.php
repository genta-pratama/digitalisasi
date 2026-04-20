<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;
    protected static ?string $navigationGroup = 'Action Peminjaman';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Peminjaman';
    protected static ?string $label = 'Peminjaman';
    protected static ?string $pluralLabel = 'Peminjaman';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'Menunggu Persetujuan')
            ->distinct('nomor_surat')
            ->count('nomor_surat');
        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Ambil 1 perwakilan per nomor_surat
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereIn('id', function ($sub) {
                    $sub->selectRaw('MIN(id)')
                        ->from('peminjamans')
                        ->groupBy('nomor_surat');
                })->with('peminjamable');
            })
            ->columns([
                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_peminjam')
                    ->label('Nama Peminjam')
                    ->searchable(),

                TextColumn::make('nim_peminjam')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('no_hp')
                    ->label('No. HP'),

                TextColumn::make('jumlah_barang')
                    ->label('Jumlah Barang')
                    ->getStateUsing(fn (Peminjaman $record) =>
                        Peminjaman::where('nomor_surat', $record->nomor_surat)->count() . ' barang'
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn (Peminjaman $record) =>
                        static::getStatusGabungan($record->nomor_surat)
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Persetujuan'  => 'gray',
                        'Disetujui'             => 'warning',
                        'Ditolak'               => 'danger',
                        'Dikembalikan'          => 'success',
                        'Sebagian Dikembalikan' => 'info',
                        default                 => 'gray',
                    }),

                TextColumn::make('tanggal_pinjam')
                    ->label('Tgl. Pinjam')
                    ->date(),

                TextColumn::make('tanggal_kembali')
                    ->label('Tgl. Kembali')
                    ->getStateUsing(fn (Peminjaman $record) =>
                        Peminjaman::where('nomor_surat', $record->nomor_surat)
                            ->whereNotNull('tanggal_kembali')
                            ->max('tanggal_kembali')
                    )
                    ->date(),
            ])
            ->actions([
                // --- 👁️ DETAIL BARANG + AKSI PER ITEM DI DALAM MODAL ---
                Action::make('lihat_detail')
                    ->label('Detail & Kelola')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('primary')
                    ->modalHeading(fn (Peminjaman $record) =>
                        'Detail Peminjaman — ' . $record->nomor_surat
                    )
                    ->modalContent(fn (Peminjaman $record) => view(
                        'filament.modals.detail-peminjaman',
                        [
                            'items'  => Peminjaman::where('nomor_surat', $record->nomor_surat)
                                ->with('peminjamable')
                                ->get(),
                            'record' => $record,
                        ]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // --- 📄 TERBITKAN SURAT BEBAS LAB GABUNGAN ---
                Action::make('terbitkan_gabungan')
                    ->label('Terbitkan Surat Bebas Lab')
                    ->color('success')
                    ->icon('heroicon-o-document-check')
                    ->url(fn (Peminjaman $record): string =>
                        route('surat-bebas-lab-gabungan.terbitkan', ['nim' => $record->nim_peminjam])
                    )
                    ->openUrlInNewTab()
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Surat Bebas Lab?')
                    ->modalDescription(fn (Peminjaman $record) =>
                        'Surat bebas lab untuk ' . $record->nama_peminjam .
                        ' (' . $record->nim_peminjam . ') akan diterbitkan.'
                    )
                    ->visible(fn (Peminjaman $record): bool =>
                        static::getStatusGabungan($record->nomor_surat) === 'Dikembalikan' &&
                        !Peminjaman::where('nim_peminjam', $record->nim_peminjam)
                            ->where('surat_bebas_lab_diterbitkan', true)
                            ->exists() &&
                        // Blokir jika ada alat yang dilaporkan rusak
                        !Peminjaman::where('nomor_surat', $record->nomor_surat)
                            ->where('kondisi_pengembalian', 'rusak')
                            ->exists()
                    ),

                // --- ⚠️ INFO: Ada barang rusak, surat tidak bisa diterbitkan ---
                Action::make('ada_rusak')
                    ->label('Ada Barang Rusak')
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->disabled()
                    ->visible(fn (Peminjaman $record): bool =>
                        static::getStatusGabungan($record->nomor_surat) === 'Dikembalikan' &&
                        Peminjaman::where('nomor_surat', $record->nomor_surat)
                            ->where('kondisi_pengembalian', 'rusak')
                            ->exists() &&
                        !Peminjaman::where('nim_peminjam', $record->nim_peminjam)
                            ->where('surat_bebas_lab_diterbitkan', true)
                            ->exists()
                    ),

                // --- ⬇️ DOWNLOAD SURAT BEBAS LAB ---
                Action::make('download_gabungan')
                    ->label('Download Surat Bebas Lab')
                    ->color('warning')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Peminjaman $record): string =>
                        route('surat-bebas-lab-gabungan.download', ['nim' => $record->nim_peminjam])
                    )
                    ->openUrlInNewTab()
                    ->visible(fn (Peminjaman $record): bool =>
                        Peminjaman::where('nim_peminjam', $record->nim_peminjam)
                            ->where('surat_bebas_lab_diterbitkan', true)
                            ->exists()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Override delete — hapus SEMUA item dalam nomor_surat yang dipilih
                    Tables\Actions\BulkAction::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Peminjaman?')
                        ->modalDescription('Semua barang dalam surat yang dipilih akan dihapus permanen.')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                // Hapus semua item dalam nomor_surat yang sama
                                Peminjaman::where('nomor_surat', $record->nomor_surat)->delete();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->poll('5s');
    }

    protected static function getStatusGabungan(string $nomorSurat): string
    {
        $statuses = Peminjaman::where('nomor_surat', $nomorSurat)
            ->pluck('status')
            ->unique()
            ->values();

        if ($statuses->contains('Menunggu Persetujuan')) return 'Menunggu Persetujuan';
        // Jika ada yg Disetujui (termasuk campuran Disetujui + Dikembalikan = sebagian kembali)
        if ($statuses->contains('Disetujui') && $statuses->contains('Dikembalikan')) return 'Sebagian Dikembalikan';
        if ($statuses->contains('Disetujui'))            return 'Disetujui';
        if ($statuses->contains('Dikembalikan'))         return 'Dikembalikan';
        if ($statuses->contains('Ditolak'))              return 'Ditolak';

        return $statuses->first() ?? 'Unknown';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit'   => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}