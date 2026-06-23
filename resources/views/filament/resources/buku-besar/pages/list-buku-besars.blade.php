<x-filament-panels::page>
    <div class="space-y-6">
        
        {{-- Form Filter Component --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <form wire:submit.prevent="$refresh" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-3">
                        {{ $this->form }}
                    </div>
                    <div>
                        <x-filament::button type="submit" color="primary" class="w-full">
                            Proses Filter
                        </x-filament::button>
                    </div>
                </div>
            </form>
        </div>

        @php
            $state = $this->form->getState();
            $records = $this->getFilteredRecords();
            $totalDebit = $records->sum('debit');
            $totalKredit = $records->sum('kredit');
            
            // Mengambil nama akun yang sedang aktif dipilih
            $namaAkunTerpilih = '-';
            if(!empty($state['coa_id'])) {
                $akun = \App\Models\Coa::find($state['coa_id']);
                $namaAkunTerpilih = $akun ? $akun->kode_akun . ' - ' . $akun->nama_akun : '-';
            }

            // Aturan Saldo Awal & Akhir Akuntansi Laporan
            $saldoAwal = 0; 
            $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;
            
            $p_awal = !empty($state['periode_awal']) ? \Carbon\Carbon::parse($state['periode_awal'])->translatedFormat('d F Y') : '-';
            $p_akhir = !empty($state['periode_akhir']) ? \Carbon\Carbon::parse($state['periode_akhir'])->translatedFormat('d F Y') : '-';
        @endphp

        {{-- Laporan Hasil Buku Besar --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Toko RafitasCake</h2>
                <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Laporan Buku Besar</h3>
                <p class="text-md font-medium text-primary-600 dark:text-primary-400 mt-1">
                    Akun: {{ $namaAkunTerpilih }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Periode: {{ $p_awal }} s/d {{ $p_akhir }}
                </p>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-left border-collapse bg-white dark:bg-gray-800 text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                            <th colspan="4" class="px-4 py-3 font-semibold text-right">Saldo Awal</th>
                            <th colspan="2" class="px-4 py-3 font-bold text-right text-gray-800 dark:text-white">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</th>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-medium">
                            <th class="px-4 py-3 text-center w-20">ID Jurnal</th>
                            <th class="px-4 py-3 w-32">Tanggal</th>
                            <th class="px-4 py-3">Keterangan / Deskripsi</th>
                            <th class="px-4 py-3 w-32">No. Referensi</th>
                            <th class="px-4 py-3 text-right w-36">Debet</th>
                            <th class="px-4 py-3 text-right w-36">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($records as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                                <td class="px-4 py-3 text-center font-semibold text-primary-600">#{{ $row->jurnal_id }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d-M-Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="block font-medium text-gray-900 dark:text-white">{{ $row->keterangan }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $row->no_referensi ?? '-' }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600 font-medium">
                                    {{ $row->debit > 0 ? 'Rp ' . number_format($row->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-red-600 font-medium">
                                    {{ $row->kredit > 0 ? 'Rp ' . number_format($row->kredit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                                    Silahkan pilih Akun COA dan klik "Proses Filter" untuk menampilkan mutasi buku besar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 dark:bg-gray-700/30 font-semibold text-gray-700 dark:text-gray-200 border-t border-gray-200 dark:border-gray-700">
                            <td colspan="4" class="px-4 py-3 text-right">Total Mutasi Periode</td>
                            <td class="px-4 py-3 text-right text-emerald-600">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-red-600">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-700 font-bold text-gray-800 dark:text-white">
                            <td colspan="4" class="px-4 py-3 text-right">Saldo Akhir</td>
                            <td colspan="2" class="px-4 py-3 text-right text-primary-600 dark:text-primary-400">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</x-filament-panels::page>