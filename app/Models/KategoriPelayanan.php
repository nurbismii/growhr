<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelayanan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelayanan';
    protected $guarded = [];

    public function subKategoris()
    {
        return $this->hasMany(SubKategoriPelayanan::class, 'kategori_pelayanan_id');
    }

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'id', 'divisi_id');
    }
}
