<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index($userId)
    {
        $n = Notifikasi::where('user_id',$userId)->orderBy('created_at','desc')->get();
        return response()->json($n);
    }

    public function markRead($id)
    {
        $n = Notifikasi::findOrFail($id);
        $n->dibaca = true;
        $n->save();
        return response()->json(['message'=>'Notifikasi dibaca']);
    }
}
