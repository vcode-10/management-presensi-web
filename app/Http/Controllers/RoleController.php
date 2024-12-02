<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
            ->paginate(5);

        return view('roles.index', [
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        $roles = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        Alert::success('Success', 'Data Jabatan baru berhasil disimpan');
        return redirect()->route('roles.index');
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
        $role = Role::find($id);

        if (!$role) {
            Alert::error('Error', 'Jabatan dengan ID ' . $id . ' tidak ditemukan');
            return redirect()->route('roles.index');
        }

        return view('roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $role = Role::find($id);

        if (!$role) {
            Alert::error('Error', 'Jabatan dengan ID ' . $id . ' tidak ditemukan');
            return redirect()->route('roles.index');
        }

        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->save();

        Alert::success('Success', 'Data Jabatan berhasil disimpan');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            Alert::error('Error', 'Jabatan not found');
            return redirect()->route('roles.index');
        }

        $role->delete();

        Alert::success('Success', 'Jabatan berhasil dihapus');
        return redirect()->route('roles.index');
    }
}
