<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LangkahMpasi extends Model
{
    use HasFactory;
     protected $table = 'langkah_mpasi';
    protected $fillable = ['mpasi_id', 'urutan', 'langkah'];

    public function mpasi()
    {
        return $this->belongsTo(Mpasi::class);
    }
}
