@extends('layouts.app')
@section('content')
    @if (Route::is('dashboard'))
        <div class="container mt-4">
            <h2>Selamat Datang, {{ auth()->user()->name }}</h2>
            <p>Kelola keuangan Anda dengan lebih baik.</p>
            <div class="row">
                <a href="{{ route('pemasukan') }}" class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pemasukan</h5>
                            <p class="card-text">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('pengeluaran') }}" class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pengeluaran</h5>
                            <p class="card-text">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('saldo') }}" class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Saldo</h5>
                            <p class="card-text">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        @include('TableTransaksi.Transaksi')
    @else
        @include('TableTransaksi.Transaksi')
    @endif
@endsection
