<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDataTransaksiController;
use App\Http\Controllers\Api\ApiLaporanController;
use App\Http\Controllers\Api\ApiNotification;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/data-transaction', [ApiDataTransaksiController::class, 'user_melihat_data_transaksi']);
    Route::get('/admin/data-transaction', [ApiDataTransaksiController::class, 'admin_melihat_data_transaksi_semua_user']);

    Route::get('/report/monthly', [ApiLaporanController::class, 'monthlyReport']);
    Route::get('/report/yearly', [ApiLaporanController::class, 'yearlyReport']);

    Route::get('/laporan/export', [ApiLaporanController::class, 'export']);

    Route::get('/notifications', [ApiNotification::class, 'getNotifications']);
});

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('admin/login', [ApiAuthController::class, 'adminLogin']);
