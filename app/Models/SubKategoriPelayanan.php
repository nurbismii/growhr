<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategoriPelayanan extends Model
{
    use HasFactory;

    protected $table = 'sub_kategori_pelayanan';
    protected $guarded = [];

    public function kategoriPelayanan()
    {
        return $this->hasOne(KategoriPelayanan::class, 'id', 'kategori_pelayanan_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPelayanan::class, 'kategori_pelayanan_id');
    }
}
