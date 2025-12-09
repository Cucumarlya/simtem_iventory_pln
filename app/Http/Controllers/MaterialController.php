<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\DetailTransaksiMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        try {
            $materials = Material::orderBy('created_at', 'desc')->paginate(10);
            return view('admin.master.index', compact('materials'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $lastMaterial = Material::orderBy('id', 'desc')->first();
            $nextCode = 'MAT-001';
            
            if ($lastMaterial) {
                if (preg_match('/MAT-(\d+)/', $lastMaterial->kode_material, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                    $nextCode = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = $lastMaterial->id + 1;
                    $nextCode = 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                }
            }
            
            return view('admin.master.create', compact('nextCode'));
        } catch (\Exception $e) {
            \Log::error('Error in MaterialController@create: ' . $e->getMessage());
            return redirect()->route('admin.master.material.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

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
            if (empty($request->kode_material)) {
                $lastMaterial = Material::orderBy('id', 'desc')->first();
                $nextNumber = $lastMaterial ? $lastMaterial->id + 1 : 1;
                $request->merge(['kode_material' => 'MAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT)]);
            }
            
            // Set stok awal sebagai stok saat ini
            $data = $request->all();
            $data['stok'] = $request->stok_awal;
            
            Material::create($data);
            return redirect()->route('admin.master.material.index')
                ->with('success', 'Material berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Error storing material: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $material = Material::findOrFail($id);
            $hasTransactions = DetailTransaksiMaterial::where('material_id', $id)->exists();
            
            return view('admin.master.edit', compact('material', 'hasTransactions'));
        } catch (\Exception $e) {
            return redirect()->route('admin.master.material.index')
                ->with('error', 'Material tidak ditemukan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
            
            $updateData = [
                'nama_material' => $request->nama_material,
                'satuan' => $request->satuan,
                'min_stok' => $request->min_stok,
            ];
            
            if (!$hasTransactions) {
                $updateData['stok_awal'] = $request->stok_awal;
                $updateData['stok'] = $request->stok_awal; // Update stok juga
            }
            
            $material->update($updateData);
            
            return redirect()->route('admin.master.material.index')
                ->with('success', 'Material berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            $hasTransactions = DetailTransaksiMaterial::where('material_id', $id)->exists();
            
            if ($hasTransactions) {
                return back()->with('error', 'Material tidak dapat dihapus karena sudah memiliki transaksi.');
            }
            
            $material->delete();
            
            return redirect()->route('admin.master.material.index')
                ->with('success', 'Material berhasil dihapus!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');
            $materials = Material::where(function($query) use ($search) {
                    $query->where('nama_material', 'like', "%{$search}%")
                          ->orWhere('kode_material', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            if ($request->ajax()) {
                return view('admin.master.partials.material-table', compact('materials'))->render();
            }
                
            return view('admin.master.index', compact('materials'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return redirect()->route('admin.master.material.index')
                ->with('error', 'Terjadi kesalahan pencarian: ' . $e->getMessage());
        }
    }
}