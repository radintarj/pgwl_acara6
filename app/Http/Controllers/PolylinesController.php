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
        ], [
            'name.required' => 'Nama harus diisi!',
            'description.required' => 'Description harus diisi!', // ← NOTIF
            'geometry_polyline.required' => 'Polyline harus digambar!',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polyline
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
    public function destroy(string $id) {}
}
