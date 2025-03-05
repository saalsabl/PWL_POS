<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 1,
                'barang_nama' => 'Indomie Goreng',
                'harga_beli' => 3000,
                'harga_jual' => 3500,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 2,
                'barang_nama' => 'Beng Beng',
                'harga_beli' => 1500,
                'harga_jual' => 2000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 3,
                'barang_nama' => 'Sunscreen',
                'harga_beli' => 30000,
                'harga_jual' => 35000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 5,
                'barang_kode' => 4,
                'barang_nama' => 'Paratusin',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 5,
                'barang_kode' => 5,
                'barang_nama' => 'Baterai',
                'harga_beli' => 2500,
                'harga_jual' => 3000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 5,
                'barang_kode' => 6,
                'barang_nama' => 'Meja Lipat',
                'harga_beli' => 40000,
                'harga_jual' => 50000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 5,
                'barang_kode' => 7,
                'barang_nama' => 'Payung',
                'harga_beli' => 20000,
                'harga_jual' => 25000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 2,
                'barang_kode' => 8,
                'barang_nama' => 'Lip Tint',
                'harga_beli' => 20000,
                'harga_jual' => 22000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 1,
                'barang_kode' => 9,
                'barang_nama' => 'Sosis',
                'harga_beli' => 9000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 1,
                'barang_kode' => 10,
                'barang_nama' => 'Yogurt',
                'harga_beli' => 9000,
                'harga_jual' => 10000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}