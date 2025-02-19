<div class="container mt-4">
    <h2>Semua Transaksi</h2>
    
    @if (Route::is('dashboard'))
        <div class="mt-4">
            <a href="{{route('create')}}" class="btn btn-success mb-3">Tambah Transaksi</a>
        </div>

        @if (!empty($tanggalTerlewat))
    <div class="alert alert-warning">
        <strong>Perhatian!</strong> Berikut adalah tanggal yang belum memiliki transaksi:
        <ul>
            @foreach ($tanggalTerlewat as $tanggal)
                <li>{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @endif


    @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $transaksi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaksi->tipe }}</td>
                    <td>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->tanggal }}</td>
                    <td>
                        <a href="{{ route('transaksi.edit', ['id' => $transaksi->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!Route::is('dashboard'))
        <div class="mt-4">
            <table class="table table-bordered">
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                        <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
