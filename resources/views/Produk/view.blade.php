<h1>Data Produk</h1>

<a href="/produk/create">Tambah Produk</a>

<br><br>

@foreach ($produk as $s)
    <p>
        {{ $s->nama_kue }} | 
        {{ $s->jenis_kue}} | 
        {{ $s->harga_jual}}|
        {{ $s->stok
     }}
    </p>
@endforeach