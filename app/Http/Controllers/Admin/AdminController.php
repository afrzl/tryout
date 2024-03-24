<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.admin.index');
    }

    public function data() {
        $users = User::with('roles')
                ->role(['admin', 'panitia', 'bendahara'])
                ->orderBy('name', 'asc');

        return datatables()
            ->eloquent($users)
            ->addIndexColumn()
            ->addColumn('no_hp', function ($user) {
                return $user->usersDetail ? $user->usersDetail->no_hp : '-';
            })
            ->addColumn('roles', function ($user) {
                return '<span class="badge badge-success">'. $user->roles->pluck('name')[0] .'</span>';
            })
            ->addColumn('aksi', function ($users) {
                if ($users->id === Auth()->user()->id) {
                    return '<span class="badge badge-danger">Logged in!</span>';
                }
                else {
                    return '
                        <button onclick="deleteData(`' .
                        route('admin.admin.destroy', $users->id) .
                        '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                        <button onclick="resetPassword(`' .
                        route('admin.admin.resetpassword', $users->id) .
                        '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-key"></i></button>
                    ';
                }
            })
            ->rawColumns(['roles', 'aksi'])
            ->make(true);
    }

    public function getUser(Request $request) {
        $users = User::orderBy('email', 'ASC')
                    ->select('id', 'email')
                    ->with('roles')
                    ->where('email', 'like', '%'.$request->search.'%')
                    ->whereDoesntHave('roles')
                    ->limit(3)
                    ->get();

        $response = array();
        foreach ($users as $user) {
            $response[] = array(
                "id" => $user->id,
                "text" => $user->email
            );
        }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'user' => 'required',
            'roles' => 'required',
        ], [
            'user.required' => 'Kolom email user tidak boleh kosong.',
            'roles.required' => 'Kolom roles tidak boleh kosong.',
        ]);

        $user = User::findOrFail($request->user);
        $user->assignRole($request->roles);

        return response()->json('Berhasil menambahkan admin', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user = $user->roles()->detach();

        return response(null, 204);
    }
}
