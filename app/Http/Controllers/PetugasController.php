<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class PetugasController extends Controller
{
    // ... method yang sudah ada ...

    /**
     * Show the form for creating pengeluaran material.
     */
    public function createPengeluaran()
    {
        return view('petugas.pengeluaran-create');
    }

    /**
     * Store a newly created pengeluaran material.
     */
    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pengambil' => 'required|string|max:255',
            'keperluan' => 'required|in:yanbung,P2TL,gangguan,PLN',
            'id_pelanggan' => 'nullable|string|max:255',
            'mcb_2a' => 'nullable|integer|min:0',
            'mcb_4a' => 'nullable|integer|min:0',
            'mcb_6a' => 'nullable|integer|min:0',
            'mcb_10a' => 'nullable|integer|min:0',
            'mcb_16a' => 'nullable|integer|min:0',
            'mcb_20a' => 'nullable|integer|min:0',
            'mcb_25a' => 'nullable|integer|min:0',
            'mcb_35a' => 'nullable|integer|min:0',
            'segel' => 'nullable|integer|min:0',
            'lpb' => 'nullable|integer|min:0',
            'paska' => 'nullable|integer|min:0',
            'sr' => 'nullable|integer|min:0',
            'swc' => 'nullable|integer|min:0',
            'lintap_10_16' => 'nullable|integer|min:0',
            'lintap_16_35' => 'nullable|integer|min:0',
            'lintap_50_70' => 'nullable|integer|min:0',
            'kondom' => 'nullable|integer|min:0',
            'solasi' => 'nullable|integer|min:0',
            'foto_sr_sebelum' => 'nullable|image|max:2048',
            'foto_sr_sesudah' => 'nullable|image|max:2048',
            'foto_bukti_pengambilan' => 'nullable|image|max:2048',
        ]);

        // Set jenis sebagai Pengeluaran
        $validated['jenis'] = 'Pengeluaran';
        $validated['nama'] = $validated['nama_pengambil'];

        // Handle file uploads
        if ($request->hasFile('foto_sr_sebelum')) {
            $validated['foto_sr_sebelum'] = $request->file('foto_sr_sebelum')->store('foto-sr-sebelum', 'public');
        }

        if ($request->hasFile('foto_sr_sesudah')) {
            $validated['foto_sr_sesudah'] = $request->file('foto_sr_sesudah')->store('foto-sr-sesudah', 'public');
        }

        if ($request->hasFile('foto_bukti_pengambilan')) {
            $validated['foto_bukti'] = $request->file('foto_bukti_pengambilan')->store('foto-bukti', 'public');
        }

        Material::create($validated);

        return redirect()->route('petugas.material')->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    /**
     * Show the form for editing pengeluaran material.
     */
    public function editPengeluaran($id)
    {
        $material = Material::findOrFail($id);
        return view('petugas.pengeluaran-edit', compact('material'));
    }

    /**
     * Update the specified pengeluaran material.
     */
    public function updatePengeluaran(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pengambil' => 'required|string|max:255',
            'keperluan' => 'required|in:yanbung,P2TL,gangguan,PLN',
            'id_pelanggan' => 'nullable|string|max:255',
            'mcb_2a' => 'nullable|integer|min:0',
            'mcb_4a' => 'nullable|integer|min:0',
            'mcb_6a' => 'nullable|integer|min:0',
            'mcb_10a' => 'nullable|integer|min:0',
            'mcb_16a' => 'nullable|integer|min:0',
            'mcb_20a' => 'nullable|integer|min:0',
            'mcb_25a' => 'nullable|integer|min:0',
            'mcb_35a' => 'nullable|integer|min:0',
            'segel' => 'nullable|integer|min:0',
            'lpb' => 'nullable|integer|min:0',
            'paska' => 'nullable|integer|min:0',
            'sr' => 'nullable|integer|min:0',
            'swc' => 'nullable|integer|min:0',
            'lintap_10_16' => 'nullable|integer|min:0',
            'lintap_16_35' => 'nullable|integer|min:0',
            'lintap_50_70' => 'nullable|integer|min:0',
            'kondom' => 'nullable|integer|min:0',
            'solasi' => 'nullable|integer|min:0',
            'foto_sr_sebelum' => 'nullable|image|max:2048',
            'foto_sr_sesudah' => 'nullable|image|max:2048',
            'foto_bukti_pengambilan' => 'nullable|image|max:2048',
        ]);

        $validated['nama'] = $validated['nama_pengambil'];

        // Handle file uploads
        if ($request->hasFile('foto_sr_sebelum')) {
            $validated['foto_sr_sebelum'] = $request->file('foto_sr_sebelum')->store('foto-sr-sebelum', 'public');
        }

        if ($request->hasFile('foto_sr_sesudah')) {
            $validated['foto_sr_sesudah'] = $request->file('foto_sr_sesudah')->store('foto-sr-sesudah', 'public');
        }

        if ($request->hasFile('foto_bukti_pengambilan')) {
            $validated['foto_bukti'] = $request->file('foto_bukti_pengambilan')->store('foto-bukti', 'public');
        }

        $material->update($validated);

        return redirect()->route('petugas.material')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified pengeluaran material.
     */
    public function destroyPengeluaran($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('petugas.material')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}