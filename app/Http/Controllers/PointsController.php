<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
    }

    public function store(Request $request)
    {
        // VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'geometry_point' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!',
            'geometry_point.required' => 'Geometry harus diisi!',
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
            'geom' => $request->geometry_point,
            'image' => $name_image,
        ];

        // Menyimpan data ke database
        if (!$this->points->create($data)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menyimpan data point.');
        }
        // Kembali ke halaman peta
        return redirect()->route('map')
            ->with('success', 'Data point berhasil disimpan.');
    }


    /**
     * Remove the specified resource
     */
    public function destroy(string $id)
    {
        //Mencari nama file gambar berdasarkan ID point
        $image = $this->points->find($id)->image;


        // Hapus data dari database
        if (!$this->points->destroy($id)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menghapus data point.');
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
            ->with('success', 'Data point berhasil dihapus.');
    }
}
