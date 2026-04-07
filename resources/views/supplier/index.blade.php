<h1>Daftar Supplier Rafita Cake</h1>
<table border="1">
    <thead>
        <tr>
            <th>Nama Supplier</th>
            <th>No Telepon</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suppliers as $s)
        <tr>
            <td>{{ $s->nama_supplier }}</td>
            <td>{{ $s->no_telepon }}</td>
            <td>{{ $s->alamat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>