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
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!', // ← NOTIF
            'geometry_polygon.required' => 'Polygon harus digambar!',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polygon
        ];

        // SIMPAN DATA
        if (!$this->polygons->create($data)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menyimpan data polygon.');
        }

        return redirect()->route('map')
            ->with('success', 'Data polygon berhasil disimpan.');
    }
}
