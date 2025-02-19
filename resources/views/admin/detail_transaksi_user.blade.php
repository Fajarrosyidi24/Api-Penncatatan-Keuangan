@extends('layouts.AdminLayout')
@section('title')
    <title>Detail Transaksi - Pencatatan Keuangan</title>
@endsection

@section('content')
    <div class="col-xl-12 stretch-card grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-7">
                        <h5>Keuangan Pengguna</h5>
                        <p class="text-muted"> Ringkasan keuangan {{$user->name}}
                            <a class="text-muted font-weight-medium pl-2" href="#"><u>Lihat Detail</u></a>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="card mb-3 mb-sm-0">
                            <div class="card-body py-3 px-4">
                                <p class="m-0 survey-head">Total Pemasukan</p>
                                <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                    <div>
                                        <h3 class="m-0 survey-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div id="incomeChart" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card mb-3 mb-sm-0">
                            <div class="card-body py-3 px-4">
                                <p class="m-0 survey-head">Total Pengeluaran</p>
                                <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                    <div>
                                        <h3 class="m-0 survey-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div id="expenseChart" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body py-3 px-4">
                                <p class="m-0 survey-head">Saldo Akhir</p>
                                <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                    <div>
                                        <h3 class="m-0 survey-value">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                                    </div>
                                    <div id="balanceChart" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABEL TRANSAKSI -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="row mt-4">
                            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                <h5 class="mb-3">Detail Transaksi</h5>
                                <a href="{{ route('export.transaksi.user', ['userId' => $user->id]) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transaksi as $index => $t)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $t->user->name }}</td>
                                            <td>{{ $t->tanggal }}</td>
                                            <td>
                                                <span class="badge {{ $t->tipe == 'pemasukan' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ ucfirst($t->tipe) }}
                                                </span>
                                            </td>
                                            <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada transaksi untuk tahun ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <p class="text-muted mb-0"> Data keuangan di atas mencerminkan ringkasan keuangan dari semua keuangan milik {{$user->name}}.
                            <b>Pelajari lebih lanjut</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection