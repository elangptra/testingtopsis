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
        $data = $this->normalisasiNilaiAlternatif();
        $data_pembagi = $this->pembagiNM();
        $normalisasiTerbobot = $this->normalisasiTerbobot();
        $solusiIdealPositif = $this->solusiIdealPositif();
        $solusiIdealNegatif = $this->solusiIdealNegatif();
        $jarakSolusiIdealPositif = $this->jarakSolusiIdealPositif();
        return view('normalisasi.index', array_merge($data, $data_pembagi, $normalisasiTerbobot, $solusiIdealPositif, $solusiIdealNegatif,$jarakSolusiIdealPositif));
    }

    function pembagiNM()
    {
        // Mendapatkan semua data kriteria dari database
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();

        $hasil = [];

        foreach ($kriterias as $kriteria) {
            $nilaiAltsKriteria = NilaiAlt::where('kode_krit', $kriteria->kode_kriteria)->get();
            $hasilPangkat = 1;

            foreach ($nilaiAltsKriteria as $nilaiAlt) {
                $rumus = pow($nilaiAlt->value, 2);
                $normalisasi[$nilaiAlt->kode_krit][$nilaiAlt->kode_alt] = $rumus;
            }

            $total = 0;

            foreach ($normalisasi[$nilaiAlt->kode_krit] as $value) {
                $total += $value;
            }

            $hasil[$nilaiAlt->kode_krit] = sqrt($total);
        }
        return compact('kriterias', 'nilaiAlts', 'hasil');
    }


    public function normalisasiNilaiAlternatif()
    {
        // Mendapatkan semua data kriteria dari database
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();

        $normalisasi = [];

        $data = $this->pembagiNM();

        foreach ($kriterias as $kriteria) {
            // Mendapatkan nilai alternatif untuk kriteria tertentu
            $nilaiAltsKriteria = NilaiAlt::where('kode_krit', $kriteria->kode_kriteria)->get();
            // Normalisasi nilai alternatif berdasarkan tipe kriteria
            foreach ($nilaiAltsKriteria as $nilaiAlt) {
                $data['hasil'][$nilaiAlt->kode_krit]; // Store the result in the $normalisasi array
                $normalisasi[$nilaiAlt->kode_alt][$nilaiAlt->kode_krit] = $nilaiAlt->value / $data['hasil'][$nilaiAlt->kode_krit];
            }
        }
        return compact('alternatifs', 'kriterias', 'nilaiAlts', 'normalisasi');
    }

    public function normalisasiTerbobot()
    {
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();
        $y = [];

        $data = $this->normalisasiNilaiAlternatif();

        foreach ($alternatifs as $alternatif) {
            $y[$alternatif->kode_alternatif] = []; // Inisialisasi array untuk setiap alternatif

            foreach ($kriterias as $kriteria) {
                $nilaiAlt = NilaiAlt::where('kode_krit', $kriteria->kode_kriteria)
                    ->where('kode_alt', $alternatif->kode_alternatif)->get();
                // Periksa apakah kunci ada dalam array sebelum mengaksesnya
                if (isset($data['normalisasi'][$alternatif->kode_alternatif][$kriteria->kode_kriteria])) {
                    // Hitung matriks tertimbang (V) untuk setiap alternatif dan kriteria
                    $bobot = $kriteria->bobot_kriteria;
                    $y[$alternatif->kode_alternatif][$kriteria->kode_kriteria] =
                        ($bobot * $data['normalisasi'][$alternatif->kode_alternatif][$kriteria->kode_kriteria]);
                } else {
                }
            }
        }
        return compact('alternatifs', 'kriterias', 'nilaiAlts', 'y');
    }

    public function solusiIdealPositif()
    {
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();
        $data = $this->normalisasiTerbobot();

        $tipe = Kriteria::pluck('attribute')->toArray();

        // Membuat array indeks mulai dari 1 hingga jumlah elemen
        $keys = range(1, count($tipe));

        // Menggabungkan array kunci dan nilai
        $type = array_combine($keys, $tipe);

        $Aplus = [];
        $values = $data['y'];
        // dd($values);

        $result = [];
        foreach ($values as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $result[$colIndex][$rowIndex] = $value;
            }
        }

        for ($i = 1; $i <= count($result); $i++) {
            for ($j = 1; $j <= count($result[$i]); $j++) {
                $max = max($result[$i]);
                $min = min($result[$i]);
                if ($type[$i] == 'Benefit' || $type[$i] == 'benefit') {
                    $Aplus[$i] = $max;
                } else if ($type[$i] == 'Cost' || $type[$i] == 'cost') {
                    $Aplus[$i] = $min;
                }
            }
        }
        return compact('Aplus');
    }

    public function solusiIdealNegatif()
    {
        $kriterias = Kriteria::all();
        $nilaiAlts = NilaiAlt::all();
        $alternatifs = Alternatif::all();
        $data = $this->normalisasiTerbobot();

        $tipe = Kriteria::pluck('attribute')->toArray();

        // Membuat array indeks mulai dari 1 hingga jumlah elemen
        $keys = range(1, count($tipe));

        // Menggabungkan array kunci dan nilai
        $type = array_combine($keys, $tipe);

        $Amin = [];
        $values = $data['y'];
        // dd($values);

        $result = [];
        foreach ($values as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $result[$colIndex][$rowIndex] = $value;
            }
        }

        for ($i = 1; $i <= count($result); $i++) {
            for ($j = 1; $j <= count($result[$i]); $j++) {
                $max = min($result[$i]);
                $min = max($result[$i]);
                if ($type[$i] == 'Benefit' || $type[$i] == 'benefit') {
                    $Amin[$i] = $max;
                } else if ($type[$i] == 'Cost' || $type[$i] == 'cost') {
                    $Amin[$i] = $min;
                }
            }
        }
        return compact('Amin');
    }


    public function jarakSolusiIdealPositif()
    {
        $kriterias = Kriteria::all();
        $data = $this->normalisasiTerbobot();
        $data2 = $this->solusiIdealPositif();

        $values = $data['y'];
        $val = $data2['Aplus'];

        $result = [];
        foreach ($values as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $result[$colIndex][$rowIndex] = $value;
            }
        }

        $Dplus = [];
        for ($i = 1; $i <= count($result[1]); $i++) {
            $jarak = 0;

            for ($j = 1; $j <= count($result); $j++) {
                $jarak += pow(($val[$j] - $result[$j][$i]), 2);
            }
            $Dplus[$i] = number_format(sqrt($jarak), 3);

        }
        // dd($Dplus);
        return compact('Dplus');
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
        $data_Q = $this->jarakSolusiIdealPositif();
        $rank = $this->countHasilQ($data_Q['q']);

        $rank = [];

        $rank = $this->countHasilQ($data_Q['q']);
        arsort($rank);
        $sortedRank = $rank;
        return view('normalisasi.hasil', compact('alternatifs', 'kriterias', 'data_Q', 'sortedRank', 'rank'));
    }
}
