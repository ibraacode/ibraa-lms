<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Ambil semua role beserta jumlah permission-nya
        $roles = Role::withCount('permissions')->with('permissions')->latest()->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // Kelompokkan permission berdasarkan prefix (misal: "users", "grades", "system")
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => strtolower(str_replace(' ', '-', $request->name))]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role "' . $role->name . '" berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        // Cegah edit role super-admin (untuk keamanan)
        if ($role->name === 'super-admin') {
            return back()->withErrors(['error' => 'Role super-admin tidak dapat diubah melalui panel.']);
        }

        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('.', $perm->name)[0];
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->withErrors(['error' => 'Role super-admin tidak dapat diubah.']);
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($request->input('permissions', []));

        // Reset cache permission agar perubahan langsung berlaku
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Hak akses role "' . $role->name . '" berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin', 'guru', 'siswa'])) {
            return back()->withErrors(['error' => 'Role sistem tidak dapat dihapus.']);
        }
        
        $role->delete();
        return back()->with('success', 'Role berhasil dihapus.');
    }
}