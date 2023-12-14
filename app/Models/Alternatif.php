<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatif';
    protected $primaryKey = 'kode_alternatif';

	protected $fillable = 
    [
        'kode_alternatif', 
        'nama_alternatif',  
    ];

    public function getKodeAlternatifAsStringAttribute()
    {
        return 'A' . $this->attributes['kode_alternatif'];
    }
    public function nilaiAlts()
    {
        return $this->hasMany(NilaiAlt::class, 'kode_alt', 'kode_alternatif');
    }
}
