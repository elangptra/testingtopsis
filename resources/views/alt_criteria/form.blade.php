@extends('layouts.app')

@section('title', 'Form Nilai Alternatif')

@section('contents')
    <form action="{{ route('nilaialt.create.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Form Tambah Nilai Alternatif</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode_alt">Alternatif</label>
                            <select name="kode_alt" id="kode_alt" class="custom-select" required="required">
                                <option value="" selected disabled hidden>-- Pilih Alternatif --</option>
                                @foreach ($alternatif as $alt)
                                    @foreach ($kriteria as $krit)
                                        @php
                                            $existingValue = \App\Models\NilaiAlt::where('kode_alt', $alt->kode_alternatif)
                                                ->where('kode_krit', $krit->kode_kriteria)
                                                ->value('value');
                                        @endphp
                                    @endforeach
                                    @if (!isset($existingValue) || $existingValue === null)
                                        <option value="{{ $alt->kode_alternatif }}">
                                            {{ $alt->kode_alternatif_as_string }}
                                        </option>
                                    @endif  
                                @endforeach
                                </option>
                            </select>
                        </div>
                        @foreach ($kriteria as $krit)
                            <div class="form-group">
                                {{-- <input type="hidden" required="required" class="form-control" id="kode_alt"
                                    name="kode_alt" value="{{ $alt->kode_alternatif }}"> --}}
                                <label for="kode_alt">{{ $krit->kode_kriteria_as_string }}</label>
                                <input type="hidden" required="required" class="form-control" id="kode_alternatif"
                                    name="kode_krit{{ $krit->kode_kriteria }}" value="{{ $krit->kode_kriteria }}">
                                <input type="number" required="required" class="form-control" id="value"
                                    name="value{{ $krit->kode_kriteria }}" value="">
                            </div>
                        @endforeach

                        {{-- <div class="form-group">
                                <label for="nama_alternatif">Kode Kriteria</label>
                                <input type="text" required="required" class="form-control" id="nama_alternatif"
                                    name="nama_alternatif" value="">
                            </div> --}}
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
