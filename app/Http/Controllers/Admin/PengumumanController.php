<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaketUjian;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        $pengumumans = Pengumuman::with('user', 'paketUjian')->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($pengumumans)
            ->addIndexColumn()
            ->addColumn('file', function ($pengumuman)
            {
                if ($pengumuman->file) {
                    return '<a target="_blank" href="'. asset('storage/pengumuman/' . $pengumuman->file) .'">Lihat File</a>';
                }
            })
            ->addColumn('author', function ($pengumuman)
            {
                return $pengumuman->user->name;
            })
            ->addColumn('paket', function ($pengumuman)
            {
                if ($pengumuman->paket_id == null) {
                    return 'Semua';
                } else {
                    return $pengumuman->paketUjian->nama;
                }
            })
            ->addColumn('aksi', function ($pengumuman) {
                return '<a href="'. route('admin.pengumuman.edit', $pengumuman->id) .'" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                        <button onclick="deleteData(`' . route('admin.pengumuman.destroy', $pengumuman->id) . '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>';
            })
            ->rawColumns(['file', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengumuman = new Pengumuman();
        $pakets = PaketUjian::orderBy('nama')->get();
        $action = route('admin.pengumuman.store');

        return view('admin.pengumuman.form', compact('pengumuman', 'pakets', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'file' => 'file|max:2048',
        ], [
            'title.required' => 'Judul tidak boleh kosong.',
            'content.required' => 'Isi tidak boleh kosong.',
        ]);

        $pengumuman = new Pengumuman();
        $pengumuman->title = $request->title;
        $pengumuman->content = $request->content;
        $pengumuman->author_id = auth()->id();
        $pengumuman->paket_id = $request->tujuan;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file->storeAs('public/pengumuman', $file->getClientOriginalName());
            $pengumuman->file = $file->getClientOriginalName();
        }

        $pengumuman->save();

        return redirect()->route('admin.pengumuman.index')->with('message','Data berhasil disimpan');
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
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $action = route('admin.pengumuman.update', $pengumuman->id);
        $pakets = PaketUjian::orderBy('nama')->get();
        return view('admin.pengumuman.form', compact('pengumuman', 'action', 'pakets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'file' => 'file|max:2048',
        ], [
            'title.required' => 'Judul tidak boleh kosong.',
            'content.required' => 'Isi tidak boleh kosong.',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->title = $request->title;
        $pengumuman->content = $request->content;
        $pengumuman->author_id = auth()->id();
        $pengumuman->paket_id = $request->tujuan;

        if ($request->hasFile('file')) {
            Storage::delete('public/pengumuman/'.basename($pengumuman->file));

            $file = $request->file('file');
            $file->storeAs('public/pengumuman', $file->getClientOriginalName());
            $pengumuman->file = $file->getClientOriginalName();
        }

        $pengumuman->update();

        return redirect()->route('admin.pengumuman.index')->with('message','Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        Storage::delete('public/pengumuman/'.basename($pengumuman->file));
        $pengumuman->delete();

        return response(null, 204);
    }
}
