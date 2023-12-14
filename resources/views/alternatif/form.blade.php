@extends('layouts.app')

@section('title', 'Form Alternatif')

@section('contents')
    <form action="{{ route('alternatif.create.store') }}"
        method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Form Tambah Alternatif</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode_alternatif">Kode Alternatif</label>
                            <input type="number" required="required" class="form-control" id="kode_alternatif"
                                name="kode_alternatif" value="">
                        </div>
                        <div class="form-group">
                            <label for="nama_alternatif">Nama alternatif</label>
                            <input type="text" required="required" class="form-control" id="nama_alternatif"
                                name="nama_alternatif" value="">
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
