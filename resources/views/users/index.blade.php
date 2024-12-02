@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Pengguna SMA Harapan Sungailiat') }}</h1>
    <p>Password Default Semua Pengguna : '123456789'</p>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-2">
        Tambah Akun
    </a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengguna SMA Harapan Sungailiat</h6>
        </div>
        <div class="card-body">
            <table class="table  table-bordered  table-hover" id="example2">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th class="text-center">Image</th>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Is Active</th>
                        <th class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                    class="img-profile rounded-circle avatar font-weight-bold">
                            </td>
                            <td>{{ $user->nip }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>
                                @if ($user->active == 1)
                                    <a href="{{ route('users.toggle-active', $user->id) }}" class="text-success">
                                        <i class="fas fa-check class  w-4 h-4 mr-2"></i>
                                        <span>Aktif</span>
                                    </a>
                                @else
                                    <a href="{{ route('users.toggle-active', $user->id) }}" class="text-danger">
                                        <i class="fas fa-times w-4 h-4 mr-2"></i>
                                        <span>Tidak Aktif</span>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-xs">
                                    Edit
                                </a>
                                <a href="{{ route('users.destroy', $user) }}"
                                    onclick="notificationBeforeDelete(event, this)" class="btn btn-danger btn-xs">
                                    Delete
                                </a>
                                <a href="{{ route('users.reset-password', $user->id) }}" class="btn btn-info btn-xs">
                                    Reset Password
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@push('js')
    <form action="" id="delete-form" method="post">
        @method('delete')
        @csrf
    </form>
    <script>
        $('#example2').DataTable({
            "responsive": true,
        });

        function notificationBeforeDelete(event, el) {
            event.preventDefault();
            if (confirm('Apakah anda yakin akan menghapus data ? ')) {
                $("#delete-form").attr('action', $(el).attr('href'));
                $("#delete-form").submit();
            }
        }
    </script>
@endpush
