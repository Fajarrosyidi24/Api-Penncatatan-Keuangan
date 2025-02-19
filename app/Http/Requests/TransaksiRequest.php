<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userID = Auth::user()->id;
        $user = User::where('id', $userID)->first();
        $totalPemasukan = $user->transaksi()->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $user->transaksi()->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoSaatIni = $totalPemasukan - $totalPengeluaran;

        return [
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => [
                'required',
                'numeric',
                'min:1000',
                function ($attribute, $value, $fail) use ($saldoSaatIni) {
                    if (request()->input('tipe') === 'pengeluaran' && $value > $saldoSaatIni) {
                        $fail('Saldo tidak mencukupi untuk melakukan transaksi ini.');
                    }
                }
            ],
            'tanggal' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'tipe.required' => 'Tipe transaksi harus dipilih.',
            'tipe.in' => 'Tipe transaksi harus salah satu dari pemasukan atau pengeluaran.',
            'jumlah.required' => 'Jumlah transaksi harus diisi.',
            'jumlah.numeric' => 'Jumlah transaksi harus berupa angka.',
            'jumlah.min' => 'Jumlah transaksi minimal Rp 1.000.',
            'tanggal.required' => 'Tanggal transaksi harus diisi.',
            'tanggal.date' => 'Tanggal transaksi harus berupa tanggal yang valid.',
        ];
    }
}
