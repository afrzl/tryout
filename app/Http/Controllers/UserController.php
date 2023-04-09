<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $users = User::orderBy('id', 'desc')->get();

        return datatables()
            ->of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($users)
            {
                if ($users->id === Auth()->user()->id) {
                    return '<span class="badge badge-danger">Logged in!</span>';
                } else {
                    return ($users->hasRole('admin') ?
                    '<button onclick="makeAdmin(`' .
                            route('user.makeAdmin', ['action' => 'revoke', 'id' => $users->id]) .
                            '`)" type="button" data-action="revoke" class="btn btn-outline-danger"><i class="fa fa-minus-circle"></i> Revoke Admin</button>' :
                    '<button onclick="makeAdmin(`' .
                            route('user.makeAdmin', ['action' => 'make', 'id' => $users->id]) .
                            '`)" type="button" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Make Admin</button>');
                }
            })
            ->addColumn('aksi', function ($users) {
                if ($users->id === Auth()->user()->id) {
                    return '<span class="badge badge-danger">Logged in!</span>';
                }
                else {
                    return '
                        <button onclick="deleteData(`' .
                        route('user.destroy', $users->id) .
                        '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                        <button onclick="showDetail(`' .
                        route('user.show', $users->id) .
                        '`)" type="button" class="btn btn-outline-info"><i class="fa fa-eye"></i></button>
                        <button onclick="resetPassword(`' .
                        route('user.resetpassword', $users->id) .
                        '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-key"></i></button>
                    ';
                }
            })
            ->rawColumns(['aksi', 'role'])
            ->make(true);
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
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
        $user->delete();

        return response(null, 204);
    }

    public function makeAdmin($action, $id)
    {
        $user = User::find($id);
        $action == 'make' ? $user->assignRole('admin') : $user->removeRole('admin');

        return response()->json('Data berhasil disimpan', 200);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user = User::find($id);
        $user->password = $request->password;
        $user->update();

        return response()->json('Data berhasil disimpan', 200);
    }
}
