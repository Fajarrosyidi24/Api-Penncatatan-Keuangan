<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\TransaksiRequest;
use App\Notifications\KonfirmasiTransaksi;

class TransaksiController extends Controller
{
    private function getTransaksiByTipe($tipe = null)
    {
        $user = Auth::user()->id;
        $query = Transaksi::where('user_id', $user);
        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $transaksis = $query->get();
        $total = $transaksis->sum('jumlah');
        if ($tipe === 'saldo') {
            $totalPemasukan = $this->getTransaksiByTipe('pemasukan')['total'];
            $totalPengeluaran = $this->getTransaksiByTipe('pengeluaran')['total'];
            $total = $totalPemasukan - $totalPengeluaran;
        }
        return compact('transaksis', 'total');
    }

    public function index()
    {
        $data = $this->getTransaksiByTipe('pemasukan');
        return view('dashboard', $data);
    }

    public function pengeluaran()
    {
        $data = $this->getTransaksiByTipe('pengeluaran');
        return view('dashboard', $data);
    }

    public function saldo()
    {
        $data = $this->getTransaksiByTipe('saldo');
        return view('dashboard', $data);
    }

    public function create()
    {
        return view('create');
    }

    public function konfirmasi($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('dikonfirmasi', false)
            ->firstOrFail();

        $transaksi->update(['dikonfirmasi' => true]);

        return redirect()->route('dashboard')->with('status', 'Transaksi telah dikonfirmasi.');
    }

    public function store(TransaksiRequest $request)
    {
        $userID = Auth::user()->id;
        $user = User::where('id', $userID)->first();
        $totalPemasukan = $user->transaksi()->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $user->transaksi()->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoSaatIni = $totalPemasukan - $totalPengeluaran;

        if ($request->input('tipe') === 'pengeluaran' && $request->input('jumlah') > $saldoSaatIni) {
            return redirect()->back()->withErrors(['jumlah' => 'Saldo tidak mencukupi untuk melakukan transaksi ini.']);
        }

        $transaksi = Transaksi::store($request);
        $limit = config('keuangan.transaksi_besar_limit', 5000000);
        if ($transaksi->jumlah >= $limit) {
            $user->notify(new KonfirmasiTransaksi($transaksi));
        }
        return redirect()->route('dashboard')->with('status', 'Anda berhasil menambahkan transaksi.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('edit', compact('transaksi'));
    }

    public function update(TransaksiRequest $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->validated());
        return redirect()->route('dashboard')->with('status', 'Transaksi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('dashboard')->with('status', 'Transaksi berhasil dihapus!');
    }

    public function laporan(Request $request)
    {
        $user = Auth::user()->id;
        $periode = $request->periode;
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        if ($periode == 'bulanan') {
            $transaksis = Transaksi::where('user_id', $user)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
        } elseif ($periode == 'tahunan') {
            $transaksis = Transaksi::where('user_id', $user)
                ->whereYear('tanggal', $tahun)
                ->get();
        } else {
            $transaksis = Transaksi::where('user_id', $user)->get();
        }
        $total = $transaksis->sum('jumlah');
        return view('Laporan.index', compact('transaksis', 'total', 'periode', 'bulan', 'tahun'));
    }

    public function export(Request $request)
    {
        $userId = Auth::user()->id;
        $periode = $request->query('periode');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        if ($periode === 'bulanan' && $bulan) {
            $namaFile = "Laporan_Bulanan_{$tahun}_{$bulan}.xlsx";
        } else {
            $namaFile = "Laporan_Tahunan_{$tahun}.xlsx";
        }

        return Excel::download(new LaporanExport($periode, $bulan, $tahun, $userId), $namaFile);
    }
}
