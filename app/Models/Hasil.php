<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;

    protected $table = 'hasil';
    protected $guarded = [];

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id', 'pekerjaan_id');
    }

    public function pic()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function prioritas()
    {
        return $this->hasOne(Prioritas::class, 'id', 'prioritas_id');
    }

    public function getNameDivisi()
    {
        $divisi = \App\Models\Divisi::find($this->divisi_id);
        return $divisi ? $divisi->divisi : '-';
    }
}
