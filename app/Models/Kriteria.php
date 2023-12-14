<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'kode_kriteria';

	protected $fillable = 
    [
        'kode_kriteria', 
        'nama_kriteria',
        'bobot_kriteria',
        'attribute',
    ];

    public function getKodeKriteriaAsStringAttribute()
    {
        return 'C' . $this->attributes['kode_kriteria'];
    }
    public function nilai_alt() 
	{
	return $this->hasMany(NilaiAlt::class,'kode_krit', 'kode_kriteria');
	}

}
