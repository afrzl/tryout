<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.voucher.index');
    }

    public function data()
    {
        $voucher = Voucher::orderBy('created_at', 'asc')
                    ->where('himada_id', '=', NULL);

        return datatables()
            ->eloquent($voucher)
            ->addIndexColumn()
            ->addColumn('kode', function ($voucher)
            {
                return '<span class="badge badge-warning">'. $voucher->kode .'</span>';
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
            'kuota' => 'required|numeric'
        ], [
            'kode.required' => 'Kolom kode tidak boleh kosong.',
            'kode.unique' => 'Kolom kode sudah digunakan.',
            'kode.regex' => 'Kolom kode tidak boleh mengandung spasi.',
            'diskon.required' => 'Kolom diskon tidak boleh kosong.',
            'diskon.numeric' => 'Kolom diskon harus diisi dengan angka.',
            'kuota.required' => 'Kolom kuota tidak boleh kosong.',
            'kuota.numeric' => 'Kolom kuota harus diisi dengan angka.',
        ]);

        $voucher = Voucher::create([
            'kode' => $request->kode,
            'diskon' => $request->diskon,
            'kuota' => $request->kuota,
        ]);

        return response()->json('Berhasil menambahkan voucher', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
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
    public function update(Request $request, Voucher $voucher)
    {
        //
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
