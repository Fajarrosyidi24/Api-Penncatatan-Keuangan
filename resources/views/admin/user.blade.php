@extends('layouts.AdminLayout')
@section('title')
    <title>Management Admin - Pencatatan Keuangan</title>
@endsection

@section('content')
<div class="col-xl-12 stretch-card grid-margin">
    <div class="card">
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="row mt-4">
            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                <h5 class="mb-3">Data User</h5>
                {{-- <a href="{{ route('admin.export-excel', ['tahun' => $tahun]) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a> --}}
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user as $index => $u)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $u->name }}</td>
                            <td>
                                <a href="{{route('admin.detail_transaksi', ['id' => $u->id])}}" class="btn btn-primary">Detail Transaksi</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada user yang terdaftar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
</div>
@endsection