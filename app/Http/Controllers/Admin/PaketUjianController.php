<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ujian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaketUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ujians = Ujian::get();
        return view('admin.paket_ujian.index', compact('ujians'));
    }

    public function data()
    {
        $pakets = PaketUjian::with('ujian')->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($pakets)
            ->addIndexColumn()
            ->addColumn('harga', function ($pakets)
            {
                return 'Rp' . number_format($pakets->harga , 0 , ',' , '.' );
            })
            ->addColumn('waktu_pendaftaran', function ($pakets)
            {
                return Carbon::parse($pakets->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($pakets->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('ujian', function ($pakets)
            {
                $ujians = '';
                foreach ($pakets->ujian as $ujian) {
                    $ujians .= '<li><a href="'. route('admin.ujian.soal.index', $ujian->id) .'">'. $ujian->nama .'</a>   </li>';
                }
                return $ujians;
            })
            ->addColumn('aksi', function ($pakets) {
                $text = '<button onclick="editData(`' . route('admin.paket.update', $pakets->id) . '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></button>
                        <button onclick="deleteData(`' . route('admin.paket.destroy', $pakets->id) . '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>';
                return $text;
            })
            ->rawColumns(['harga', 'waktu_pendaftaran', 'ujian', 'aksi'])
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
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
        ]);

        $paketUjian = new PaketUjian();
        $paketUjian->nama = $request->nama;
        $paketUjian->deskripsi = $request->deskripsi;
        $paketUjian->harga = $request->harga;
        $paketUjian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $paketUjian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $paketUjian->save();

        $paketUjian->ujian()->attach($request->ujians);

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paketUjian = PaketUjian::with('ujian')->find($id);

        return response()->json($paketUjian);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaketUjian $paketUjian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
        ]);

        $paketUjian = PaketUjian::find($id);

        $paketUjian->nama = $request->nama;
        $paketUjian->deskripsi = $request->deskripsi;
        $paketUjian->harga = $request->harga;
        $paketUjian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $paketUjian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $paketUjian->update();

        $paketUjian->ujian()->sync($request->ujians);

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paketUjian = PaketUjian::find($id);
        $paketUjian->delete();

        $paketUjian->ujian()->detach();

        return response(null, 204);
    }
}
