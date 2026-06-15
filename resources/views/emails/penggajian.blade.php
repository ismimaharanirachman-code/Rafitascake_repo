<h2>Slip Gaji: {{ $record->id_penggajian }}</h2>

<p>Halo {{ $record->pegawai->nama_pegawai }},</p>

<p>Berikut adalah rincian gaji Anda untuk periode <strong>{{ $record->periode_gaji }}</strong>:</p>

<ul>
    <li>Gaji Pokok: Rp {{ number_format($record->gaji_pokok, 0, ',', '.') }}</li>
    <li>Tunjangan: Rp {{ number_format($record->tunjangan, 0, ',', '.') }}</li>
    <li>Potongan: Rp {{ number_format($record->potongan, 0, ',', '.') }}</li>
</ul>

<p><strong>Total Gaji Diterima: Rp {{ number_format($record->total_gaji, 0, ',', '.') }}</strong></p>

<p>Terima kasih atas kerja keras Anda di Rafitas Cake.</p>