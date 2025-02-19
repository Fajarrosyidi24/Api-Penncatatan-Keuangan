<?php

use Carbon\Carbon;
use App\Models\Notif;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Notifications\CatatanKeuangan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $transaksis = Transaksi::where('user_id', $user->id)->get();
    $totalPemasukan = $transaksis->where('tipe', 'pemasukan')->sum('jumlah');
    $totalPengeluaran = $transaksis->where('tipe', 'pengeluaran')->sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;
    $transaksiPertama = Transaksi::where('user_id', $user->id)
        ->whereNotNull('tanggal')
        ->orderBy('tanggal', 'asc')
        ->first();

    if ($transaksiPertama) {
        $tanggalAwal = Carbon::parse($transaksiPertama->tanggal);
        $tanggalSekarang = Carbon::now();
        $tanggalTransaksi = Transaksi::where('user_id', $user->id)
            ->whereNotNull('tanggal')
            ->pluck('tanggal')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        for ($date = $tanggalAwal; $date->lte($tanggalSekarang); $date->addDay()) {
            if (!in_array($date->toDateString(), $tanggalTransaksi)) {
                $notif = Notif::firstOrNew([
                    'tanggal' => $date->toDateString(),
                    'user_id' => $user->id
                ]);

                if (!$notif->exists) {
                    $notif->terkirim = false;
                    $notif->save();
                }

                if (!$notif->terkirim) {
                    $user->notify(new CatatanKeuangan($notif->tanggal));
                    $notif->update(['terkirim' => true]);
                }
            }
        }
    }

    $notifs = Notif::where('user_id', $user->id)->where('terkirim', false)->get();

    return view('dashboard', compact(
        'transaksis',
        'totalPemasukan',
        'totalPengeluaran',
        'saldo',
        'notifs'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('transaksi')->group(function () {
        Route::get('pemasukan', [TransaksiController::class, 'index'])->name('pemasukan');
        Route::get('pengeluaran', [TransaksiController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('saldo', [TransaksiController::class, 'saldo'])->name('saldo');
        Route::get('create', [TransaksiController::class, 'create'])->name('create');
        Route::post('create', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::post('/transaksi/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi//delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });

    Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export', [TransaksiController::class, 'export'])->name('laporan.export');


});

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'login'])->name('login_admin');
    Route::post('login/post', [AdminController::class, 'store'])->name('admin.login');
    Route::middleware('is_admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
        Route::get('/admin/export-excel', [AdminController::class, 'exportExcel'])->name('admin.export-excel');
        Route::get('/admin/management-user', [AdminController::class, 'managementuser'])->name('admin.management');
        Route::get('/admin/detail/transaksi_user/{id}', [AdminController::class, 'DetailTransaksiUser'])->name('admin.detail_transaksi');
        Route::get('/export-transaksi/{userId}', [AdminController::class, 'exportExcelUser'])->name('export.transaksi.user');
    });
});

Route::get('/transaksi/konfirmasi/{id}', [TransaksiController::class, 'konfirmasi'])
    ->middleware('auth')
    ->name('transaksi.konfirmasi');


require __DIR__.'/auth.php';
