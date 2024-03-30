<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pembelian;
use App\Models\UsersDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BiusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bius.index');
    }

    public function data()
    {
        $pembelian = Pembelian::with('user', 'user.usersDetail')
                ->where('paket_id', '0df8c9b0-d352-448b-9611-abadffc4f46d')
                ->where('status', 'Sukses')
                ->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($pembelian)
            ->addIndexColumn()
            ->addColumn('name', fn($pembelian) => $pembelian->user->name)
            ->addColumn('email', fn($pembelian) => $pembelian->user->email)
            ->addColumn('no_hp', fn($pembelian) => $pembelian->user->usersDetail->no_hp)
            ->addColumn('kelompok', function ($pembelian) {
                return '
                    <select name="kelompok[]" onChange="storeKelompok(`'. $pembelian->user->id .'`)" id="kel-'. $pembelian->user->id .'" class="form-control input-kelompok">
                        <option value="">-- Pilih Nama Kelompok--</option>
                        <option value="Poisson" '. ($pembelian->user->usersDetail->nama_kelompok == "Poisson" ? "selected" : "") .'>Poisson</option>
                        <option value="Bernoulli" '. ($pembelian->user->usersDetail->nama_kelompok == "Bernoulli" ? "selected" : "") .'>Bernoulli</option>
                        <option value="Binomial" '. ($pembelian->user->usersDetail->nama_kelompok == "Binomial" ? "selected" : "") .'>Binomial</option>
                    </select>';
            })
            ->rawColumns(['kelompok'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required',
            'kelompok' => 'required',
        ]);

        $userDetail = UsersDetail::findOrFail($request->id);
        $userDetail->nama_kelompok = $request->kelompok;
        $userDetail->update();

        return response()->json('Berhasil menambahkan kelompok', 200);
    }
}
