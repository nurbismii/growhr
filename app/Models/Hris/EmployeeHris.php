<?php

namespace App\Models\Hris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHris extends Model
{
    use HasFactory;

    protected $connection = 'hris';
    protected $table = 'employees';
    protected $primaryKey = 'nik_karyawan';
    public $incrementing = false;
    protected $guarded = [];

    public function getDivisi()
    {
        return $this->hasOne(DivisiHris::class, 'id', 'divisi_id');
    }
}
