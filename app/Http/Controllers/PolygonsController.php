<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;

class PolygonsController extends Controller
{
    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }

    public function store(Request $request)
    {
        // VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', // ← WAJIB
            'geometry_polygon' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!', // ← NOTIF
            'geometry_polygon.required' => 'Polygon harus digambar!',
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
            'geom' => $request->geometry_polygon,
            'image' => $name_image,
        ];

        // SIMPAN DATA
        if (!$this->polygons->create($data)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menyimpan data polygon.');
        }

        return redirect()->route('map')
            ->with('success', 'Data polygon berhasil disimpan.');
    }

    /**
     * Remove the specified resource
     */
    public function destroy(string $id)
    {
        //Mencari nama file gambar berdasarkan ID point
        $image = $this->polygons->find($id)->image;


        // Hapus data dari database
        if (!$this->polygons->destroy($id)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menghapus data polygone.');
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
            ->with('success', 'Data polygone berhasil dihapus.');
    }
}

