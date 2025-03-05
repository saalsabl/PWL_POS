<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Aisyah',
                'penjualan_kode' => 1,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Salsa',
                'penjualan_kode' => 2,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Sarah',
                'penjualan_kode' => 3,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Diana',
                'penjualan_kode' => 4,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Sandrina',
                'penjualan_kode' => 5,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Marsya',
                'penjualan_kode' => 6,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Putri',
                'penjualan_kode' => 7,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Dinda',
                'penjualan_kode' => 8,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Vidi',
                'penjualan_kode' => 9,
                'penjualan_tanggal' => '2025-03-04',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Cakra',
                'penjualan_kode' => 10,
                'penjualan_tanggal' => '2025-03-04',
            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}