<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMpasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_mpasi';
    protected $fillable = ['nama_kategori'];

    public function mpasi()
    {
        return $this->hasMany(Mpasi::class, 'kategori_id');
    }
}
