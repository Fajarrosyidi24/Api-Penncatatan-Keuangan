<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tanggal', 'tipe', 'jumlah', 'dikonfirmasi'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function perluKonfirmasi()
    {
        return $this->jumlah >= config('keuangan.transaksi_besar_limit') && !$this->dikonfirmasi;
    }


    public static function store($request)
    {
        $transaksi = self::create([
            'tipe' => $request->input('tipe'),
            'jumlah' => $request->input('jumlah'),
            'tanggal' => $request->input('tanggal'),
            'user_id' => Auth::user()->id,
            'dikonfirmasi' => false, // Default belum dikonfirmasi
        ]);

        return $transaksi;
    }

    // Validasi sebelum transaksi dibuat
    public static function boot()
    {
        parent::boot();
        static::creating(function ($transaksi) {
            $user = $transaksi->user;
            $saldoSaatIni = $user->transaksi()->where('tipe', 'pemasukan')->sum('jumlah') - $user->transaksi()->where('tipe', 'pengeluaran')->sum('jumlah');

            // Jika transaksi adalah pengeluaran, cek saldo
            if ($transaksi->tipe === 'pengeluaran' && $transaksi->jumlah > $saldoSaatIni) {
                throw new \Exception("Saldo tidak mencukupi untuk melakukan transaksi ini!");
            }

            // Validasi tanggal harus antara 2010-01-01 sampai hari ini
            $tanggal = Carbon::parse($transaksi->tanggal);
            if ($tanggal < Carbon::create(2010, 1, 1) || $tanggal > now()) {
                throw new \Exception("Tanggal transaksi harus antara Januari 2010 hingga saat ini!");
            }
        });
    }
}
