<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Prodi;

class ProdiController extends Controller
{
    public function index(Request $req)
    {
        // Untuk mendapat value dari parameter url method get
        $search = request('search', '');
        // Mengambil data tanpa parameter search
        $prodis = Prodi::orderBy("id", "desc")->get();
        // Kondisi untuk validasi parameter search
        if ($search) {
            // Mengambil data dengan parameter search
            $prodis = Prodi::whereRaw('LOWER(nama) LIKE ?', ["%" . strtolower($search) . "%"])->orderBy('id', 'desc')->get();
        }
        return view('prodi.index', compact('prodis'));
    }

    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama' => 'required'
        ]);

        Prodi::create([
            'nama' => $req->nama
        ]);

        return redirect()->route('prodi')->with('success', 'Program Studi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.edit', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('prodi')->with('success', 'Program Studi berhasil diupdated');
    }

    public function delete($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('prodi')->with('success', 'Data Program Studi berhasil dihapus');
    }
}

