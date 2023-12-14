@extends('layouts.app')

@section('title', 'Form Alternatif')

@section('contents')
<form action="{{ route('alternatif.edit', $alternatif->kode_alternatif) }}"
    method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Form Edit Alternatif</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="kode_alternatif">Kode Alternatif</label>
                        <input type="number" class="form-control" required="required" id="kode_alternatif" name="kode_alternatif"
                            value="{{ $alternatif->kode_alternatif }}">
                    </div>
                    <div class="form-group">
                        <label for="nama_alternatif">Nama Alternatif</label>
                        <input type="text" class="form-control" required="required" id="nama_alternatif" name="nama_alternatif"
                            value="{{ $alternatif->nama_alternatif }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('alternatif') }}" class="btn btn-danger" role="button" aria-disabled="true" style="margin-left:5px">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
