<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'sub_pekerjaan';
    protected $guarded = [];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'id');
    }

    public function pekerjaanHasOne()
    {
        return $this->hasOne(Pekerjaan::class, 'id', 'pekerjaan_id')->select(['id', 'deskripsi_pekerjaan']);
    }

    public function getStatusPekerjaan()
    {
        return $this->hasOne(StatusPekerjaan::class, 'id', 'status_pekerjaan_id');
    }

    public function getKategoriPekerjaan()
    {
        return $this->hasOne(KategoriPekerjaan::class, 'id', 'kategori_pekerjaan_id');
    }

    public function getPjPekerjaan()
    {
        return $this->hasOne(User::class, 'id', 'pj_pekerjaan_id');
    }

    public function getSifatPekerjaan()
    {
        return $this->hasOne(SifatPekerjaan::class, 'id', 'sifat_pekerjaan_id');
    }

    public function getPrioritas()
    {
        return $this->hasOne(Prioritas::class, 'id', 'prioritas_id');
    }

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
