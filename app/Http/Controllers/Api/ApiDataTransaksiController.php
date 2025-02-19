<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Trait\APiResponsTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiResource;
use Illuminate\Support\Facades\Auth;

class ApiDataTransaksiController extends Controller
{
    use APiResponsTrait;

    public function user_melihat_data_transaksi(){
        $user = Auth::user();
        $transactions = Transaksi::where('user_id', $user->id)->get();
        return $this->successResponse($transactions, 'Daftar transaksi Anda.', 200);
    }

    public function admin_melihat_data_transaksi_semua_user(): JsonResponse
    {
        $transactions = Transaksi::with('user')->latest()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar semua transaksi pengguna.',
            'data' => TransaksiResource::collection($transactions)
        ]);
    }
}

