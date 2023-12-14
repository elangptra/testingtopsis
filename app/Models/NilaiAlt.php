<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAlt extends Model
{
    protected $table = 'nilai_alt';

    protected $fillable = 
    [
        'kode_alt',
        'kode_krit',
        'value', 
    ];

    public function getKodeKriteriaAsStringAttribute()
    {
        return 'C' . $this->attributes['kode_krit'];
    }
    public function getKodeAlternatifAsStringAttribute()
    {
        return 'A' . $this->attributes['kode_alt'];
    }
    public function alternatif() 
	{
	return $this->belongsTo(Alternatif::class,'kode_alt', 'kode_alternatif');
	}
    public function kriteria() 
	{
	return $this->belongsTo(Kriteria::class,'kode_krit', 'kode_kriteria');
	}
    public function nilai()
    {
        return $this->hasMany(NilaiAlt::class, 'kode_alt', 'kode_alt');
    }
}
