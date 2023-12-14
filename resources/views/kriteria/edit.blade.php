@extends('layouts.app')

@section('title', 'Form Kriteria')

@section('contents')
<form action="{{ route('kriteria.edit', $kriteria->kode_kriteria) }}"
    method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Form Edit Kriteria</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="kode_kriteria">Kode Kriteria</label>
                        <input type="number" class="form-control" required="required" id="kode_kriteria" name="kode_kriteria"
                            value="{{ $kriteria->kode_kriteria }}">
                    </div>
                    <div class="form-group">
                        <label for="nama_kriteria">Nama Kriteria</label>
                        <input type="text" class="form-control" required="required" id="nama_kriteria" name="nama_kriteria"
                            value="{{ $kriteria->nama_kriteria }}">
                    </div>
                    <div class="form-group">
                        <label for="bobot_kriteria">Bobot Kriteria</label><br>
                        <input type="number" class="form-control" name="bobot_kriteria"
                            value="{{ isset($kriteria) ? $kriteria->bobot_kriteria : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="attribute">Attribute</label>
                        <select name="attribute" required="required" id="attribute" class="custom-select">
                            {{-- <option value="" selected disabled hidden>-- Pilih Attribute --</option> --}}
                            <option value="cost"{{ ($kriteria->attribute == 'cost' ? 'selected' : '')}}>Cost</option>
                            <option value="benefit"{{ ($kriteria->attribute == 'benefit' ? 'selected' : '')}}>Benefit</option>

                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kriteria') }}" class="btn btn-danger" role="button" aria-disabled="true" style="margin-left:5px">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
