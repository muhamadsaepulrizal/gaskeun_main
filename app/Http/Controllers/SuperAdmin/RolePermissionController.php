<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        
        return view('superadmin.roles.index', compact('roles'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
            'base_role' => 'required|string|in:Super Admin,Disperindag,Agen LPG,Pangkalan LPG,Pengawas,Publik'
        ]);
        
        $role = Role::create([
            'name' => $request->name,
            'base_role' => $request->base_role
        ]);
        
        activity()->performedOn($role)->log('Created role');
        
        return redirect()->back()->with('success', 'Role berhasil dibuat.');
    }
}
