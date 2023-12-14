<!-- resources/views/normalisasi/index.blade.php -->

@extends('layouts.app') {{-- Assuming you have a layout file --}}

@section('contents')
    <div class="container">
        <h2>Menghitung Matriks Pembagi</h2>

        {{-- Add this section to inspect variables --}}
        {{-- {{ dd($normalisasi, $kriterias, $alternatifs) }} --}}

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    @foreach ($kriterias as $kriteria)
                        <th style="text-align: center;">{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($pembagi as $index => $nilai)
                    <tr>
                        <td>{{ number_format($nilai, 2) }}</td>
                    </tr>
                @endforeach
        </table>

        <h2>Normalisasi Nilai Alternatif</h2>

        {{-- Add this section to inspect variables --}}
        {{-- {{ dd($normalisasi, $kriterias, $alternatifs) }} --}}

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;">Alternatif</th>
                    @foreach ($kriterias as $kriteria)
                        <th style="text-align: center;">{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatifs as $alternatif)
                    <tr>
                        <td style="text-align: center;">{{ $alternatif->nama_alternatif }}</td>
                        @foreach ($kriterias as $kriteria)
                            {{-- Check if the key exists in the $normalisasi array --}}
                            @if (isset($normalisasi[$kriteria->kode_kriteria][$alternatif->kode_alternatif]))
                                <td style="text-align: center;">
                                    {{ number_format($normalisasi[$kriteria->kode_kriteria][$alternatif->kode_alternatif], 3, ',', '.') }}
                                </td>
                            @else
                                <td style="text-align: center;">-</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Elemen Matriks Tertimbang (V)</h2>
        {{-- Tambahkan ini untuk memeriksa data yang dikirim ke view --}}
        {{-- {{ dd($alternatifs, $kriterias, $nilaiAlts, $v) }} --}}

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;">Alternatif</th>
                    @foreach ($kriterias as $kriteria)
                        <th style="text-align: center;">{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatifs as $alternatif)
                    <tr>
                        <td style="text-align: center;">{{ $alternatif->nama_alternatif }}</td>
                        @foreach ($kriterias as $kriteria)
                            {{-- Pengecekan keberadaan kunci --}}
                            @if (isset($v[$alternatif->kode_alternatif]) && isset($v[$alternatif->kode_alternatif][$kriteria->kode_kriteria]))
                                {{-- Pastikan bahwa $v[$alternatif->kode_alternatif][$kriteria->kode_kriteria] berisi nilai --}}
                                <td style="text-align: center;">
                                    {{ number_format($v[$alternatif->kode_alternatif][$kriteria->kode_kriteria], 5, ',', '.') }}
                                </td>
                            @else
                                <td style="text-align: center;">-</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Matriks Area Perkiraan Perbatasan (G)</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    @foreach ($kriterias as $kriteria)
                        <th>{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>G</td>
                    @foreach ($kriterias as $kriteria)
                        {{-- Pengecekan keberadaan kunci --}}
                        @if (isset($g[$kriteria->kode_kriteria]))
                            {{-- Pastikan bahwa $g[$kriteria->kode_kriteria] berisi nilai --}}
                            <td>{{ number_format($g[$kriteria->kode_kriteria], 5, ',', '.') }}</td>
                        @else
                            <td>-</td>
                        @endif
                    @endforeach
                </tr>
            </tbody>
        </table>
        </table>
        <h2>Matriks Jarak Alternatif Dari Daerah Perkiraan Perbatasan (Q)</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;">Alternatif</th>
                    @foreach ($kriterias as $kriteria)
                        <th style="text-align: center;">{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatifs as $alternatif)
                    <tr>
                        <td style="text-align: center;">{{ $alternatif->nama_alternatif }}</td>
                        @foreach ($kriterias as $kriteria)
                            {{-- Pengecekan keberadaan kunci --}}
                            @if (isset($q[$alternatif->kode_alternatif]) && isset($q[$alternatif->kode_alternatif][$kriteria->kode_kriteria]))
                                {{-- Pastikan bahwa $v[$alternatif->kode_alternatif][$kriteria->kode_kriteria] berisi nilai --}}
                                <td style="text-align: center;">
                                    {{ number_format($q[$alternatif->kode_alternatif][$kriteria->kode_kriteria], 3, ',', '.') }}
                                </td>
                            @else
                                <td style="text-align: center;">-</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
