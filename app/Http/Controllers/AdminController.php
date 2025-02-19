<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\TransaksiExport;
use App\Exports\TransaksiExportUser;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function store(Request $request)
    {
        $check = $request->all();
        if (Auth::guard('admin')->attempt(['email' => $check['email'], 'password' =>  $check['password']])) {
            return redirect()->route('admin.dashboard')->with('success', 'admin login succesfully');
        } else {
            return back()->with('gagal', 'email atau password salah');
        }
    }

    public function dashboard(Request $request)
    {
        $tahun_terkecil = Transaksi::min('tanggal');
        $tahun_terbesar = Transaksi::max('tanggal');

        $tahun_awal = $tahun_terkecil ? Carbon::parse($tahun_terkecil)->format('Y') : Carbon::now()->format('Y');
        $tahun_akhir = $tahun_terbesar ? Carbon::parse($tahun_terbesar)->format('Y') : Carbon::now()->format('Y');

        $tahun = $request->input('tahun', $tahun_akhir);

        $transaksi = Transaksi::whereYear('tanggal', $tahun)->with('user')->get();
        $total_pemasukan = $transaksi->where('tipe', 'pemasukan')->sum('jumlah');
        $total_pengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $total_pemasukan - $total_pengeluaran;
        $total_transaksi = $transaksi->count();
        $tahun_list = range($tahun_awal, $tahun_akhir);

        return view('admin.dashboard', compact('total_pemasukan', 'total_pengeluaran', 'saldo', 'total_transaksi', 'tahun', 'tahun_list', 'transaksi'));
    }


    public function AdminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login_admin')->with('success', 'admin logout succesfully');
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        return Excel::download(new TransaksiExport($tahun), "transaksi_$tahun.xlsx");
    }

    public function managementuser()
    {
        $user = User::all();
        return view('admin.user', compact('user'));
    }

    public function DetailTransaksiUser($id)
    {
        $user = User::where('id', $id)->first();
        $transaksi = Transaksi::where('user_id', $id)->get();
        $totalPemasukan = $transaksi->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        return view('admin.detail_transaksi_user', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'user'));
    }

    public function exportExcelUser($userId)
    {
        $user = User::where('id', $userId)->first();
        $name = $user->name;
        return Excel::download(new TransaksiExportUser($userId), 'transaksi_user_' . $name . '.xlsx');
    }
   
}
