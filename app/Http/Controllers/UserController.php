<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where(function ($query) use ($request) {
            $query->where('email', 'like', "%{$request->search}%")
                ->orWhere('name', 'like', "%{$request->search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('user.user_index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.user_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'email'    => $request->email,
                'name'     => $request->name,
                'password' => Hash::make($request->password),
                'created_by' => Auth::user()->id
            ];

            User::create($data);

            DB::commit();

            return redirect(route("user.index"))
                ->with("response-message", "Create user berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("user.index"))
                ->with("response-message", "Create user gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.user_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        DB::beginTransaction();

        try {
            $user->name = $request->name;
            $user->is_active = $request->has('is_active') ? User::USER_ACTIVE : User::USER_INACTIVE;
            $user->password = $request->filled('password') ? Hash::make($request->password) : $user->password;
            $user->updated_by = Auth::user()->id;
            $user->save();

            DB::commit();

            return redirect(route("user.index"))
                ->with("response-message", "Update user berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("user.index"))
                ->with("response-message", "Update user gagal")
                ->with("response-status", "error");
        }

        DB::rollBack();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            $user->deleted_by = Auth::user()->id;
            $user->deleted_at = Carbon::now();
            $user->save();

            DB::commit();

            return redirect(route("user.index"))
                ->with("response-message", "Delete user berhasil")
                ->with("response-status", "success");
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return redirect(route("user.index"))
                ->with("response-message", "Delete user gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function userRoles(Request $request)
    {
        $roles = Role::all();
        $user = User::find($request->user_id);

        $userHasRoles = $user->getRoleNames()->toArray();

        $roles = $roles->map(function ($value) use ($userHasRoles) {
            $value->toArray();

            $value['has'] = in_array($value['name'], $userHasRoles) ? true : false;

            return $value;
        });

        return response()->json([
            'data' => $roles
        ]);
    }

    public function syncUserRoles(Request $request)
    {
        $user = User::find($request->user_id);

        DB::beginTransaction();
        try {
            $user->syncRoles($request->roles);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses sync user roles'
            ]);
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync user roles'
            ]);
        }
        DB::rollBack();
    }

    public function syncUserPermissions(Request $request)
    {
        $user = User::find($request->user_id);

        DB::beginTransaction();
        try {
            $user->syncPermissions($request->permissions);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses sync user permissions'
            ]);
        } catch (Exception $exception) {
            report($exception);

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync user permissions'
            ]);
        }
        DB::rollBack();
    }

    public function userPermissions(Request $request)
    {
        $permissions = Permission::all();
        $user = User::find($request->user_id);

        $userHasPermissions = $user->getPermissionNames()->toArray();

        $permissions = $permissions->map(function ($value) use ($userHasPermissions) {
            $value->toArray();

            $value['has'] = in_array($value['name'], $userHasPermissions) ? true : false;

            return $value;
        });

        return response()->json([
            'data' => $permissions
        ]);
    }
}
