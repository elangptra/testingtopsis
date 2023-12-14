@extends('layouts.app')

@section('title', 'Kriteria')

@section('contents')
    @include('sweetalert::alert')

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
            @php
                Session::forget('error');
            @endphp
        </div>
    @endif

    @if (Session::has('info'))
        <div class="alert alert-info">
            {{ Session::get('info') }}
            @php
                Session::forget('info');
            @endphp
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kriteria</h6>
        </div>
        <div class="card-body">
            @if (auth()->user()->level == 'admin')
                <a href="{{ route('kriteria.create') }}" class="btn btn-primary mb-3 ml-3">Tambah Kriteria</a>
                <a class="btn btn-success right mb-3" href="{{ route('kriteria') }}">Tampilkan semua Kriteria</a>
            @endif
            <form class="form-left my-2" method="get" action="{{ route('kriteria.search') }}">
                <div class="input-group mb-3 col-12 col-sm-8 col-md-6">
                    <input type="text" name="search" class="form-control w-50 d-inline" id="search"
                        placeholder="Masukkan Pencarian">
                    <button type="submit" class="btn btn-primary mb-1">
                        <span class="fa fa-search"></span> Cari
                    </button>
                    <div class="input-group-append">
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Kriteria</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot Kriteria</th>
                            <th>Attribute</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->kode_kriteria_as_string }}</td>
                                <td>{{ $row->nama_kriteria }}</td>
                                <td>{{ $row->bobot_kriteria }}</td>
                                <td>{{ $row->attribute == 'cost' ? 'Cost' : 'Benefit' }}</td>
                                <td>
                                    <a href="{{ route('kriteria.edit', $row->kode_kriteria) }}"
                                        class="btn btn-warning edit-button edit-button"
                                        onclick="confirmationEdit(event)">Edit</a>
                                    <a href="{{ route('kriteria.destroy', $row->kode_kriteria) }}"
                                        class="btn btn-danger delete-button" onclick="confirmationDel(event)">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    {{ $data->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
