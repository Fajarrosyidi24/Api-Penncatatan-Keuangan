<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransaksiExportUser implements FromCollection, WithHeadings
{

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Transaksi::where('user_id', $this->userId)
            ->with('user')
            ->get()
            ->map(function ($t, $index) {
                return [
                    'No' => $index + 1,
                    'Nama User' => $t->user->name,
                    'Tanggal' => $t->tanggal,
                    'Tipe' => ucfirst($t->tipe),
                    'Jumlah' => $t->jumlah,
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Nama User', 'Tanggal', 'Tipe', 'Jumlah'];
    }
}