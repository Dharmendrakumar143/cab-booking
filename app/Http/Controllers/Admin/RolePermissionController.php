<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends AdminBaseController
{
    public function index()
    {
        $roles = Role::whereIn('name', ['independent-contractors', 'employees'])
            ->orderBy('id', 'asc') // Ascending order (Oldest first)
            ->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function edit($role_id)
    {
        $role = Role::find($role_id);
        $permissions =  Permission::where('guard_name', 'admin')->get();

        // echo "<pre>role==";
        // print_r($role);
        // die;
        return view('admin.roles.edit', compact('role', 'permissions'));
    }


    public function update(Request $request)
    {   
        $role_id = $request->role_id;
        
        $role = Role::find($role_id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);
        // $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions);

        $request->session()->flash('success', 'Permission assigned successfully.');
        return redirect()->route('roles');
    }


}
