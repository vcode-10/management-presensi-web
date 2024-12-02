<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\AttendanceSaved;
use App\Models\BarcodeScan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancePerUserExport;

class ApiController extends Controller

{
    public function absen(Request $request)
    {

        $barcode = $request->barcode;
        $id = $request->user;
        $status = $request->status;

        $latestBarcodeScan = BarcodeScan::latest()->first();
        $user = User::find($id)->first();

        $timestamp = Carbon::now();
        $showTime = $timestamp->format('H:i');
        $currentTime = $timestamp->format('H:i:s');

        // Cek jika guru tidak ditemukan
        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'ID Barcode tidak ditemukan.',
                'id' => '',
                'name' => '',
                'jam' => ''
            ]);
        }

        Carbon::setLocale('id');
        $created_at = Carbon::today();
        $today = Carbon::now()->isoFormat('dddd');
        $shift = Shift::where('day', $today)->first();

        if (!$shift) {
            return response()->json([
                'status' => 'success',
                'message' => 'Shift tidak ditemukan.'
            ]);
        }

        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        if (isset($status) && is_null($barcode)) {
            $attendance = Attendance::where('user_id', $id)->first();

            if ($attendance) {
                // Mendapatkan tanggal saat ini
                $today = date('Y-m-d');

                // Mendapatkan tanggal kehadiran dari catatan
                $attendanceDate = $attendance->created_at->format('Y-m-d');

                if ($attendanceDate === $today) {
                    // Jika kehadiran sudah ada untuk hari ini
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Permintaan ' . $status . ' hanya dapat dilakukan sekali sehari.',
                        'id' => $user->nip,
                        'name' => $user->name,
                        'jam' => $showTime
                    ]);
                }
            }

            // Jika kehadiran belum ada atau kehadiran belum dilakukan hari ini
            // Melanjutkan logika Anda di sini

            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->status = $status;
            $attendance->save();

            // Kode lanjutan jika kehadiran baru berhasil dibuat
            return response()->json([
                'status' => 'success',
                'message' => 'Permintaan ' . $status . ' berhasil',
                'id' => $user->nip,
                'name' => $user->name,
                'jam' => $showTime
            ]);
        }

        if ($latestBarcodeScan && $latestBarcodeScan->barcode === $barcode) {
            $existingAttendance = Attendance::where('barcode_hour_came', $barcode)
                ->whereDate('hour_came', $timestamp->toDateString())
                ->first();

            if ($existingAttendance) {
                // Guru sudah melakukan absen
                if ($currentTime >= $endTime->subHour() || $currentTime <= $endTime->addHour()) {
                    $existingAttendance = Attendance::where('barcode_hour_came', $barcode)
                        ->whereDate('hour_came', $timestamp->toDateString())
                        ->first();

                    if (!$existingAttendance) {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Belum melakukan absen masuk.',
                            'id' => $user->nip,
                            'name' => $user->name,
                            'jam' => $showTime
                        ]);
                    }

                    $existingAttendance->barcode_home_time = $barcode;
                    $existingAttendance->home_time = $timestamp;
                    $existingAttendance->status = 'Hadir';
                    $existingAttendance->overtime_hours = $endTime->diffInHours($timestamp) > 1 ? $endTime->diffInHours($timestamp) - 1 : 0;
                    $existingAttendance->save();

                    event(new AttendanceSaved($existingAttendance));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Absen pulang.',
                        'id' => $user->nip,
                        'name' => $user->name,
                        'jam' => $showTime
                    ]);
                }
            } else {
                if ($currentTime <= $startTime->addHours(2)) {
                    $attendance = new Attendance();
                    $attendance->barcode_hour_came = $barcode;
                    $attendance->user_id = $user->id;
                    $attendance->hour_came = $timestamp;
                    $attendance->status = 'Hadir';
                    $attendance->save();

                    event(new AttendanceSaved($attendance));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Absen masuk.',
                        'id' => $user->nip,
                        'name' => $user->name,
                        'jam' => $showTime
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Barcode Tidak Valid.',
                'id' => $user->nip,
                'name' => $user->name,
                'jam' => $showTime
            ]);
        }


        // Jika tidak memenuhi syarat untuk absen masuk atau absen pulang
        return response()->json([
            'status' => 'success',
            'message' => 'Tidak memenuhi syarat untuk absen masuk atau absen pulang.',
            'id' => $user->nip,
            'name' => $user->name,
            'jam' => $showTime
        ]);
    }

    public function testabsen(Request $request)
    {
        $barcode = $request->barcode;
        $id = $request->user;
        $status = $request->status;

        $latestBarcodeScan = BarcodeScan::latest()->first();
        $user = User::find($id)->first();

        $timestamp = Carbon::parse($request->waktuSekarang); // 2023-06-15 15:59:00
        $showTime = $timestamp->format('H:i');
        $currentTime = $timestamp->format('H:i:s');

        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'ID Barcode tidak ditemukan.',
                'id' => '',
                'name' => '',
                'jam' => ''
            ]);
        }

        Carbon::setLocale('id');
        $created_at = Carbon::today();
        $today = Carbon::now()->isoFormat('dddd');
        $shift = Shift::where('day', $today)->first();

        if (!$shift) {
            return response()->json([
                'status' => 'success',
                'message' => 'Shift tidak ditemukan.'
            ]);
        }

        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        if (isset($status) && is_null($barcode)) {
            // Cari kehadiran untuk pengguna pada tanggal hari ini
            $attendance = Attendance::firstOrCreate([
                'user_id' => $user->id,
                'created_at' => $created_at,
            ], [
                'status' => $status,
            ]);

            if (!$attendance->wasRecentlyCreated) {
                // Jika kehadiran sudah ada, berarti telah dilakukan sebelumnya
                return response()->json([
                    'status' => 'error',
                    'message' => 'Permintaan ' . $status . ' hanya dapat dilakukan sekali sehari.',
                ]);
            }


            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->status = $status;
            $attendance->save();

            // Kode lanjutan jika kehadiran baru berhasil dibuat
            return response()->json([
                'status' => 'success',
                'message' => 'Permintaan ' . $status . ' berhasil',
                'id' => $user->nip,
                'name' => $user->name,
                'jam' => $showTime
            ]);
        }

        if ($latestBarcodeScan && $latestBarcodeScan->barcode === $barcode) {
            $existingAttendance = Attendance::where('barcode_hour_came', $barcode)
                ->whereDate('hour_came', $timestamp->toDateString())
                ->first();

            if ($existingAttendance) {
                // Guru sudah melakukan absen
                if ($currentTime >= $endTime->subHour() || $currentTime <= $endTime->addHour()) {
                    $existingAttendance = Attendance::where('barcode_hour_came', $barcode)
                        ->whereDate('hour_came', $timestamp->toDateString())
                        ->first();

                    if (!$existingAttendance) {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Belum melakukan absen masuk.',
                            'id' => $user->nip,
                            'name' => $user->name,
                            'jam' => $showTime
                        ]);
                    }

                    $existingAttendance->barcode_home_time = $barcode;
                    $existingAttendance->home_time = $timestamp;
                    $existingAttendance->status = 'Hadir';
                    $existingAttendance->overtime_hours = $endTime->diffInHours($timestamp) > 1 ? $endTime->diffInHours($timestamp) - 1 : 0;
                    $existingAttendance->save();

                    event(new AttendanceSaved($existingAttendance));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Absen pulang.',
                        'id' => $user->nip,
                        'name' => $user->name,
                        'jam' => $showTime
                    ]);
                }
            } else {
                if ($currentTime <= $startTime->addHours(2)) {
                    $attendance = new Attendance();
                    $attendance->barcode_hour_came = $barcode;
                    $attendance->user_id = $user->id;
                    $attendance->hour_came = $timestamp;
                    $attendance->status = 'Hadir';
                    $attendance->save();

                    event(new AttendanceSaved($attendance));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Absen masuk.',
                        'id' => $user->nip,
                        'name' => $user->name,
                        'jam' => $showTime
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Barcode Tidak Valid.',
                'id' => $user->nip,
                'name' => $user->name,
                'jam' => $showTime
            ]);
        }


        // Jika tidak memenuhi syarat untuk absen masuk atau absen pulang
        return response()->json([
            'status' => 'success',
            'message' => 'Tidak memenuhi syarat untuk absen masuk atau absen pulang.',
            'id' => $user->nip,
            'name' => $user->name,
            'jam' => $showTime
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();

            return response()->json([
                'status' => 'success',
                'id' => $user->id,
                'api_token' => $user->api_token,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    public function getAttendanceHistory(Request $request)
    {
        $userId = $request->user;
        $attendanceHistory = Attendance::where('user_id', $userId)->get();

        // return $attendanceHistory;
        $formattedAttendanceHistory = [];

        foreach ($attendanceHistory as $attendance) {
            $formattedAttendance = [
                'id' => $attendance->id,
                'name' => $attendance->user->name,
                'created_at' => $attendance->created_at->format('Y-m-d'),
                'hour_came' => optional($attendance->hour_came)->format('H:i:s'),
                'home_time' => optional($attendance->home_time)->format('H:i:s'),
                'status' => $attendance->status
            ];

            $formattedAttendanceHistory[] = $formattedAttendance;
        }

        return response()->json([
            'status' => 'success',
            'attendance_history' => $formattedAttendanceHistory
        ]);
    }
}