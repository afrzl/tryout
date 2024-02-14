<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HimadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.himada.index');
    }

    public function getUser(Request $request)
    {
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

    public function data()
    {
        $users = User::with('roles', 'voucher')
                ->orderBy('created_at', 'asc')
                ->role('himada');

        return datatables()
            ->eloquent($users)
            ->addIndexColumn()
            ->addColumn('role', function ($users)
            {
                return '<span class="badge badge-warning">'. $users->voucher->kode .'</span>';
            })
            ->addColumn('aksi', function ($users) {
                return '
                    <button onclick="deleteData(`' .
                    route('admin.himada.destroy', $users->id) .
                    '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                ';
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
        $validator = $request->validate([
            'user' => 'required',
            'kode' => 'required|unique:voucher,kode,except,id',
        ], [
            'user.required' => 'Kolom email user tidak boleh kosong.',
            'kode.required' => 'Kolom kode tidak boleh kosong.',
            'kode.unique' => 'Kolom kode sudah digunakan.',
        ]);

        $user = User::findOrFail($request->user);
        $user->assignRole('himada');

        $voucher = Voucher::create([
            'kode' => $request->kode,
            'diskon' => 0,
            'himada_id' => $user->id,
            'is_active' => true
        ]);

        return response()->json('Berhasil menambahkan himada', 200);
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
        $user = $user->removeRole('himada');

        $voucher = Voucher::where('himada_id', $id)->first();
        $voucher = $voucher->delete();

        return response(null, 204);
    }
}
