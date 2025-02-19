<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Trait\APiResponsTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\RegisteredUserController;

class ApiAuthController extends RegisteredUserController
{
    use APiResponsTrait;
    public function register(Request $request): JsonResponse
    {
        $response = parent::store($request);
        $user = Auth::user();
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'Registrasi berhasil!', 201);
        }
        return $this->errorResponse('Registrasi gagal.', 400);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('Email atau password salah.', 401);
        }
        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Login berhasil!', 200);
    }

    public function adminLogin(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return $this->errorResponse('Email atau password salah.', 401);
        }

        $user = Auth::guard('admin')->user();
        $token = $user->createToken('api_token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Login berhasil!', 200);
    }
}
