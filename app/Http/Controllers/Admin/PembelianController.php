<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pembelian.index');
    }

    public function data(Request $request)
    {
        $pembelians = Pembelian::with('user')
                        ->with('voucher')
                        ->where('paket_id', $request->paket_ujian)
                        ->orderBy('status', 'desc')
                        ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($pembelians)
            ->addIndexColumn()
            ->addColumn('email', function ($pembelians)
            {
                return '<a href="javascript:void(0);" onclick="detailForm(`' . route('admin.pembelian.show', $pembelians->id) . '`)">' . $pembelians->user->email;
            })
            ->addColumn('nama', function ($pembelians)
            {
                return $pembelians->user->name;
            })
            ->addColumn('created_at', function ($pembelians)
            {
                return Carbon::parse($pembelians->created_at)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('voucher', function ($pembelians)
            {
                if ($pembelians->voucher) {
                    return '<span class="badge badge-primary">'. $pembelians->voucher->kode .'</span>';
                    return $pembelians->voucher->kode . "(". $pembelians->voucher->diskon .")";
                }
            })
            ->addColumn('harga', function ($pembelians)
            {
                return 'Rp' . number_format($pembelians->harga , 0 , ',' , '.' );
            })
            ->addColumn('status', function ($pembelians)
            {
                if ($pembelians->status == "Sukses") {
                    return '<span class="badge badge-success">Sukses</span>';
                }
                if ($pembelians->status == "Belum dibayar") {
                    return '<span class="badge badge-warning">Belum Bayar</span>';
                }
                if ($pembelians->status == "Gagal") {
                    return '<span class="badge badge-danger">Gagal</span>';
                }
            })
            ->rawColumns(['aksi', 'email', 'tanggal', 'voucher', 'status'])
            ->make(true);
    }

    public function getSummary($id) {
        $paket = PaketUjian::with(['pembelian' => function($query) {
                            $query->where('status', 'Sukses');
                        }])->findOrFail($id);
        $data = [
            'paketUjian' => ': ' . $paket->nama,
            'totalPembelian' => ': ' . $paket->pembelian->count(),
            'totalPembayaran' => ': Rp' . number_format($paket->pembelian->sum('harga'), 0 , ',' , '.' ),
        ];
        return response()->json($data);
    }

    public function getUser(Request $request) {
        $users = User::orderBy('email', 'ASC')
                    ->select('id', 'email')
                    ->with('roles')
                    ->where('email', 'like', '%'.$request->search.'%')
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

    public function dataPaket() {
        $data = PaketUjian::orderBy('created_at', 'asc')->get();
        return response()->json($data, 200);
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
        $cek = Pembelian::where('user_id', $request->user)
                ->where('paket_id', $request->paket)
                ->latest('updated_at')
                ->first();
        $paketUjian = PaketUjian::find($request->paket);

        if (!$cek) {
            $pembelian = new Pembelian();
            $pembelian->paket_id = $request->paket;
            $pembelian->user_id = $request->user;
            $pembelian->status = 'Sukses';
            $pembelian->jenis_pembayaran = 'Manual';
            $pembelian->harga = $paketUjian->harga;

            //kalo udah beli tobar batch 1
            if ($paketUjian->id == '0df8c9b0-d352-448b-9611-abadffc4f46d') {
                $tobar = Pembelian::where('user_id', $request->user)
                        ->where('paket_id', '33370256-b734-470a-afe9-c7ca8421f1b3')
                        ->where('status', 'Sukses')
                        ->latest('updated_at')
                        ->first();
                if ($tobar) {
                    $pembelian->harga = $paketUjian->harga - $tobar->harga;
                }
            }

            $pembelian->save();
            return response()->json('Berhasil menambahkan peserta', 200);
        } else {
            if ($cek->status == 'Gagal') {
                $pembelian = new Pembelian();
                $pembelian->paket_id = $request->paket;
                $pembelian->user_id = $request->user;
                $pembelian->status = 'Sukses';
                $pembelian->jenis_pembayaran = 'Manual';
                $pembelian->harga = $paketUjian->harga;
                //kalo udah beli tobar batch 1
                if ($paketUjian->id == '0df8c9b0-d352-448b-9611-abadffc4f46d') {
                    $tobar = Pembelian::where('user_id', $request->user)
                            ->where('paket_id', '33370256-b734-470a-afe9-c7ca8421f1b3')
                            ->where('status', 'Sukses')
                            ->latest('updated_at')
                            ->first();
                    if ($tobar) {
                        $pembelian->harga = $paketUjian->harga - $tobar->harga;
                    }
                }

                $pembelian->save();

                return response()->json('Berhasil menambahkan peserta', 200);
            } else {
                return response()->json('Peserta sudah membeli paket', 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with('paketUjian', 'user', 'voucher', 'user.usersDetail')->findOrFail($id);
        $pembelian->idTransaksi = '#' . sprintf('%06d', $pembelian->id);
        $pembelian->tanggalTransaksi = Carbon::parse($pembelian->created_at)->isoFormat('D MMMM Y HH:mm:ss');
        $pembelian->hargaTotal = 'Rp' . number_format($pembelian->harga , 0 , ',' , '.' );
        return response()->json($pembelian);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        //
    }
}
