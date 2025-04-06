<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembaruanStatusPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pembaruan_status_pekerjaan';
    protected $guarded = [];

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id', 'pekerjaan_id');
    }

    public function statusPekerjaan()
    {
        return $this->hasOne(StatusPekerjaan::class, 'id', 'status_pekerjaan_id');
    }

    public function prioritas()
    {
        return $this->hasOne(Prioritas::class, 'id', 'prioritas_id');
    }
}
