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
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Ahmad',     'penjualan_kode' => 'PJL-001', 'penjualan_tanggal' => '2025-04-01'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Budi',      'penjualan_kode' => 'PJL-002', 'penjualan_tanggal' => '2025-04-01'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Citra',     'penjualan_kode' => 'PJL-003', 'penjualan_tanggal' => '2025-04-02'],
            ['penjualan_id' => 4, 'user_id' => 1, 'pembeli' => 'Dinda',     'penjualan_kode' => 'PJL-004', 'penjualan_tanggal' => '2025-04-02'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'Eko',       'penjualan_kode' => 'PJL-005', 'penjualan_tanggal' => '2025-04-03'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Fajar',     'penjualan_kode' => 'PJL-006', 'penjualan_tanggal' => '2025-04-03'],
            ['penjualan_id' => 7, 'user_id' => 2, 'pembeli' => 'Gina',      'penjualan_kode' => 'PJL-007', 'penjualan_tanggal' => '2025-04-04'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'Herman',    'penjualan_kode' => 'PJL-008', 'penjualan_tanggal' => '2025-04-04'],
            ['penjualan_id' => 9, 'user_id' => 1, 'pembeli' => 'Indah',     'penjualan_kode' => 'PJL-009', 'penjualan_tanggal' => '2025-04-05'],
            ['penjualan_id' => 10, 'user_id' => 2, 'pembeli' => 'Joko',      'penjualan_kode' => 'PJL-010', 'penjualan_tanggal' => '2025-04-05'],
            ['penjualan_id' => 11, 'user_id' => 1, 'pembeli' => 'Karina',    'penjualan_kode' => 'PJL-011', 'penjualan_tanggal' => '2025-04-06'],
            ['penjualan_id' => 12, 'user_id' => 2, 'pembeli' => 'Lukman',    'penjualan_kode' => 'PJL-012', 'penjualan_tanggal' => '2025-04-06'],
            ['penjualan_id' => 13, 'user_id' => 3, 'pembeli' => 'Mega',      'penjualan_kode' => 'PJL-013', 'penjualan_tanggal' => '2025-04-07'],
            ['penjualan_id' => 14, 'user_id' => 2, 'pembeli' => 'Nina',      'penjualan_kode' => 'PJL-014', 'penjualan_tanggal' => '2025-04-07'],
            ['penjualan_id' => 15, 'user_id' => 1, 'pembeli' => 'Oscar',     'penjualan_kode' => 'PJL-015', 'penjualan_tanggal' => '2025-04-08'],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
