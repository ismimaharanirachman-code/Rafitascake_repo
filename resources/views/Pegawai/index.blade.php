<h1>Data Pegawai</h1>

<a href="/Pegawai/create">Tambah Pegawai</a>

<br><br>

@foreach ($pegawai as $s)
    <p>
        {{ $s->id_pegawai }} | 
        {{ $s->nama_pegawai }} | 
        {{ $s->jabatan }}
        {{ $s->alamat_pegawai }}
        {{ $s->no_hp }}
    </p>
@endforeach