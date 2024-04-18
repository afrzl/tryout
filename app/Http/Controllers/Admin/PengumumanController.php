<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pengumuman.index');
    }

    public function data() {
        $pengumumans = Pengumuman::with('user', 'paket')->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($pengumumans)
            ->addIndexColumn()
            ->addColumn('author', function ($pengumuman)
            {
                return $pengumuman->user->name;
            })
            ->addColumn('paket', function ($pengumuman)
            {
                if ($pengumuman->paket_id == null) {
                    return 'Semua';
                } else {
                    return $pengumuman->paketUjian->name;
                }
            })
            ->addColumn('aksi', function ($pakets) {
                if ($pakets->id == '0df8c9b0-d352-448b-9611-abadffc4f46d' || $pakets->id == '33370256-b734-470a-afe9-c7ca8421f1b3' ||$pakets->id == '981ae5b5-a48d-47e6-9cc7-9e79994a3ef0' || $pakets->id == '0be570c6-7edf-4970-bd99-304d0626f9ff' || $pakets->id == 'd5f57505-fb5a-4f59-a301-3722ef581844') {
                    return '<button onclick="editData(`' . route('admin.paket.update', $pakets->id) . '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></button>';
                }

                return '<button onclick="editData(`' . route('admin.paket.update', $pakets->id) . '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></button>
                        <button onclick="deleteData(`' . route('admin.paket.destroy', $pakets->id) . '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengumuman = new Pengumuman();
        $action = 'admin.pengumuman.store';

        return view('admin.pengumuman.form', compact('pengumuman', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        //
    }
}
