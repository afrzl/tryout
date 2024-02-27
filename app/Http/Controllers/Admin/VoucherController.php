<?php

namespace App\Http\Controllers\Admin;

use App\Models\Voucher;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pakets = PaketUjian::orderBy('created_at')->get();

        return view('admin.voucher.index', compact('pakets'));
    }

    public function data()
    {
        $voucher = Voucher::with('paketUjian')
                    ->orderBy('created_at', 'asc')
                    ->where('himada_id', '=', NULL);

        return datatables()
            ->eloquent($voucher)
            ->addIndexColumn()
            ->addColumn('kode', function ($voucher)
            {
                return '<span class="badge badge-warning">'. $voucher->kode .'</span>';
            })
            ->addColumn('paket-ujian', function ($voucher)
            {
                return $voucher->paketUjian->nama;
            })
            ->addColumn('kuota', function ($voucher)
            {
                if ($voucher->himada_id == NULL) {
                    if ($voucher->kuota == 0) {
                        return '<span class="badge badge-danger">Habis</span>';
                    }
                    return '<span class="badge badge-success">'. $voucher->kuota .' tersisa</span>';
                } else {
                    return '-';
                }
            })
            ->addColumn('diskon', function($voucher) {
                return 'Rp' . number_format($voucher->diskon , 0 , ',' , '.' );
            })
            ->addColumn('aksi', function ($voucher) {
                return '
                    <button onclick="addKuota(`' .
                    route('admin.voucher.update', $voucher->id) .
                    '`)" type="button" class="btn btn-outline-success mr-2"><i class="fa fa-plus"></i></button>
                    <button onclick="deleteData(`' .
                    route('admin.voucher.destroy', $voucher->id) .
                    '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'kode', 'kuota'])
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
            'kode' => 'required|unique:voucher,kode,except,id|regex:/^\S*$/u',
            'diskon' => 'required|numeric',
            'kuota' => 'required|numeric',
            'paket' => 'required'
        ], [
            'kode.required' => 'Kolom kode tidak boleh kosong.',
            'kode.unique' => 'Kolom kode sudah digunakan.',
            'kode.regex' => 'Kolom kode tidak boleh mengandung spasi.',
            'diskon.required' => 'Kolom diskon tidak boleh kosong.',
            'diskon.numeric' => 'Kolom diskon harus diisi dengan angka.',
            'kuota.required' => 'Kolom kuota tidak boleh kosong.',
            'kuota.numeric' => 'Kolom kuota harus diisi dengan angka.',
            'paket.required' => 'Kolom paket ujian tidak boleh kosong.',
        ]);

        $voucher = Voucher::create([
            'kode' => $request->kode,
            'diskon' => $request->diskon,
            'kuota' => $request->kuota,
            'paket_ujian_id' => $request->paket,
        ]);

        return response()->json('Berhasil menambahkan voucher', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);

        return response()->json($voucher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'tambah' => 'required|min:0',
        ]);

        $voucher = Voucher::findOrFail($id);

        $voucher->kuota = $voucher->kuota + $request->tambah;
        $voucher->update();

        return response()->json('Voucher berhasil ditambah', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response(null, 204);
    }
}
