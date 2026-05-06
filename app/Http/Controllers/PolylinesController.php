<?php

namespace App\Http\Controllers;

use App\Models\PolylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    public function store(Request $request)
    {
        // VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', // ← WAJIB
            'geometry_polyline' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!', // ← NOTIF
            'geometry_polyline.required' => 'Polyline harus digambar!',
            'image.image' => 'File harus berupa gambar!',
            'image.mimes' => 'Format gambar tidak valid!',
            'image.max' => 'Ukuran gambar terlalu besar!',
        ]);

        // PHP Create Directory
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // PHP Upload File
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polyline,
            'image' => $name_image,
        ];

        // SIMPAN DATA
        if (!$this->polylines->create($data)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menyimpan data polyline.');
        }

        return redirect()->route('map')
            ->with('success', 'Data polyline berhasil disimpan.');
    }


    public function index() {}
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource
     */
    public function destroy(string $id)
    {
        //Mencari nama file gambar berdasarkan ID point
        $image = $this->polylines->find($id)->image;


        // Hapus data dari database
        if (!$this->polylines->destroy($id)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menghapus data polyline.');
        }

        // Hapus file gambar jika ada
        if ($image != null) {
            // Cek apakah file gambar ada sebelum menghapus
            if (file_exists('./storage/images/' . $image)) {
                // Hapus file gambar
                unlink('./storage/images/' . $image);
            }
        }

        // Kembali ke halaman peta
        return redirect()->route('map')
            ->with('success', 'Data polyline berhasil dihapus.');
    }
}
