<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'isi',
        'gambar',
        'kategori_id',
        'tanggal_post',
        'sumber',
    ];  // relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Menggunakan model event untuk membuat slug secara otomatis dan memastikan unik
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            $originalSlug = Str::slug($artikel->judul);
            $slug = $originalSlug;
            $counter = 1;

            // Periksa apakah slug sudah ada, jika iya, tambahkan nomor
            while (static::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $artikel->slug = $slug;
        });
    }
//relasi gambar
  public function getGambarUrlAttribute()
    {
        // Kalau $this->gambar sudah ada prefix 'uploads/...', langsung pakai
        if ($this->gambar) {
            if (str_starts_with($this->gambar, 'uploads/')) {
                return asset($this->gambar);
            }
            // kalau cuma nama file saja
            return asset('uploads/gambar_artikel/' . $this->gambar);
            
        }

        // default placeholder kalau belum ada gambar
        return asset('images/placeholder.png');
    }

    //relasi kategori
    public function kategori()
{
    return $this->belongsTo(KategoriArtikel::class, 'kategori_id');
}

}