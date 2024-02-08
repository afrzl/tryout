<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersDetail;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.user.index');
    }

    public function data()
    {
        $users = User::with('roles')->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($users)
            ->addIndexColumn()
            ->addColumn('role', function ($users)
            {
                if ($users->id === Auth()->user()->id) {
                    return '<span class="badge badge-danger">Logged in!</span>';
                } else {
                    return ($users->hasRole('admin') ?
                    '<button onclick="makeAdmin(`' .
                            route('user.makeAdmin', ['action' => 'revoke', 'id' => $users->id]) .
                            '`)" type="button" data-action="revoke" class="btn btn-outline-danger"><i class="fa fa-minus-circle"></i> Revoke Admin</button>'
                    :
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
    public function show()
    {
        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);

        // $sumber = json_decode($user->usersDetail->sumber_informasi);
        // return $sumber;
        // return $user->usersDetail;
        return view('profile', compact('user'));
    }

    public function account(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'sumber' => 'required',
        ]);

        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);
        $user->name = $request->name;
        $user->status = 2;
        $user->update();

        if ($user->usersDetail != NULL) {
            $usersDetail = UsersDetail::findOrFail($user->id);
            $usersDetail->no_hp = $request->no_hp;
            $usersDetail->sumber_informasi = $request->sumber;
            $usersDetail->update();
        } else {
            $usersDetail = new UsersDetail();
            $usersDetail->id = $user->id;
            $usersDetail->no_hp = $request->no_hp;
            $usersDetail->sumber_informasi = $request->sumber;
            $usersDetail->save();
        }

        return response()->json(['message' => 'Profil berhasil diupdate.', 'data' => $user], 200);
    }

    public function peserta(Request $request) {
        $request->validate([
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'asal_sekolah' => 'required',
            'instagram' => 'required',
        ]);

        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);
        $user->status = 3;
        $user->update();

        if ($user->usersDetail != NULL) {
            $usersDetail = UsersDetail::findOrFail($user->id);
            $usersDetail->provinsi = $request->provinsi;
            $usersDetail->kabupaten = $request->kabupaten;
            $usersDetail->kecamatan = $request->kecamatan;
            $usersDetail->asal_sekolah = $request->asal_sekolah;
            $usersDetail->instagram = $request->instagram;
            $usersDetail->update();
        } else {
            $usersDetail = UsersDetail::create([
                'id' => $user->id,
                'provinsi' => $request->provinsi,
                'kabupaten' => $request->kabupaten,
                'kecamatan' => $request->kecamatan,
                'asal_sekolah' => $request->asal_sekolah,
                'instagram' => $request->instagram,
            ]);
        }

        return response()->json(['message' => 'Profil berhasil diupdate.', 'data' => $user], 200);
    }

    public function pendaftar(Request $request) {
        $request->validate([
            'prodi' => 'required',
            'formasi' => 'required',
        ]);

        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);
        $user->status = 1;
        $user->update();

        if ($user->usersDetail != NULL) {
            $usersDetail = UsersDetail::findOrFail($user->id);
            $usersDetail->prodi = $request->prodi;
            $usersDetail->penempatan = $request->formasi;
            $usersDetail->update();
        } else {
            $usersDetail = UsersDetail::create([
                'id' => $user->id,
                'prodi' => $request->prodi,
                'penempatan' => $request->formasi,
            ]);
        }

        return response()->json(['message' => 'Profil berhasil diupdate.', 'data' => $user], 200);
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
