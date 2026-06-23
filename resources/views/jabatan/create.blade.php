<h2>Tambah Jabatan</h2>

<form action="{{ route('jabatan.store') }}" method="POST">
    @csrf

    <p>Nama Jabatan</p>
    <input type="text" name="nama_jabatan">

    <br><br>

    <p>Gaji Pokok</p>
    <input type="number" name="gaji_pokok">

    <br><br>

    <button type="submit">Simpan</button>
</form>