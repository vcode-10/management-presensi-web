<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\BarcodeScan;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Exports\AttendanceExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancePerUserExport;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $filterBulan = $request->input('filter_bulan', Carbon::now()->month);
        // $filterBulan = 5;
        Carbon::setLocale('id');
        $today = Carbon::now()->isoFormat('dddd');
        $shift = Shift::where('day', $today)->first();
        $users = User::all();

        $dataGuru = [];

        foreach ($users as $user) {
            $totalSakit = Attendance::where('user_id', $user->id)
                ->where('status', 'Sakit')
                ->whereYear('created_at', '=', Carbon::now()->year)
                ->whereMonth('created_at', '=', $filterBulan)
                ->count();

            $totalIzin = Attendance::where('user_id', $user->id)
                ->where('status', 'Izin')
                ->whereYear('created_at', '=', Carbon::now()->year)
                ->whereMonth('created_at', '=', $filterBulan)
                ->count();

            $totalHadir = Attendance::where('user_id', $user->id)
                ->where('status', 'Hadir')
                ->whereYear('created_at', '=', Carbon::now()->year)
                ->whereMonth('created_at', '=', $filterBulan)
                ->count();

            $totalAlpa = Attendance::where('user_id', $user->id)
                ->where('status', 'Alpa')
                ->whereYear('created_at', '=', Carbon::now()->year)
                ->whereMonth('created_at', '=', $filterBulan)
                ->count();


            $dataGuru[] = [
                'nama' => $user->name,
                'nip' => $user->nip,
                'totalSakit' => $totalSakit,
                'totalIzin' => $totalIzin,
                'totalHadir' => $totalHadir,
                'totalAlpa' => $totalAlpa,
            ];
        }

        return view('presensi.index', [
            'dataGuru' => $dataGuru,
            'shift' => $shift,
        ]);
    }
    public function exportToExcel(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        return Excel::download(new AttendanceExport($startDate, $endDate), 'attendance.xlsx');
    }

    public function exportAttendancePerUser(Request $request)
    {
        $userId = $request->id; // Ganti dengan ID user yang diinginkan
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        return Excel::download(new AttendancePerUserExport($userId, $startDate, $endDate), 'attendance_per_user.xlsx');
    }

    public function create()
    {
        $barcode = BarcodeScan::latest()->first();
        Carbon::setLocale('id');
        $today = Carbon::now()->isoFormat('dddd');
        $shift = Shift::where('day', $today)->first();

        return view('presensi.create', [
            'barcode' => $barcode,
            'shift' => $shift
        ]);
    }
    public function store(Request $request)
    {
        if (!$request->has('barcode')) {
            return redirect()->route('presensi.index')
                ->with('error_message', 'Barcode field is required');
        }

        $barcodeValue = Uuid::uuid4()->toString();

        $barcode = new BarcodeScan();
        $barcode->barcode = $barcodeValue;
        $barcode->save();

        return redirect()->route('presensi.index')->with('success', 'Barcode Presensi Hari ini telah dibuat');
    }
}