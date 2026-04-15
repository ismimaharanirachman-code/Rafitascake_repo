<h1>Data Pelanggan</h1>

<a href="/pelanggan/create">Tambah Pelanggan</a>
//
<br><br>

@foreach ($pelanggan as $s)
    <p>
        {{ $s->nama_pelanggan }} | 
        {{ $s->nomor_hp}} | 
        {{ $s->alamat }}
    </p>
@endforeach