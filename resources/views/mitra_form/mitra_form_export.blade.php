<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Message</th>
            <th>Tanggal Submit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mitraForm as $mitra)
            <tr>
                <td>{{ $mitra->name }}</td>
                <td>{{ $mitra->phone }}</td>
                <td>{{ $mitra->email }}</td>
                <td>{{ $mitra->message }}</td>
                <td>{{ $mitra->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
