@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Jabatan/Bagian SMA Harapan Sungailiat') }}</h1>
    {{-- <p>Password Default Semua Guru dan Staff : '123456789'</p> --}}
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-2">
        Tambah Akun
    </a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Jabatan/Bagian SMA Harapan Sungailiat</h6>
        </div>
        <div class="card-body">
            <table class="table  table-bordered  table-hover" id="example2">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Jabatan/Bagian</th>
                        <th>Alias</th>
                        <th class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $role->name }}</td>
                            <td>{{ $role->slug }}</td>

                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-xs">
                                    Edit
                                </a>
                                <a href="{{ route('roles.destroy', $role) }}"
                                    onclick="notificationBeforeDelete(event, this)" class="btn btn-danger btn-xs">
                                    Delete
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
