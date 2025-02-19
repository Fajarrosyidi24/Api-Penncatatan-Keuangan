@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <!-- Form Filter Laporan -->
    <form method="GET" action="{{ route('laporan') }}">
        <div class="form-group">
            <label for="periode">Pilih Periode</label>
            <select name="periode" id="periode" class="form-control" onchange="this.form.submit()">
                <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="tahunan" {{ $periode == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>

        @if ($periode == 'bulanan')
        <div class="form-group mt-3">
            <label for="bulan">Bulan</label>
            <select name="bulan" id="bulan" class="form-control" onchange="this.form.submit()">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                @endfor
            </select>
        </div>
        @endif

        <div class="form-group mt-3">
            <label for="tahun">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahun }}" required>
        </div>
    </form>

    <hr>

    <div class="form-group mb-3 mt-4">
        <a href="{{ route('laporan.export', ['format' => 'excel', 'periode' => $periode, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-primary">Export to Excel</a>
    </div>

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
@endsection
