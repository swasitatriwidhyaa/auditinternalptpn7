<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi kebalikannya: Satu temuan pasti milik satu Jadwal Audit tertentu
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}