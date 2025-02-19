<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ApiLaporanController extends Controller
{
    public function monthlyReport(Request $request): JsonResponse
    {
        $user = Auth::user();
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        $transactions = Transaksi::where('user_id', $user->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        $totalIncome = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalExpense = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoAkhir = $totalIncome - $totalExpense;

        return response()->json([
            'status' => 'success',
            'message' => "Laporan keuangan bulan $month tahun $year.",
            'data' => [
                'bulan' => $month,
                'tahun' => $year,
                'total_pemasukan' => $totalIncome,
                'total_pengeluaran' => $totalExpense,
                'saldo_akhir' => $saldoAkhir,
                'transaksi' => $transactions
            ]
        ]);
    }

    public function yearlyReport(Request $request): JsonResponse
    {
        $user = Auth::user();
        $year = $request->query('year', Carbon::now()->year);

        $transactions = Transaksi::where('user_id', $user->id)
            ->whereYear('tanggal', $year)
            ->get();

        $totalIncome = $transactions->where('tipe', 'pemasukan')->sum('jumlah');
        $totalExpense = $transactions->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoAkhir = $totalIncome - $totalExpense;

        return response()->json([
            'status' => 'success',
            'message' => "Laporan keuangan tahun $year.",
            'data' => [
                'tahun' => $year,
                'total_pemasukan' => $totalIncome,
                'total_pengeluaran' => $totalExpense,
                'saldo_akhir' => $saldoAkhir,
                'transaksi' => $transactions
            ]
        ]);
    }


    public function export(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $periode = $request->query('periode');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        if (!$tahun) {
            return response()->json(['status' => 'error', 'message' => 'Tahun wajib diisi!'], 400);
        }
        if ($periode === 'bulanan' && $bulan) {
            $namaFile = "Laporan_Bulanan_{$tahun}_{$bulan}.xlsx";
        } else {
            $namaFile = "Laporan_Tahunan_{$tahun}.xlsx";
        }

        $filePath = "exports/$namaFile";
        Excel::store(new LaporanExport($periode, $bulan, $tahun, $userId), $filePath, 'public');
        $url = url("storage/$filePath");
        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil dibuat.',
            'download_url' => $url
        ]);
    }
}
