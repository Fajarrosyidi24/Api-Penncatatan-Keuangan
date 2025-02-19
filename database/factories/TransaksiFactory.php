<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        if (!$user) {
            $user = User::factory()->create();
        }

        // Ambil total transaksi user
        $totalTransaksi = $user->transaksi()->count();
        $totalPemasukan = $user->transaksi()->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $user->transaksi()->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoSaatIni = $totalPemasukan - $totalPengeluaran;

        if ($totalTransaksi == 0 || $saldoSaatIni <= 0) {
            $tipe = 'pemasukan';
        } else {
            $tipe = $this->faker->randomElement(['pemasukan', 'pengeluaran', 'pengeluaran']);
        }
        if ($tipe === 'pemasukan') {
            $jumlah = $this->faker->numberBetween(100000, 1000000);
        } else {
            $jumlah = $this->faker->numberBetween(1000, $saldoSaatIni);
        }

        $start = Carbon::create(2025, 2, 11)->startOfDay(); // 5 Januari 2025, awal hari
        $end = Carbon::create(2025, 2, 19)->endOfDay(); // 10 Januari 2025, akhir hari

        $randomTanggal = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));


        return [
            'user_id' => 1,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'tanggal' => $randomTanggal
        ];
    }
}
