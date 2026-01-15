<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BahanMpasi extends Model
{
    use HasFactory;
     protected $table = 'bahan_mpasi';
    protected $fillable = ['mpasi_id', 'bahan', 'takaran'];

    public function mpasi()
    {
        return $this->belongsTo(Mpasi::class);
    }
}
