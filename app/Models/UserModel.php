<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Iluminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    // model & eloquent orm
    protected $fillable = ['level_id', 'username', 'nama', 'password'];

    protected $hidden = ['password'];
    protected $cast = ['password' => 'hashed'];


    public function level() {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
