<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkembanganBayi extends Model
{
    use HasFactory;
  protected $table = 'perkembangan_bayi';
    protected $fillable = [
        'bayi_id',
        'umur_bulan',
        'berat',
        'tinggi',
        'tanggal_catat',
    ];

 public function bayi()
{
    return $this->belongsTo(Bayi::class, 'bayi_id');
}

    
}

