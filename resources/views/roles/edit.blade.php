@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Edit Akun Jabatan / Bagian SMA Harapan Sungailiat') }}</h1>
    {{-- <p class="text-danger">Password Default Semua Guru dan Staff : '123456789'</p> --}}
    <form action="{{ route('roles.update', $role) }}" method="post">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="crud-form-name" class="form-label">Nama Jabatan / Bagian</label>
                            <input id="crud-form-name" name="name" type="text" class="form-control w-full"
                                placeholder="Name" value="{{ old('name', $role->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
