<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NilaiAlt;
use App\Models\Alternatif;
use App\Models\Kriteria;

class PerhitunganController extends Controller
{

    public function index()
    {
        $pembagi = $this->matriksPembagi();
        $data = $this->normalisasiNilaiAlternatif();
        $data_V = $this->elemenMatriksTertimbang();
        $data_G = $this->matrikAreaPerkiraanPerbatasanG();
        $data_Q = $this->matrikJarakPerkiraanPerbatasanQ();
        return view('normalisasi.index', array_merge($pembagi, $data, $data_V, $data_G, $data_Q));
    }

    public function matriksPembagi()
    {
        // Mendapatkan semua data kriteria dari database
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();

        // $nilaiAltsKriteria = NilaiAlt::where('kode_krit', $kriterias->kode_kriteria)->get();
        $pembagi = [];
        $kuadratKriteria = [];

        foreach($kriterias as $kriteria) {
            foreach($nilaiAlts as $nilai) {
                for ($i = 0; $i < count($kriterias); $i++) {
                    $kuadratKriteria[$i] = 0;
                    for ($j = 0; $j < count($alternatifs); $j++) {
                        $kuadratKriteria[$i] += pow($nilai->value[$j][$i], 2);
                    }
                    // Menghitung akar kuadrat dari jumlah kuadrat kriteria
                    $pembagi[$i] = sqrt($kuadratKriteria[$i]);
                }
        }
            dd($pembagi);
            return compact('alternatifs', 'kriterias', 'nilaiAlts', 'pembagi');
        }
    }

    public function normalisasiNilaiAlternatif()
    {
        // Mendapatkan semua data kriteria dari database
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();

        $normalisasi = [];

        foreach ($kriterias as $kriteria) {
            // Mendapatkan nilai alternatif untuk kriteria tertentu
            $nilaiAltsKriteria = NilaiAlt::where('kode_krit', $kriteria->kode_kriteria)->get();

            // Mendapatkan nilai maksimum dan minimum untuk kriteria tersebut
            $maxValue = $nilaiAltsKriteria->max('value');
            $minValue = $nilaiAltsKriteria->min('value');

            // Normalisasi nilai alternatif berdasarkan tipe kriteria
            foreach ($nilaiAltsKriteria as $nilaiAlt) {
                if ($kriteria->attribute == 'benefit') {
                    $hitung = ($nilaiAlt->value - $minValue) / ($maxValue - $minValue);
                } else if ($kriteria->attribute == 'cost') {
                    $hitung = ($nilaiAlt->value - $maxValue) / ($minValue - $maxValue);
                }
                $normalisasi[$nilaiAlt->kode_krit][$nilaiAlt->kode_alt] = $hitung; // Store the result in the $normalisasi array
            }
        }
        return compact('alternatifs', 'kriterias', 'nilaiAlts', 'normalisasi');
    }

    public function elemenMatriksTertimbang()
    {
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();
        $v = [];

        $data = $this->normalisasiNilaiAlternatif();

        foreach ($alternatifs as $alternatif) {
            $v[$alternatif->kode_alternatif] = []; // Inisialisasi array untuk setiap alternatif

            foreach ($kriterias as $kriteria) {
                $nilaiAlt = NilaiAlt::where('kode_krit', $kriteria->kode_kriteria)
                    ->where('kode_alt', $alternatif->kode_alternatif)->get();

                // Periksa apakah kunci ada dalam array sebelum mengaksesnya
                if (isset($data['normalisasi'][$kriteria->kode_kriteria][$alternatif->kode_alternatif])) {
                    // Hitung matriks tertimbang (V) untuk setiap alternatif dan kriteria
                    $bobot_100 = $kriteria->bobot_kriteria / 100;
                    $v[$alternatif->kode_alternatif][$kriteria->kode_kriteria] =
                        ($bobot_100 * $data['normalisasi'][$kriteria->kode_kriteria][$alternatif->kode_alternatif]) + $bobot_100;
                } else {
                }
            }
        }

        // Tambahkan pernyataan dd untuk melihat nilai variabel 'v'
        // dd($v);
        return compact('alternatifs', 'kriterias', 'nilaiAlts', 'v');
    }

    public function matrikAreaPerkiraanPerbatasanG()
    {
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();
        $data = $this->elemenMatriksTertimbang();
        $g = [];
        $count_alt = Alternatif::count();
        foreach ($kriterias as $kriteria) {
            $result = 1; // Initialize $result for each kriteria
            foreach ($alternatifs as $alternatif) {
                if (isset($data['v'][$alternatif->kode_alternatif][$kriteria->kode_kriteria])) {
                    $value_v = $data['v'][$alternatif->kode_alternatif][$kriteria->kode_kriteria];
                    $result *= $value_v;
                } else {
                }
                // dd($value_v);
                // dd($data['v']);
            }
            $g[$kriteria->kode_kriteria] = pow($result, 1 / $count_alt);
        }
        return compact('alternatifs', 'kriterias', 'nilaiAlts', 'g');
    }

    public function matrikJarakPerkiraanPerbatasanQ()
    {
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::all();
        $data_V = $this->elemenMatriksTertimbang();
        $data_G = $this->matrikAreaPerkiraanPerbatasanG();
        $q = [];

        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                if (isset($data_V['v'][$alternatif->kode_alternatif][$kriteria->kode_kriteria])) {
                    $v = $data_V['v'][$alternatif->kode_alternatif][$kriteria->kode_kriteria];
                    $g = $data_G['g'][$kriteria->kode_kriteria];

                    $q[$alternatif->kode_alternatif][$kriteria->kode_kriteria] = $v - $g;
                } else {
                }
            }
        }
        return compact('alternatifs', 'kriterias', 'q');
    }
    public function countHasilQ($q)
    {
        $hasilQ = [];

        foreach ($q as $alternatif => $kriteriaValues) {
            $totalQ = 0;

            foreach ($kriteriaValues as $nilaiQ) {
                $totalQ += ($nilaiQ);
            }
            $hasilQ[$alternatif] = $totalQ;
        }
        return $hasilQ;
    }

    public function RankingAlternatifs()
    {
        $kriterias = Kriteria::all();
        $alternatifs = Alternatif::all();
        $data_Q = $this->matrikJarakPerkiraanPerbatasanQ();
        $rank = $this->countHasilQ($data_Q['q']);

        $rank = [];

        $rank = $this->countHasilQ($data_Q['q']);
        arsort($rank);
        $sortedRank = $rank;
        return view('normalisasi.hasil', compact('alternatifs', 'kriterias', 'data_Q', 'sortedRank', 'rank'));
    }
}
