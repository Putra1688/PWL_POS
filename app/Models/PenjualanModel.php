<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanModel extends Model
{
    use HasFactory;

    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';

    protected $fillable = ['penjualan_id', 'user_id', 'barang_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal', 'created_at', 'updated_at'];

    public function user(): BelongsTo{
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id', 'penjualan_id'); 
    }
}