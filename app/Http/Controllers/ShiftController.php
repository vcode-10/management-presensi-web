<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $shifts = Shift::where(function ($query) use ($search) {
            $query->where('shift_name', 'LIKE', "%$search%");
        })
            ->paginate(7);

        return view('shifts.index', [
            'shifts' => $shifts,
            'search' => $search,
        ]);
    }
    public function create()
    {
        $shifts = Shift::all();
        return view(
            'shifts.create',
            [
                'shifts' => $shifts,
            ]
        );
    }


    public function store(Request $request)
    {

        $request->validate([
            'shift_name' => 'required',
            'day' => [
                'required',
                Rule::notIn(Shift::pluck('day')->toArray()),
            ],
        ]);

        $shift = Shift::create([
            'shift_name' => $request->input('shift_name'),
            'day' => $request->input('day'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);


        return redirect()->route('shifts.index')
            ->with('success_message', 'Berhasil menambah user baru');
    }


    public function edit($id)
    {
        $shift = Shift::find($id);
        $shiftDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $selectedDays = explode(', ', $shift->day); // Memisahkan hari-hari dari kolom day berdasarkan koma dan spasi
        if (!$shift) return redirect()->route('shifts.index')
            ->with('error_message', 'Jadwal Shift dengan id' . $id . ' tidak ditemukan');

        return view('shifts.edit', [
            'shift' => $shift,
            'selectedDays' => $selectedDays,
            'shiftDays' => $shiftDays
        ]);
    }


    public function update(Request $request, $id)
    {
        $shift = Shift::find($id);

        $request->validate([
            'shift_name' => 'required',
        ]);

        $shift->shift_name = $request->input('shift_name');
        $shift->start_time = $request->input('start_time');
        $shift->end_time = $request->input('end_time');
        $shift->save();

        return redirect()->route('shifts.index')
            ->with('success_message', 'Berhasil mengubah Guru');
    }

    public function destroy(Request $request, $id)
    {
        $shift = Shift::find($id);
        $shift->delete();

        return redirect()->route('shifts.index')
            ->with('success_message', 'Berhasil menghapus Guru');
    }
}