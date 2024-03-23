<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UsersDetail;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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
        $users = User::with('usersDetail')
                ->doesntHave('roles')
                ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($users)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return '<a href="javascript:void(0);" onclick="detailForm(`' . route('user.showDetails', $user->id) . '`)">' . $user->name;
            })
            ->addColumn('no_hp', function ($user) {
                return $user->usersDetail ? $user->usersDetail->no_hp : '-';
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
                        <button onclick="resetPassword(`' .
                        route('user.resetpassword', $users->id) .
                        '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-key"></i></button>
                    ';
                }
            })
            ->rawColumns(['name', 'aksi'])
            ->make(true);
    }

    public function export() {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function showDetails($id) {
        $user = User::with('usersDetail', 'sessions')->find($id);

        foreach ($user->sessions as $session) {
            $session->last_activity = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
        }

        return response()->json($user);
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'email_verified_at' => Carbon::now()
        ]);

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);

        return view('profile', compact('user'));
    }

    public function account(Request $request) {
        $user = User::with('usersDetail')->findOrFail(auth()->user()->id);
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'sumber' => 'required',
        ];
        $notification = [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'no_hp.required' => 'No. HP tidak boleh kosong.',
            'no_hp.regex' => 'Format No. HP harus sesuai.',
            'sumber.required' => 'Sumber mendapatkan tryout tidak boleh kosong.',
        ];
        if ($user->profile_photo_path == NULL || $request->image != NULL) {
            $rules['image'] = 'required|mimes:jpeg,png,jpg|max:2048';
            $notification['image.required'] = 'Foto tidak boleh kosong.';
            $notification['image.max'] = 'Ukuran foto maksimal 2 MB.';
            $notification['image.mimes'] = 'Foto harus berekstensi .jpeg atau .png.';
        }
        $validator = Validator::make($request->all(), $rules, $notification);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all(),
            ]);
        }

        if ($request->image != NULL) {
            if ($files = $request->file('image')) {
                $fileName =  "image-" . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->storeAs('public/photo-profile', $fileName);

                $user->profile_photo_path = 'photo-profile/' . $fileName;
                $user->name = $request->name;
                $user->status = 2;
                $user->update();
            }
        }

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
