<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pekerjaan extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $table = 'pekerjaan';
    protected $guarded = [];

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getSifatPekerjaan()
    {
        return $this->hasOne(SifatPekerjaan::class, 'id', 'sifat_pekerjaan_id');
    }

    public function getPrioritas()
    {
        return $this->hasOne(Prioritas::class, 'id', 'prioritas_id');
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
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getSubPekerjaan()
    {
        return $this->hasMany(SubPekerjaan::class, 'pekerjaan_id');
    }
}
