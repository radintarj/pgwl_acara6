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
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!',
            'geometry_point.required' => 'Geometry harus diisi!',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_point
        ];

        // SIMPAN DATA
        if (!$this->points->create($data)) {
            return redirect()->route('map')
                ->with('error', 'Gagal menyimpan data point.');
        }

        return redirect()->route('map')
            ->with('success', 'Data point berhasil disimpan.');
    }
}
