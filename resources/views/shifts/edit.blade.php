@extends('layouts.admin')

@section('main-content')
    <form action="{{ route('shifts.update', $shift) }}" method="post">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="crud-form-shift_name" class="form-label">Hari</label>
                            <input id="crud-form-shift_name" name="shift_name" type="text" class="w-full form-control"
                                value="{{ old('shift_name', $shift->shift_name) }}" disabled>
                            @error('shift_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="crud-form-start_time" class="form-label">Jam Datang</label>
                            <input id="crud-form-start_time" name="start_time" type="time" class="w-full form-control"
                                placeholder="start_time" value="{{ old('start_time', $shift->start_time) }}">
                            @error('start_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="crud-form-end_time" class="form-label">Jam Pulang</label>
                            <input id="crud-form-end_time" name="end_time" type="time" class="w-full form-control"
                                placeholder="end_time" value="{{ old('end_time', $shift->end_time) }}">
                            @error('end_time')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('shifts.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @stop
