@extends('adminlte::page')

@section('title', 'Tambah Shift')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Shift</h1>
@stop

@section('content')
    <form action="{{ route('shifts.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="shift_name">Nama Shift</label>
                            <input type="text" class="form-control @error('shift_name') is-invalid @enderror"
                                id="shift_name" placeholder="shift_name lengkap" name="shift_name"
                                value="{{ old('shift_name') }}">
                            @error('shift_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="start_time">Mulai jam</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                id="start_time" placeholder="start_time lengkap" name="start_time"
                                value="{{ old('start_time') }}">
                            @error('start_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_time">Akhir Jam</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                id="end_time" placeholder="end_time lengkap" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @stop
