<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class P_detail_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['detail_id'=> 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 10000, 'jumlah' => 2],
            ['detail_id'=> 2, 'penjualan_id' => 1, 'barang_id' => 2, 'harga' => 15000, 'jumlah' => 1],
            ['detail_id'=> 3, 'penjualan_id' => 2, 'barang_id' => 3, 'harga' => 20000, 'jumlah' => 3],
            ['detail_id'=> 4, 'penjualan_id' => 2, 'barang_id' => 4, 'harga' => 18000, 'jumlah' => 2],
            ['detail_id'=> 5, 'penjualan_id' => 3, 'barang_id' => 5, 'harga' => 25000, 'jumlah' => 1],
            ['detail_id'=> 6, 'penjualan_id' => 3, 'barang_id' => 6, 'harga' => 30000, 'jumlah' => 2],
            ['detail_id'=> 7, 'penjualan_id' => 4, 'barang_id' => 7, 'harga' => 22000, 'jumlah' => 1],
            ['detail_id'=> 8, 'penjualan_id' => 4, 'barang_id' => 8, 'harga' => 17000, 'jumlah' => 3],
            ['detail_id'=> 9, 'penjualan_id' => 5, 'barang_id' => 9, 'harga' => 19000, 'jumlah' => 2],
            ['detail_id'=> 10, 'penjualan_id' => 5, 'barang_id' => 10, 'harga' => 21000, 'jumlah' => 1],
            ['detail_id'=> 11, 'penjualan_id' => 1, 'barang_id' => 3, 'harga' => 20000, 'jumlah' => 1],
            ['detail_id'=> 12, 'penjualan_id' => 2, 'barang_id' => 7, 'harga' => 22000, 'jumlah' => 2],
            ['detail_id'=> 13, 'penjualan_id' => 3, 'barang_id' => 1, 'harga' => 10000, 'jumlah' => 1],
            ['detail_id'=> 14, 'penjualan_id' => 4, 'barang_id' => 4, 'harga' => 18000, 'jumlah' => 2],
            ['detail_id'=> 15, 'penjualan_id' => 5, 'barang_id' => 2, 'harga' => 15000, 'jumlah' => 3],
        ];
        DB::table('t_penjualan_detail')->insert($data);
    }
}
