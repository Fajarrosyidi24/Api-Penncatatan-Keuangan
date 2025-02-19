@extends('layouts.AdminLayout')
@section('title')
    <title>Dashboard Admin</title>
@endsection

@section('content')
    <div class="page-header flex-wrap">
        <h3 class="mb-0"> Hi, welcome back! <span class="pl-0 h6 pl-sm-2 text-muted d-inline-block">Your web analytics
                dashboard template.</span>
        </h3>
    </div>
    <div class="col-xl-12 stretch-card grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-7">
                        <h5>Keuangan Pengguna</h5>
                        <p class="text-muted"> Ringkasan keuangan semua user
                            <a class="text-muted font-weight-medium pl-2" href="#"><u>Lihat Detail</u></a>
                        </p>
                    </div>
                </div>

                <!-- Dropdown Filter Tahun -->
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <form method="GET" action="{{ route('admin.dashboard') }}">
                            <label for="tahun">Pilih Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control" onchange="this.form.submit()">
                                @foreach ($tahun_list as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="card mb-3 mb-sm-0">
                            <div class="card-body py-3 px-4">
                                <p class="m-0 survey-head">Total Pemasukan</p>
                                <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                    <div>
                                        <h3 class="m-0 survey-value">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
                                        </h3>
                                        <p class="text-success m-0">Pemasukan tahun ini</p>
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
                                        <h3 class="m-0 survey-value">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}
                                        </h3>
                                        <p class="text-danger m-0">Pengeluaran tahun ini</p>
                                    </div>
                                    <div id="expenseChart" class="flot-chart"></div>
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
                                <h5 class="mb-3">Detail Transaksi Tahun {{ $tahun }}</h5>
                                <a href="{{ route('admin.export-excel', ['tahun' => $tahun]) }}" class="btn btn-success">
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
                        <p class="text-muted mb-0"> Data keuangan di atas mencerminkan ringkasan keuangan dari semua user.
                            <b>Pelajari lebih lanjut</b>
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <p class="mb-0 text-muted">Total Transaksi</p>
                        <h5 class="d-inline-block survey-value mb-0"> {{ number_format($total_transaksi, 0, ',', '.') }}
                        </h5>
                        <p class="d-inline-block text-danger mb-0"> tahun ini </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection