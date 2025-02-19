@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Transaksi</h2>

    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tipe">Tipe Transaksi</label>
            <select name="tipe" id="tipe" class="form-control" required>
                <option value="pemasukan" {{ $transaksi->tipe == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $transaksi->tipe == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $transaksi->jumlah }}" required min="1000">
        </div>

        <div class="form-group mt-3">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $transaksi->tanggal }}" required>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>
</div>
@endsection
