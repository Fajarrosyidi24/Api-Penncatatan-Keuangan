<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Notif;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CatatanKeuangan;

class ApiNotification extends Controller
{
    public function getNotifications() {
        $user = Auth::user();
        $transaksis = Transaksi::where('user_id', $user->id)->get();

        $tanggalTransaksi = $transaksis->pluck('tanggal')->map(fn ($date) => Carbon::parse($date)->toDateString())->toArray();
        
        $transaksiPertama = $transaksis->whereNotNull('tanggal')->sortBy('tanggal')->first();

        if ($transaksiPertama) {
            $tanggalAwal = Carbon::parse($transaksiPertama->tanggal);
            $tanggalSekarang = Carbon::now();
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

        $notifs = Notif::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi transaksi yang belum diisi',
            'data' => $notifs
        ], 200);
    }
}
