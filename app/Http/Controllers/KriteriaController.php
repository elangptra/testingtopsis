<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kriteria = Kriteria::paginate(5);
		return view('kriteria.index', ['data' => $kriteria]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kriteria = Kriteria::get();
		return view('kriteria.form', ['kriteria' => $kriteria]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
			'kode_kriteria' => $request->kode_kriteria,
			'nama_kriteria' => $request->nama_kriteria,
            'bobot_kriteria' => $request->bobot_kriteria,
			'attribute' => $request->attribute,
		];
        $validateKode = Kriteria::where('kode_kriteria', $request->kode_kriteria)->first();
        $validateNama = Kriteria::where('nama_kriteria', $request->nama_kriteria)->first();
        if ($validateKode) {
            return redirect()->route('kriteria')->with('error', 'Kode Kriteria Tidak Boleh Sama!');
        } elseif ($validateNama) {
            return redirect()->route('kriteria')->with('error', 'Nama Kriteria Tidak Boleh Sama!');
        } else {
            Kriteria::create($data);
            return redirect()->route('kriteria')->with('success', 'Berhasil Menambah Kriteria!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode_kriteria)
    {
        $kriteria = Kriteria::find($kode_kriteria);
        return view('kriteria.detail', compact('kriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_kriteria)
    {
        $kriteria = Kriteria::find($kode_kriteria);
		return view('kriteria.edit', ['kriteria' => $kriteria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_kriteria)
    {
        $kriteria = Kriteria::find($kode_kriteria);
        $validateKode = Kriteria::where('kode_kriteria', $request->kode_kriteria)
            ->where('kode_kriteria', '!=', $kode_kriteria)
            ->exists();
        $validateNama = Kriteria::where('nama_kriteria', $request->nama_kriteria)
            ->where('kode_kriteria', '!=', $kode_kriteria)
            ->exists();
        
        $data = [
			'kode_kriteria' => $request->kode_kriteria,
			'nama_kriteria' => $request->nama_kriteria,
            'bobot_kriteria' => $request->bobot_kriteria,
			'attribute' => $request->attribute,
		];
        if ($kriteria->kode_kriteria == $request->kode_kriteria && $kriteria->nama_kriteria == $request->nama_kriteria 
        && $kriteria->bobot_kriteria == $request->bobot_kriteria && $kriteria->attribute == $request->attribute) {
            return redirect()->route('kriteria')->with('info', 'Tidak Ada Perubahan Pada Data Kriteria.');
        } 
        else if ($validateKode) {
            return redirect()->route('kriteria')->with('error', 'Kode Kriteria Tidak Boleh Sama!');
        } else if ($validateNama) {
            return redirect()->route('kriteria')->with('error', 'Nama Kriteria Tidak Boleh Sama!');
        } else {
                Kriteria::find($kode_kriteria)->update($data);
                return redirect()->route('kriteria')->with('success', 'Berhasil Mengedit Kriteria!');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_kriteria)
    {
        Kriteria::find($kode_kriteria)->delete();
		return redirect()->route('kriteria')->with('success','Berhasil Menghapus Data Kriteria');
    }

    public function search(Request $request){
        $keyword = $request->search;
        $kriteria = Kriteria::where('kriteria.nama_kriteria', 'like', "%" . $keyword . "%")
            ->orWhere('kriteria.kode_kriteria', 'like', "%" . $keyword . "%")
            ->orWhere('kriteria.attribute', 'like', "%" . $keyword . "%")
            ->orWhere('kriteria.bobot_kriteria', 'like', "%" . $keyword . "%")
            ->paginate(5);
        return view('kriteria.index', ['data' => $kriteria])->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
