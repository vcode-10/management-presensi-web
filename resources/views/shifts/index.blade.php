@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Jadwal Absen') }}</h1>
    <p>Jadwal Masuk Harian</p>


    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-bordered table-stripped" id="example2">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Shift</th>
                        <th class="text-center">Mulai Shift</th>
                        <th class="text-center">Akhir Shift</th>
                        <th class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $key => $shift)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $shift->shift_name }}</td>
                            <td class="text-center">{{ $shift->start_time }}</td>
                            <td class="text-center">{{ $shift->end_time }}</td>
                            <td class="text-center">
                                <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-block btn-primary btn-xs">
                                    Edit
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
