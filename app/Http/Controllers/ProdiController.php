<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Prodi;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::orderBy('id', 'desc')->get();
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
}
