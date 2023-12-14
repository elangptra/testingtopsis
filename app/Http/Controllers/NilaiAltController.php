<?php

namespace App\Http\Controllers;

use App\Models\NilaiAlt;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNilaiAltRequest;
use App\Http\Requests\UpdateNilaiAltRequest;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class NilaiAltController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alternatif = Alternatif::with('nilaiAlts')->get();
        $kriteria = Kriteria::get();
        $nilai_alt = NilaiAlt::all();
        return view ('alt_criteria.index',compact('nilai_alt','alternatif','kriteria') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nilai_alt = NilaiAlt::get();
        $alternatif = Alternatif::get();
        $kriteria = Kriteria::get();
		return view('alt_criteria.form',compact('nilai_alt','alternatif','kriteria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNilaiAltRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = NilaiAlt::get();
        $kriteria = Kriteria::get();
        $request->validate([
            'value*' => 'required',]);
        if($validate){
            
        }
        foreach ($kriteria as $Kriteria) {
            $nilaiAlt = new nilaiAlt;

            $nilaiAlt->kode_alt = $request->get('kode_alt');
            $nilaiAlt->kode_krit = $request->get('kode_krit' . $Kriteria->kode_kriteria);
            $nilaiAlt->value = $request->get('value' . $Kriteria->kode_kriteria);
            $nilaiAlt->save();
        }
            return redirect()->route('nilaialt')->with('success', 'Berhasil Menambah Nilai Alternatif!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NilaiAlt  $nilaiAlt
     * @return \Illuminate\Http\Response
     */
    public function show(Request $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NilaiAlt  $nilaiAlt
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_alt)
    {
    // Find the specific NilaiAlt record by its id
    $nilaiAlt = NilaiAlt::where('kode_alt', $kode_alt)->first();
    $nilai = NilaiAlt::all();
    // Check if the record exists
    if (!$nilaiAlt) {
        return redirect()->route('nilaialt')->with('error', 'Nilai Alternatif tidak ditemukan');
    }

    // Retrieve necessary data for the form
    $alternatif = Alternatif::get();
    $kriteria = Kriteria::get();

    // Pass the data to the view for editing
    return view('alt_criteria.edit', compact('nilaiAlt', 'alternatif', 'kriteria','nilai'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNilaiAltRequest  $request
     * @param  \App\Models\NilaiAlt  $nilaiAlt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_alt)
{
    // Find the specific NilaiAlt record by its id

    // Check if the record exists
    // if (!$nilaiAlt) {
    //     return redirect()->route('nilaialt')->with('error', 'Nilai Alternatif tidak ditemukan');
    // }

    // Update the values based on the form input
    
    
    // Loop through all criteria and update the values
    $kriteria = Kriteria::all();
    foreach ($kriteria as $krit) {
        // Find the specific NilaiAlt record by its id and criteria code
        $nilaiAlt = NilaiAlt::where('kode_alt', $kode_alt)->where('kode_krit', $krit->kode_kriteria)->first();

        // Check if the record exists
        if (!$nilaiAlt) {
            return redirect()->route('nilaialt')->with('error', 'Nilai Alternatif tidak ditemukan');
        }

        // Update the values based on the form input
        $nilaiAlt->update(['value' => $request->get('value' . $krit->kode_kriteria)]);
    }
    // NilaiAlt::find($kode_alt)->update($data);

    // Save the updated record
    // $nilaiAlt->save();

    // Redirect back to the index page with a success message
    return redirect()->route('nilaialt')->with('success', 'Berhasil Mengupdate Nilai Alternatif');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NilaiAlt  $nilaiAlt
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_alt)
    {
        try {
            // Cari dan hapus semua nilai untuk alternatif tertentu
            NilaiAlt::where('kode_alt', $kode_alt)->delete();
    
            return redirect()->route('nilaialt')->with('success', 'Berhasil Menghapus Semua Nilai Alternatif');
        } catch (\Exception $e) {
            return redirect()->route('nilaialt')->with('error', 'Gagal menghapus nilai alternatif. Error: ' . $e->getMessage());
        }
    }
}
