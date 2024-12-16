<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Permission::class, 'permission');
    }

    public function index(Request $request)
    {
        $permissions = Permission::where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        })->orderBy('name')->paginate(10);
        return view('permission.permission_index', compact('permissions'));
    }

    public function create()
    {
        return view('permission.permission_create');
    }

    public function store(PermissionStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            Permission::create(['name' => strtoupper($request->name), 'description' => $request->description]);

            DB::commit();

            return redirect(route("permission.index"))
                ->with("response-message", "Create permission berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("permission.index"))
                ->with("response-message", "Create permission gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function edit(Permission $permission)
    {
        return view('permission.permission_edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->name = strtoupper($request->name);
            $permission->description = $request->description;
            $permission->save();

            DB::commit();

            return redirect(route("permission.index"))
                ->with("response-message", "Update permission berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("permission.index"))
                ->with("response-message", "Update permission gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function destroy(Permission $permission)
    {
        DB::beginTransaction();
        try {
            $permission->delete();

            DB::commit();

            return redirect(route("permission.index"))
                ->with("response-message", "Delete permission berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("permission.index"))
                ->with("response-message", "Delete permission gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
