<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection
{

    protected $periode;
    protected $bulan;
    protected $tahun;
    protected $userId;

    public function __construct($periode, $bulan, $tahun, $userId)
    {
        $this->periode = $periode;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transaksi::where('user_id', $this->userId)->whereYear('tanggal', $this->tahun);

        if ($this->periode === 'bulanan' && $this->bulan) {
            $query->whereMonth('tanggal', $this->bulan);
        }

        return $query->get();
    }
}
