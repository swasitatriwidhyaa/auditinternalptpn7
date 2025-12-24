<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditStandard extends Model
{
    use HasFactory;

    // $guarded = [] berarti semua kolom boleh diisi, kecuali id
    protected $guarded = ['id'];

    // Relasi: Satu standar (misal ISO 9001) bisa dipakai di banyak jadwal Audit
    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}