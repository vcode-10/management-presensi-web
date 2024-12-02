<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('role')
            ->where(function ($query) use ($search) {
                $query->where('nip', 'LIKE', "%$search%")
                    ->orWhere('name', 'LIKE', "%$search%");
            })
            ->paginate(10);

        return view('users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nip' => 'required|numeric|unique:users',
            'username' => ['required', 'unique:users', 'regex:/^\S*$/'],
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'role_id' => 'required',
            'photo' => 'image',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : bcrypt('123456789'),
            'gender' => $request->gender,
            'role_id' => $request->role_id,
            'active' => 1,
            'photo' => null,
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $user->photo = $photoPath;
            $user->save();
        }

        Alert::success('Success', 'Data Pengguna baru berhasil disimpan');
        return redirect()->route('users.index');
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
        $roles = Role::all();

        if (!$user) {
            Alert::error('Error', 'User dengan ID ' . $id . ' tidak ditemukan');
            return redirect()->route('users.index');
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:users,nip,' . $id,
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'gender' => 'required',
            'role_id' => 'required',
            'photo' => 'image',
        ]);

        $user = User::find($id);

        if (!$user) {
            Alert::error('Error', 'User dengan ID ' . $id . ' tidak ditemukan');
            return redirect()->route('users.index');
        }

        $user->name = $request->name;
        $user->nip = $request->nip;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->role_id = $request->role_id;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        Alert::success('Success', 'Berhasil mengubah user');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            Alert::error('Error', 'User not found');
            return redirect()->route('users.index');
        }

        if ($id == $request->user()->id) {
            Alert::error('Error', 'Anda tidak dapat menghapus diri sendiri.')->autoClose(false);
            return redirect()->route('users.index');
        }

        $user->delete();

        Alert::success('Success', 'Berhasil menghapus user');
        return redirect()->route('users.index');
    }

    public function toggleActiveStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            Alert::error('Error', 'User not found');
            return redirect()->route('users.index');
        }

        if ($user->id == Auth::user()->id) {
            Alert::error('Error', 'You cannot change your own active status')->autoClose(false);
            return redirect()->route('users.index');
        }

        $user->active = $user->active == 1 ? 0 : 1;
        $user->save();

        Alert::success('Success', 'User active status toggled successfully');
        return redirect()->route('users.index');
    }

    public function resetPassword($id)
    {
        $user = User::find($id);

        if (!$user) {
            Alert::error('Error', 'User not found');
            return redirect()->route('users.index');
        }

        if ($user->id == Auth::user()->id) {
            Alert::error('Error', 'You cannot change your own reset password')->autoClose(false);
            return redirect()->route('users.index');
        }

        $user->password = bcrypt('123456789');
        $user->save();
        Alert::success('Success', 'User reset password successfully');
        return redirect()->route('users.index');
    }
}
