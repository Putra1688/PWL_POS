<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'barang_id' => 3,
                'user_id' => 2,
                'stok_tanggal' => '2025-04-01',
                'stok_jumlah' => 120
            ],
            [
                'supplier_id' => 2,
                'barang_id' => 5,
                'user_id' => 1,
                'stok_tanggal' => '2025-03-29',
                'stok_jumlah' => 80
            ],
            [
                'supplier_id' => 1,
                'barang_id' => 7,
                'user_id' => 3,
                'stok_tanggal' => '2025-04-04',
                'stok_jumlah' => 150
            ],
            [
                'supplier_id' => 3,
                'barang_id' => 2,
                'user_id' => 2,
                'stok_tanggal' => '2025-04-03',
                'stok_jumlah' => 60
            ],
            [
                'supplier_id' => 1,
                'barang_id' => 4,
                'user_id' => 5,
                'stok_tanggal' => '2025-03-31',
                'stok_jumlah' => 200
            ],
            [
                'supplier_id' => 2,
                'barang_id' => 6,
                'user_id' => 4,
                'stok_tanggal' => '2025-04-05',
                'stok_jumlah' => 75
            ],
            [
                'supplier_id' => 3,
                'barang_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => '2025-04-06',
                'stok_jumlah' => 90
            ],
            [
                'supplier_id' => 2,
                'barang_id' => 8,
                'user_id' => 5,
                'stok_tanggal' => '2025-03-28',
                'stok_jumlah' => 130
            ],
            [
                'supplier_id' => 1,
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => '2025-04-07',
                'stok_jumlah' => 100
            ],
            [
                'supplier_id' => 3,
                'barang_id' => 10,
                'user_id' => 2,
                'stok_tanggal' => '2025-04-02',
                'stok_jumlah' => 50
            ],
            [
                'supplier_id' => 2,
                'barang_id' => 2,
                'user_id' => 1,
                'stok_tanggal' => '2025-04-08',
                'stok_jumlah' => 110
            ],
            [
                'supplier_id' => 3,
                'barang_id' => 3,
                'user_id' => 4,
                'stok_tanggal' => '2025-03-30',
                'stok_jumlah' => 170
            ],
            [
                'supplier_id' => 1,
                'barang_id' => 5,
                'user_id' => 2,
                'stok_tanggal' => '2025-04-09',
                'stok_jumlah' => 140
            ],
            [
                'supplier_id' => 2,
                'barang_id' => 7,
                'user_id' => 3,
                'stok_tanggal' => '2025-03-27',
                'stok_jumlah' => 95
            ],
            [
                'supplier_id' => 3,
                'barang_id' => 6,
                'user_id' => 1,
                'stok_tanggal' => '2025-04-01',
                'stok_jumlah' => 115
            ],
        ];
        DB::table('t_stok')->insert($data);
    }
}
