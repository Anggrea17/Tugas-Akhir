<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpasi extends Model
{
    use HasFactory;

    Protected $table = 'mpasi'; // penting karena defaultnya Laravel pakai "mpasis"
    
    protected $fillable = [
        'nama_menu',
        'porsi',
        'bahan',
        'langkah',
        'min_umur',
        'max_umur',
        'energi',
        'karbohidrat',
        'protein',
        'lemak',
        'zat_besi',
        'kategori_id',
        'gambar',
    ];
    public function kategori()
{
    return $this->belongsTo(KategoriMpasi::class, 'kategori_id');
}
public function bahans()
{
    return $this->hasMany(BahanMpasi::class);
}

public function langkahs()
{
    return $this->hasMany(LangkahMpasi::class);
}
public function getGambarUrlAttribute()
{
    return $this->gambar
        ? asset('uploads/gambar_mpasi/' . $this->gambar)
        : asset('images/placeholder.png');
}

  protected function formatDecimal($value)
    {
        // Hilangin nol di belakang koma, tapi tetap ada kalau desimalnya > 0
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    public function getEnergiAttribute($value)
    {
        return $this->formatDecimal($value);
    }

    public function getProteinAttribute($value)
    {
        return $this->formatDecimal($value);
    }

    public function getLemakAttribute($value)
    {
        return $this->formatDecimal($value);
    }

    public function getKarbohidratAttribute($value)
    {
        return $this->formatDecimal($value);
    }

    public function getZatBesiAttribute($value)
    {
        return $this->formatDecimal($value);
    }

}
