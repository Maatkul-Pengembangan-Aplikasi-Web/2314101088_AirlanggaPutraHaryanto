<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Prodi;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $req)
    {
        // Untuk mendapat value dari parameter url method get
        $search = request('search', '');
        // Mengambil data tanpa parameter search
        $mahasiswas = Mahasiswa::orderBy("id", "desc")->get();
        // Kondisi untuk validasi parameter search
        if ($search) {
            // Mengambil data dengan parameter search
            $mahasiswas = Mahasiswa::whereRaw('LOWER(nama) LIKE ?', ["%" . strtolower($search) . "%"])->orderBy('id', 'desc')->get();
        }
        return view('mahasiswa.index', compact('mahasiswas'));
    }
    public function create()
    {
        $prodis = Prodi::all();
        return view('mahasiswa.create', compact('prodis'));
    }

    public function save(Request $request)
    {
        $validation = $request->validate([
            'nama' => 'required',
            'npm' => 'required|numeric',
            'prodi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $namaFoto = $request->npm . '.' . $request->foto->extension();
            $request->foto->move(public_path('fotomahasiswa'), $namaFoto);
            $validation['foto'] = $namaFoto;
        }

        $mahasiswa = Mahasiswa::create($validation);

        if ($mahasiswa) {
            session()->flash('success', 'Data Mahasiswa Berhasil di Tambahkan');
            return redirect(route('mahasiswa'));
        } else {
            session()->flash('error', 'Ada Kesalahan');
            return redirect(route('mahasiswa.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $prodis = Prodi::all();
        return view('mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Validasi termasuk gambar
        $validation = $request->validate([
            'nama' => 'required',
            'npm' => 'required|numeric',
            'prodi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Proses upload gambar baru
        if ($request->hasFile('foto')) {
            // Hapus gambar lama jika ada
            if ($mahasiswa->foto && file_exists(public_path('fotomahasiswa/' . $mahasiswa->foto))) {
                unlink(public_path('fotomahasiswa/' . $mahasiswa->foto));
            }

            $namaFoto = $request->npm . '.' . $request->foto->extension();
            $request->foto->move(public_path('fotomahasiswa'), $namaFoto);

            // Set gambar baru
            $mahasiswa->foto = $namaFoto;
        }

        // Update data produk lainnya
        $mahasiswa->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'prodi' => $request->prodi,
        ]);

        session()->flash('success', 'Data Mahasiswa Berhasil di Perbaharui');
        return redirect(route('mahasiswa'));
    }

    public function delete($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Hapus gambar jika ada
        if ($mahasiswa->foto) {
            if (file_exists(public_path('fotomahasiswa/' . $mahasiswa->foto))) {
                unlink(public_path('fotomahasiswa/' . $mahasiswa->foto));
            }
        }

        // Hapus produk
        $mahasiswa->delete();

        session()->flash('success', 'Data Mahasiswa Berhasil di Hapus');
        return redirect(route('mahasiswa'));
    }
}
