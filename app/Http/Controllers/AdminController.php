<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'Admin')->get();
        return view('admins.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username|regex:/^\S*$/',

            // 'password' => 'required|confirmed'
        ]);;
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => 123456789,
            'role' => 'Admin',
        ]);
        return redirect()->route('admins.index')
            ->with('success_message', 'Berhasil menambah Admin baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) return redirect()->route('admins.index')
            ->with('error_message', 'User dengan id' . $id . ' tidak ditemukan');
        return view('admins.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|regex:/^\S*$/|unique:users,username,' . $id,
            'password' => 'sometimes|nullable|confirmed'
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password) $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('admins.index')
            ->with('success_message', 'Berhasil mengubah Admin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);
        if ($id == $request->user()->id) return redirect()->route('admins.index')
            ->with('error_message', 'Anda tidak dapat menghapus diri sendiri.');
        if ($user) $user->delete();
        return redirect()->route('admins.index')
            ->with('success_message', 'Berhasil menghapus Admin');
    }
}
