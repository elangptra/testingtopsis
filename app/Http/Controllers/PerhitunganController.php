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
        $jarakSolusiIdealNegatif = $this->jarakSolusiIdealNegatif();
        $nilaiPreferensi = $this->nilaiPreferensi();
        $rankingAlternatif = $this->rankingAlternatif();
        return view('normalisasi.index', array_merge($data, $data_pembagi, $normalisasiTerbobot, $solusiIdealPositif, $solusiIdealNegatif, $jarakSolusiIdealPositif, $jarakSolusiIdealNegatif, $nilaiPreferensi, $rankingAlternatif));
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

    public function jarakSolusiIdealNegatif()
    {
        $kriterias = Kriteria::all();
        $data = $this->normalisasiTerbobot();
        $data2 = $this->solusiIdealNegatif();

        $values = $data['y'];
        $val = $data2['Amin'];

        $result = [];
        foreach ($values as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $result[$colIndex][$rowIndex] = $value;
            }
        }

        $Dmin = [];
        for ($i = 1; $i <= count($result[1]); $i++) {
            $jarak = 0;

            for ($j = 1; $j <= count($result); $j++) {
                $jarak += pow(($result[$j][$i] - $val[$j]), 2);
            }
            $Dmin[$i] = number_format(sqrt($jarak), 3);
        }
        // dd($Dmin);
        return compact('Dmin');
    }

    public function nilaiPreferensi()
    {
        $kriterias = Kriteria::all();
        $data = $this->normalisasiTerbobot();
        $data2 = $this->jarakSolusiIdealNegatif();
        $data3 = $this->jarakSolusiIdealPositif();

        $valMin = $data2['Dmin'];
        $valPlus = $data3['Dplus'];

        $preferensi = [];
        for ($i = 1; $i <= count($valPlus); $i++) {
            $preferensi[$i] = number_format($valMin[$i] / ($valPlus[$i] + $valMin[$i]), 3);
        }
        // dd($preferensi);
        return compact('preferensi');
    }

    public function rankingAlternatif()
    {
        $kriterias = Kriteria::all();
        $data = $this->nilaiPreferensi();

        $val = $data['preferensi'];
        arsort($val);

        // Assign new rankings
        $rank = [];
        $rankingValue = 1;
        foreach ($val as $alternatifId => $totalRanking) {
            $rank[$alternatifId] = $rankingValue;
            $rankingValue++;
        }
        // dd($rank);
        return compact('rank');
    }
}
