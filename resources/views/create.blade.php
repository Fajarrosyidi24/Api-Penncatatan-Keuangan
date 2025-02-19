@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Tambah Transaksi</h2>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
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
            <div class="form-group">
                <label for="tipe">Tipe Transaksi</label>
                <select name="tipe" id="tipe" class="form-control" required>
                    <option value="">Pilih Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <div class="form-group mt-3">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1000"
                    placeholder="Minimal Rp 1.000">
            </div>

            <div class="form-group mt-3">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
@endsection
