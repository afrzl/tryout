<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return view('admin.faq.index');
    }

    public function data() {
        $faqs = Faq::with('user')->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($faqs)
            ->addIndexColumn()
            ->addColumn('title', function ($faq)
            {
                $text = $faq->title;
                if ($faq->pinned) {
                    $text .= ' <i class="fa fa-thumbtack"></i>';
                }
                return $text;
            })
            ->addColumn('author', function ($faq)
            {
                return $faq->user->name;
            })
            ->addColumn('aksi', function ($faq) {
                return '<button onclick="pinData(`' . route('admin.faq.pin', $faq->id) . '`)" type="button" class="btn btn-outline-success"><i class="fa fa-thumbtack"></i></button>
                        <a href="'. route('admin.faq.edit', $faq->id) .'" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                        <button onclick="deleteData(`' . route('admin.faq.destroy', $faq->id) . '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>';
            })
            ->rawColumns(['title', 'content', 'file', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faq = new Faq();
        $action = route('admin.faq.store');

        return view('admin.faq.form', compact('faq', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ], [
            'title.required' => 'Judul tidak boleh kosong.',
            'content.required' => 'Isi tidak boleh kosong.',
        ]);

        $faq = new Faq();
        $faq->title = $request->title;
        $faq->content = $request->content;
        $faq->author_id = auth()->id();

        $faq->save();

        return redirect()->route('admin.faq.index')->with('message','Data berhasil disimpan');
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
        $faq = Faq::findOrFail($id);
        $action = route('admin.faq.update', $faq->id);
        return view('admin.faq.form', compact('faq', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ], [
            'title.required' => 'Judul tidak boleh kosong.',
            'content.required' => 'Isi tidak boleh kosong.',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->title = $request->title;
        $faq->content = $request->content;
        $faq->author_id = auth()->id();

        $faq->update();

        return redirect()->route('admin.faq.index')->with('message','Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return response(null, 204);
    }

    public function pin($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->pinned = !$faq->pinned;
        $faq->update();

        return response($faq->pinned, 200);
    }
}
