<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ujian.index');
    }

    public function data()
    {
        $ujians = Ujian::orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($ujians)
            ->addIndexColumn()
            ->addColumn('nama', function ($ujians)
            {
                $text = $ujians->nama;
                if ($ujians->isPublished) {
                    $text .= ' <span class="badge badge-success">Published</span>';
                }
                return $text;
            })
            ->addColumn('waktu_pengerjaan', function ($ujians)
            {
                return Carbon::parse($ujians->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($ujians->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('lama_pengerjaan', function ($ujians)
            {
                return '<span class="badge badge-warning">' . $ujians->lama_pengerjaan . '</span>';
            })
            ->addColumn('harga', function ($ujians)
            {
                return 'Rp' . number_format( $ujians->harga , 0 , ',' , '.' );
            })
            ->addColumn('aksi', function ($ujians) {
                $text = '';
                if (!$ujians->isPublished) {
                    $text .= '<button onclick="editData(`' . route('admin.ujian.update', $ujians->id) . '`)" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' . route('admin.ujian.destroy', $ujians->id) . '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>';
                }
                $text .= ' <a href="' . route('admin.ujian.soal.index', $ujians->id) . '" type="button" class="btn btn-outline-info"><i class="fa fa-eye"></i></a>';

                return $text;
            })
            ->rawColumns(['nama', 'aksi', 'lama_pengerjaan', 'waktu_pengerjaan'])
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
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
            'jam' => 'required|min:0',
            'menit' => 'required|min:0|max:59',
            'detik' => 'required|min:0|max:59',
            'harga' => 'required',
            'jumlah_soal' => 'required|min:0',
        ]);

        $lama_pengerjaan = implode(":", [$request->jam, $request->menit, $request->detik]);
        $ujian = new Ujian();
        $ujian->nama = $request->nama;
        $ujian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $ujian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $ujian->lama_pengerjaan = $lama_pengerjaan;
        $ujian->harga = $request->harga;
        $ujian->jumlah_soal = $request->jumlah_soal;
        $ujian->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ujian = Ujian::find($id);

        return response()->json($ujian);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ujian $ujian)
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
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
            'jam' => 'required|min:0',
            'menit' => 'required|min:0|max:59',
            'detik' => 'required|min:0|max:59',
            'harga' => 'required',
            'jumlah_soal' => 'required|min:0',
        ]);

        $lama_pengerjaan = implode(":", [$request->jam, $request->menit, $request->detik]);
        $ujian = Ujian::find($id);

        $ujian->nama = $request->nama;
        $ujian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $ujian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $ujian->lama_pengerjaan = $lama_pengerjaan;
        $ujian->harga = $request->harga;
        $ujian->jumlah_soal = $request->jumlah_soal;
        $ujian->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ujian = Ujian::find($id);
        $ujian->delete();

        return response(null, 204);
    }

    public function publish($id)
    {
        $ujian = Ujian::findorFail($id);
        if ($ujian->isPublished) {
            $ujian->isPublished = 0;
        } else {
            $ujian->isPublished = 1;
        }

        $ujian->update();

        return response()->json('Data berhasil disimpan', 200);
    }
}
