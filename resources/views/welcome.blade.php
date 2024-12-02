<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WhatsApp Web</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #4e73df;
        }

        .login-container {
            max-width: 1200px;
            margin: auto;
            padding: 35px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-logo img {
            width: 50px;
        }

        .login-form {
            margin-top: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .barcode-container {
            text-align: center;
            margin-top: 20px;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }


        .links>a {
            color: #ffffff;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            @endif
        </div>
        <div class="login-container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card-header">
                        <h1 class="font-large text-lg text-gray-800">{{ __('ABSENSI HARIAN') }}</h1>
                        <span>Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} |</span>
                        <span>Jam Datang : {{ $shift->start_time }} |</span>
                        <span>Jam Pulang : {{ $shift->end_time }}</span>
                    </div>
                    <table class="table mt-2 table-border ">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Tanggal</th>
                                <th class="text-center text-success">Jam Datang</th>
                                <th class="text-center text-warning">Jam Pulang</th>
                                <th class="text-center text-danger">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $item)
                                <tr class="intro-x">
                                    <td class="w-40">
                                        <div class="text-sm mt-0.5">
                                            {{ $item->user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm mt-0.5">
                                            {{ \Carbon\Carbon::parse($item->create_at)->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-slate-500 text-sm mt-0.5">
                                            {{ $item->hour_came ? \Carbon\Carbon::parse($item->hour_came)->format('H:i') : '....' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-slate-500 text-sm mt-0.5">
                                            {{ $item->home_time ? \Carbon\Carbon::parse($item->home_time)->format('H:i') : '....' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status == 'Hadir')
                                            <span class="flex items-center justify-center text-success">Hadir</span>
                                        @elseif($item->status == 'Sakit')
                                            <span class="flex items-center justify-center text-warning">Sakit</span>
                                        @elseif($item->status == 'Alpa')
                                            <span class="flex items-center justify-center text-danger">Alpa</span>
                                        @elseif($item->status == 'Izin')
                                            <span class="flex items-center justify-center text-white">Izin</span>
                                        @else
                                            <span class="flex items-center justify-center text-primary">Belum ada
                                                Keterangan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <div class="login-logo">
                        <img src="{{ asset('img/logo_yapendik.png') }}" alt="WhatsApp Web">
                        <span>SMA HARAPAN SUNGAILIAT</span>
                    </div>
                    <form class="login-form">
                        <div class="form-group text-center">
                            <label for="barcode">Scan the QR Code</label>
                            <div class="barcode-container">
                                {{ QrCode::size(400)->generate($barcode->barcode) }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
