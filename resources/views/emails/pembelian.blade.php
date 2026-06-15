<h2>Invoice Pembelian: {{ $data['kode_pembelian'] }}</h2>

<p>Halo <strong>{{ $data['Nama_Supplier'] }}</strong>,</p>

<p>Kami dari tim pembelian <strong>Rafita's Cake and Bakery</strong> ingin mengonfirmasi bahwa pembayaran untuk pesanan bahan baku kami telah berhasil dilakukan.</p>

<div style="background-color: #fff1f2; padding: 15px; border-radius: 8px; border: 1px solid #f472b6;">
    <p><strong>Ringkasan Pesanan:</strong></p>
    <ul>
        <li>Nomor Invoice: {{ $data['kode_pembelian'] }}</li>
        <li>Tanggal: {{ $data['tanggal'] }}</li>
        <li>Total Bayar: Rp {{ number_format($data['total'], 0, ',', '.') }}</li>
    </ul>
</div>

<p>Detail barang yang kami pesan dapat Anda lihat pada <strong>file PDF yang terlampir</strong> dalam email ini.</p>

<p>Mohon segera menyiapkan bahan baku tersebut dan mengirimkannya ke alamat kami. Jika ada kendala, silakan hubungi kami kembali.</p>

<p>Terima kasih atas kerja samanya.</p>

<hr>
<p style="color: #be185d;"><strong>Rafita's Cake and Bakery</strong><br>
