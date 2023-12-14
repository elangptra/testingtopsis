@extends('layouts.app')

@section('contents')
    <div class="container">
        <h2>Nilai Total Alternatif</h2>

        <table class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Alternatif</th>
                    @foreach($kriterias as $kriteria)
                        <th>{{ $kriteria->kode_kriteria_as_string }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alternatifs as $alternatif)
                    <tr>
                        <td>{{ $alternatif->nama_alternatif }}</td>
                        @foreach($kriterias as $kriteria)
                            <td>{{ number_format($data_Q['q'][$alternatif->kode_alternatif][$kriteria->kode_kriteria], 3, ',', '.') }}</td>
                        @endforeach
                        <td>{{ number_format($rank[$alternatif->kode_alternatif], 3, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Peringkat Alternatif</h2>
       <table class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Ranking</th>
                    <th>Nilai</th>
                    <th>Alternatif</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rankedAlternatives = collect($sortedRank)->map(function ($value) {
                        return number_format($value, 3, ',', '.');
                    });
        
                    $rankedAlternatives = collect($rankedAlternatives)->unique()->values()->all();
                @endphp
                @foreach($sortedRank as $alternatifKode => $ranking)
                    <tr>
                        <td>{{ collect($rankedAlternatives)->search(number_format($ranking, 3, ',', '.')) + 1 }}</td>
                        <td>{{ number_format($ranking, 3, ',', '.') }}</td>
                        <td>{{ $alternatifs->where('kode_alternatif', $alternatifKode)->first()->nama_alternatif }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
@endsection