<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * Format tanggal Indonesia
     */
    protected function formatTanggalIndo($date, $withTime = false)
    {
        if (!$date) return '-';
        
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $day = date('w', strtotime($date));
        $tanggal = date('j', strtotime($date));
        $bulan = $bulan[date('n', strtotime($date))];
        $tahun = date('Y', strtotime($date));
        
        if ($withTime) {
            $waktu = date('H:i', strtotime($date));
            return $hari[$day] . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $waktu;
        }
        
        return $hari[$day] . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
    
    /**
     * Format angka dengan separator ribuan
     */
    protected function formatAngka($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
    
    /**
     * Response JSON success
     */
    protected function responseSuccess($data = [], $message = 'Berhasil', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    
    /**
     * Response JSON error
     */
    protected function responseError($message = 'Terjadi kesalahan', $errors = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}