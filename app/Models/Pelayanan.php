<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;

    protected $table = 'pelayanan';
    protected $guarded = [];

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'id', 'divisi_id');
    }

    public function kategoriPelayanan()
    {
        return $this->hasOne(KategoriPelayanan::class, 'id', 'kategori_pelayanan_id');
    }

    public function subKategoriPelayanan()
    {
        return $this->hasOne(SubKategoriPelayanan::class, 'id', 'sub_kategori_pelayanan_id');
    }

    public function pic()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
