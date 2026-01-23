<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbUReferensi extends Model
{
    use HasFactory;

    protected $table = 'bb_u_referensi';

    protected $fillable = [
        'gender',
        'usia_bulan',
        'bb_min',
        'bb_max',
    ];

    public $timestamps = true;
}
