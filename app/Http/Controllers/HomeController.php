<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $admin = User::where('role_id', 1)->count();
        $guru = User::whereNotIn('role_id', [1])->get()->count();


        $users = User::all();
        $jumlahSudahAda = 0;
        $belumAbsenCount = 0;

        foreach ($users as $user) {
            $sudahAbsen = Attendance::where('user_id', $user->id)->count();
            $belumAbsen = Attendance::where('user_id', $user->id)->whereNull('status')->count();

            if ($belumAbsen === 0) {
                $belumAbsenCount++;
            }

            if ($sudahAbsen > 0) {
                $jumlahSudahAda++;
            }
        }

        // dd($belumAbsenCount);

        return view('home', [
            'admin' => $admin,
            'guru' => $guru,
            'belumAbsenCount' => $belumAbsenCount,
            'jumlahSudahAda' => $jumlahSudahAda,
        ]);
    }
}