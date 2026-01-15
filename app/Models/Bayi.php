<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayi extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id',
    'nama_bayi',
    'tanggal_lahir',
    'tinggi',
    'berat',
    'jenis_kelamin',
    'jenis_susu',
    'volume_asi',
    'kalori_per_porsi',
    'volume_per_porsi',
    'jumlah_porsi_per_hari', 
    



];

// relasi ke User
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
//relasi ke PerkembanganBayi
public function perkembanganBayi()
{
    return $this->hasMany(PerkembanganBayi::class, 'bayi_id');
}
//relasi perkembangan dengan pengurutan terbaru
public function perkembangan()
{
    return $this->hasMany(PerkembanganBayi::class)->orderBy('tanggal_pencatatan', 'desc');
}
// menghapus perkembangan bayi saat bayi dihapus
protected static function booted()
{
    static::deleting(function ($bayi) {
        $bayi->perkembanganBayi()->delete();
    });
}

}