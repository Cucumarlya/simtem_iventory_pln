<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function rekap()
    {
        $data = DB::table('rekap_stok_view')->get();
        return response()->json($data);
    }

    public function ledger($materialId = null)
    {
        $q = \App\Models\StokMaterial::with('material');
        if ($materialId) $q->where('material_id', $materialId);
        $data = $q->orderBy('tanggal','desc')->paginate(50);
        return response()->json($data);
    }
}
