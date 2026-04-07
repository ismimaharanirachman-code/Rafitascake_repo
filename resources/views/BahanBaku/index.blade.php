<h1>Data Bahan Baku</h1>

<a href="{{ route('bahan-baku.create') }}">Tambah</a>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Stok</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Aksi</th>
    </tr>

    @foreach($data as $d)
    <tr>
        <td>{{ $d->nama_bahan }}</td>
        <td>{{ $d->stok }}</td>
        <td>{{ $d->satuan }}</td>
        <td>{{ $d->harga }}</td>
        <td>
            <a href="{{ route('bahan-baku.edit', $d->id) }}">Edit</a>
            <form action="{{ route('bahan-baku.destroy', $d->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>