@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="row justify-content-center text-center mb-2">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">{{ __('Daftar Presensi') }}</h1>
        </div>
        <div class="col-12">
            <p>
                Tanggal : {{ $shift->day }}, {{ \Carbon\Carbon::now()->format('Y-m-d') }}
            </p>
        </div>
        <div class="col-6 text-right">
            <p>Jam Datang : {{ $shift->start_time }}</p>
        </div>
        <div class="col-6 text-left">
            <p>Jam Pulang : {{ $shift->end_time }}</p>
        </div>

        <div class="col-6">
            <form action="{{ route('export.excel') }}" method="POST" class="form-inline">
                @csrf
                <input type="date" name="start_date" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                    class="form-control form-control-lg">
                <input type="date" name="end_date" value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}"
                    class="form-control form-control-lg ml-2">
                <button type="submit" class="ml-2 shadow-md btn btn-success btn-lg">Export to Excel</button>
            </form>
        </div>
        <div class="col-6">
            <form action="{{ route('presensi.index') }}" method="get" class="form-inline float-right">
                @csrf
                <select name="filter_bulan" id="filter_bulan" class="form-control form-control-lg">
                    @for ($i = 1; $i <= 12; $i++)
                        @php
                            $bulan = date('F', mktime(0, 0, 0, $i, 1));
                        @endphp
                        <option value="{{ $i }}">{{ $bulan }}</option>
                    @endfor
                </select>
                <button type="submit" class="ml-2 shadow-md btn btn-primary btn-lg">Filter</button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Presensi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table -mt-2 table-report">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">NIP</th>
                            <th class="whitespace-nowrap">Nama Lengkap</th>
                            <th class="text-center whitespace-nowrap text-success">Hadir</th>
                            <th class="text-center whitespace-nowrap text-warning">Sakit</th>
                            <th class="text-center whitespace-nowrap text-info">Izin</th>
                            <th class="text-center whitespace-nowrap text-danger">Alpa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataGuru as $item)
                            <tr class="intro-x">
                                <td class="w-40">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">{{ $item['nip'] }}
                                    </div>
                                </td>
                                <td class="w-40">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">{{ $item['nama'] }}
                                    </div>
                                </td>
                                <td class="text-center text-success">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">
                                        {{ $item['totalHadir'] }}</div>
                                </td>
                                <td class="text-center text-warning">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">
                                        {{ $item['totalSakit'] }}</div>
                                </td>
                                <td class="text-center text-info">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">
                                        {{ $item['totalIzin'] }}</div>
                                </td>
                                <td class="text-center text-danger">
                                    <div class="text-slate-500 text-sm whitespace-nowrap mt-0.5">
                                        {{ $item['totalAlpa'] }}</div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
