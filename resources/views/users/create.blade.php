@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Buat Akun Guru dan Staff SMA Harapan Sungailiat') }}</h1>
    <p class="text-danger">Password Default Semua Guru dan Staff : '123456789'</p>
    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="crud-form-nip" class="form-label">NIP</label>
                            <input id="crud-form-nip" name="nip" type="number" class="form-control w-full"
                                placeholder="NIP" maxlength="18" value="{{ old('nip') }}">
                            @error('nip')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-name" class="form-label">Name</label>
                            <input id="crud-form-name" name="name" type="text" class="form-control w-full"
                                placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-username" class="form-label">Username</label>
                            <input id="crud-form-username" name="username" type="text" class="form-control w-full"
                                placeholder="Username" value="{{ old('username') }}">
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-email" class="form-label">Email</label>
                            <input id="crud-form-email" name="email" type="email" class="form-control w-full"
                                placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-password" class="form-label">Password</label>
                            <input id="crud-form-password" name="password" type="password" class="form-control w-full"
                                placeholder="Password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-gender" class="form-label">Gender</label>
                            <select id="crud-form-gender" name="gender" class="form-control w-full">
                                <option value="male" @if (old('gender') === 'male') selected @endif>Laki - Laki
                                </option>
                                <option value="female" @if (old('gender') === 'female') selected @endif>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-role" class="form-label">Role</label>
                            <select id="crud-form-role" name="role_id" class="form-control w-full">
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" @if (old('role_id') == $item->id) selected @endif>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="crud-form-photo" class="form-label">Photo</label>
                            <input id="crud-form-photo" name="photo" type="file" class="form-control w-full"
                                accept="image/*">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
