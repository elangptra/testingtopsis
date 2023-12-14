@extends('layouts.app')

@section('title', 'Form Edit Nilai Alternatif')

@section('contents')
    <form action="{{ route('nilaialt.create.update', $nilaiAlt->kode_alt) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Form Edit Nilai Alternatif</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode_alt">Alternatif</label>
                            <select name="kode_alt" id="kode_alt" class="custom-select" required="required">
                                    <option value="{{ $nilaiAlt->kode_alt }}""selected>
                                        {{ $nilaiAlt->alternatif->kode_alternatif_as_string }}
                                    </option>
                            </select>
                        </div>
                        @foreach ($kriteria as $krit)
                            <div class="form-group">
                                <label for="value{{ $krit->kode_kriteria }}">{{ $krit->kode_kriteria_as_string }}</label>
                                <input type="number" required="required" class="form-control"
                                    id="value{{ $krit->kode_kriteria }}" name="value{{ $krit->kode_kriteria }}"
                                    value="{{ $nilaiAlt->nilai->where('kode_krit', $krit->kode_kriteria)->first()->value }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('nilaialt') }}" class="btn btn-danger" role="button" aria-disabled="true"
                            style="margin-left:5px">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
