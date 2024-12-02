@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="row justify-content-center text-center mb-2">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">{{ __('BUAT BARCODE PRESENSI HARI INI') }}</h1>
        </div>
        <div class="col-12">
            <p>Tanggal : {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
        </div>
        <div class="col-6 text-right">
            <p>Jam Datang : {{ $shift->start_time }}</p>
        </div>
        <div class="col-6 text-left">
            <p>Jam Pulang : {{ $shift->end_time }}</p>
        </div>
        <div class="col-12">
            <form action="{{ route('presensi.store') }}" method="post">
                @csrf
                <input type="hidden" name="barcode" value="1">
                @if (!$barcode)
                    <button type="submit" class="btn btn-primary btn-lg">GENERATE BARCODE</button>
                @else
                    <button disabled class="btn btn-primary btn-lg">BARCODE HARI INI TELAH DIBUAT</button>
                @endif
            </form>
        </div>

    </div>
@endsection
