<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alternatif = Alternatif::paginate(5);
		return view('alternatif.index', ['data' => $alternatif]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $alternatif = Alternatif::get();
		return view('alternatif.form', ['alternatif' => $alternatif]);
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
			'kode_alternatif' => $request->kode_alternatif,
			'nama_alternatif' => $request->nama_alternatif,
		];
        $validateKode = Alternatif::where('kode_alternatif', $request->kode_alternatif)->first();
        $validateNama = Alternatif::where('nama_alternatif', $request->nama_alternatif)->first();
        if ($validateKode) {
            return redirect()->route('alternatif')->with('error', 'Kode Alternatif Tidak Boleh Sama!');
        } elseif ($validateNama) {
            return redirect()->route('alternatif')->with('error', 'Nama Alternatif Tidak Boleh Sama!');
        } else {
            Alternatif::create($data);
            return redirect()->route('alternatif')->with('success', 'Berhasil Menambah Alternatif!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode_alternatif)
    {
        $alternatif = Alternatif::find($kode_alternatif);
        return view('alternatif.detail', compact('alternatif'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_alternatif)
    {
        $alternatif = Alternatif::find($kode_alternatif);
		return view('alternatif.edit', ['alternatif' => $alternatif]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_alternatif)
    {
        $alternatif = Alternatif::find($kode_alternatif);
        $validateKode = Alternatif::where('kode_alternatif', $request->kode_alternatif)
            ->where('kode_alternatif', '!=', $kode_alternatif)
            ->exists();
        $validateNama = Alternatif::where('nama_alternatif', $request->nama_alternatif)
            ->where('kode_alternatif', '!=', $kode_alternatif)
            ->exists();
        
        $data = [
			'kode_alternatif' => $request->kode_alternatif,
			'nama_alternatif' => $request->nama_alternatif,
		];
        if ($alternatif->kode_alternatif == $request->kode_alternatif && $alternatif->nama_alternatif == $request->nama_alternatif) {
            return redirect()->route('alternatif')->with('info', 'Tidak Ada Perubahan Pada Data Kriteria.');
        } 
        else if ($validateKode) {
            return redirect()->route('alternatif')->with('error', 'Kode Kriteria Tidak Boleh Sama!');
        } else if ($validateNama) {
            return redirect()->route('alternatif')->with('error', 'Nama Kriteria Tidak Boleh Sama!');
        } else {
                Alternatif::find($kode_alternatif)->update($data);
                return redirect()->route('alternatif')->with('success', 'Berhasil Mengedit Kriteria!');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_alternatif)
    {
        Kriteria::find($kode_alternatif)->delete();
		return redirect()->route('kriteria')->with('success','Berhasil Menghapus Data Kriteria');
    }

    public function search(Request $request){
        $keyword = $request->search;
        $alternatif = Alternatif::where('alternatif.nama_alternatif', 'like', "%" . $keyword . "%")
            ->orWhere('alternatif.kode_alternatif', 'like', "%" . $keyword . "%")
            ->paginate(5);
        return view('alternatif.index', ['data' => $alternatif])->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
