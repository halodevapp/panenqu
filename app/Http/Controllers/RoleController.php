<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(Request $request)
    {
        $roles = Role::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%");
        })->paginate(10);
        return view('role.role_index', compact('roles'));
    }

    public function create()
    {
        return view('role.role_create');
    }

    public function store(RoleStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Role::create(['name' => strtoupper($request->name)]);

            DB::commit();

            return redirect(route("role.index"))
                ->with("response-message", "Create role berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("role.index"))
                ->with("response-message", "Create role gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('role.role_edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $role->name =  strtoupper($request->name);
            $role->save();

            DB::commit();

            return redirect(route("role.index"))
                ->with("response-message", "Update role berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("role.index"))
                ->with("response-message", "Update role gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();

            return redirect(route("role.index"))
                ->with("response-message", "Delete role berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("role.index"))
                ->with("response-message", "Delete role gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function getRolePermissions(Request $request)
    {
        $role = Role::findById($request->role_id);
        $roleHasPermission = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all();
        $permissions = $permissions->map(function ($value) use ($roleHasPermission) {
            $value->toArray();

            $value['has'] = in_array($value['name'], $roleHasPermission) ? true : false;

            return $value;
        });

        return response()->json([
            'data' => $permissions
        ]);
    }

    public function syncRolePermissions(Request $request)
    {
        $role = Role::findById($request->role_id);

        DB::beginTransaction();
        try {
            $role->syncPermissions($request->permissions);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses sync permissions'
            ]);
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync permissions'
            ]);
        }
        DB::rollBack();
    }

    public function getPermissionsByRole(Request $request)
    {
        $roles = Role::findById($request->role_id);
        return response()->json([
            'data' => $roles->permissions
        ]);
    }
}
