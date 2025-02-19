<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Transaksi::with('user')
            ->whereYear('tanggal', $this->tahun)
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama User', 'Tanggal', 'Tipe', 'Jumlah'];
    }

    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->user->name,
            $transaksi->tanggal,
            ucfirst($transaksi->tipe),
            number_format($transaksi->jumlah, 0, ',', '.'),
        ];
    }
}

