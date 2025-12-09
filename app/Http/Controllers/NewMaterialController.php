<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DetailTransaksiMaterial;
use Illuminate\Http\Request;

class NewMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $materials = Material::orderBy('created_at', 'desc')->paginate(10);
            return view('admin.new-material.index', compact('materials'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $lastMaterial = Material::orderBy('kode_material', 'desc')->first();
            $nextCode = 'MAT-001';
            
            if ($lastMaterial && preg_match('/MAT-(\d+)/', $lastMaterial->kode_material, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
                $nextCode = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
            
            return view('admin.new-material.create', compact('nextCode'));
        } catch (\Exception $e) {
            return redirect()->route('admin.new-material.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_material' => 'required|string|max:50|unique:materials,kode_material',
            'nama_material' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'stok_awal' => 'required|integer|min:0',
            'min_stok' => 'required|integer|min:0',
        ]);
        
        try {
            Material::create([
                'kode_material' => $request->kode_material,
                'nama_material' => $request->nama_material,
                'satuan' => $request->satuan,
                'stok_awal' => $request->stok_awal,
                'min_stok' => $request->min_stok,
            ]);
            
            return redirect()->route('admin.new-material.index')
                ->with('success', 'Material berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $material = Material::findOrFail($id);
            return view('admin.new-material.show', compact('material'));
        } catch (\Exception $e) {
            return redirect()->route('admin.new-material.index')
                ->with('error', 'Material tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $material = Material::findOrFail($id);
            $hasTransactions = DetailTransaksiMaterial::where('material_id', $id)->exists();
            
            return view('admin.new-material.edit', compact('material', 'hasTransactions'));
        } catch (\Exception $e) {
            return redirect()->route('admin.new-material.index')
                ->with('error', 'Material tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $hasTransactions = DetailTransaksiMaterial::where('material_id', $id)->exists();
        
        $rules = [
            'nama_material' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'min_stok' => 'required|integer|min:0',
        ];
        
        if (!$hasTransactions) {
            $rules['stok_awal'] = 'required|integer|min:0';
        }
        
        $request->validate($rules);
        
        try {
            $updateData = [
                'nama_material' => $request->nama_material,
                'satuan' => $request->satuan,
                'min_stok' => $request->min_stok,
            ];
            
            if (!$hasTransactions) {
                $updateData['stok_awal'] = $request->stok_awal;
            }
            
            $material->update($updateData);
            
            return redirect()->route('admin.new-material.index')
                ->with('success', 'Material berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            $hasTransactions = DetailTransaksiMaterial::where('material_id', $id)->exists();
            
            if ($hasTransactions) {
                return back()->with('error', 'Material tidak dapat dihapus karena sudah memiliki transaksi.');
            }
            
            $material->delete();
            
            return redirect()->route('admin.new-material.index')
                ->with('success', 'Material berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}