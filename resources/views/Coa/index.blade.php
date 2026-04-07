<h1>Data COA</h1>

<a href="/coa/create">Tambah Akun</a>

<br><br>

@foreach ($coa as $c)
    <p> 
        {{ $c->kode_akun }}|
        {{ $c->nama_akun }}|
        {{ $c->tipe_akun }}
    </p>
@endforeach