<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::latest()->paginate(10);

            $totalUsers = User::count();
            $adminCount = User::where('role_id', 1)->count();
            $petugasCount = User::where('role_id', 2)->count();
            $petugasYanbungCount = User::where('role_id', 3)->count();

            return view('admin.kelola_user.index', compact(
                'users', 
                'totalUsers', 
                'adminCount', 
                'petugasCount', 
                'petugasYanbungCount'
            ));
        } catch (\Exception $e) {
            Log::error('UserController@index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data user.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kelola_user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|in:1,2,3',
            'password' => 'required|min:8|confirmed',
            'is_active' => 'required|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'is_active' => $request->is_active,
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UserController@store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.kelola_user.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('UserController@edit Error: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|in:1,2,3',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'required|in:0,1'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => $request->is_active,
            ];

            if ($request->password) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UserController@update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Prevent admin from deleting themselves
            if (auth()->id() == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri.'
                ], 422);
            }

            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            Log::error('UserController@destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search and filter users (AJAX compatible)
     */
    public function search(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $role = $request->get('role', '');
            $status = $request->get('status', '');

            $users = User::when($search, function($query) use ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->when($role !== '', function($query) use ($role) {
                    // Convert role name to role_id
                    $roleMap = [
                        'admin' => 1,
                        'petugas' => 2,
                        'petugas_yanbung' => 3
                    ];
                    $roleId = $roleMap[$role] ?? $role;
                    $query->where('role_id', $roleId);
                })
                ->when($status !== '', function($query) use ($status) {
                    $statusValue = $status === 'active' ? 1 : 0;
                    $query->where('is_active', $statusValue);
                })
                ->latest()
                ->paginate(10);

            // Untuk AJAX request, kembalikan partial view
            if ($request->ajax() || $request->has('ajax')) {
                return view('admin.kelola_user.partials.user-table', compact('users'))->render();
            }

            // Untuk non-AJAX, kembalikan view lengkap
            $totalUsers = User::count();
            $adminCount = User::where('role_id', 1)->count();
            $petugasCount = User::where('role_id', 2)->count();
            $petugasYanbungCount = User::where('role_id', 3)->count();

            return view('admin.kelola_user.index', compact(
                'users', 
                'totalUsers', 
                'adminCount', 
                'petugasCount', 
                'petugasYanbungCount'
            ));

        } catch (\Exception $e) {
            Log::error('UserController@search Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->has('ajax')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Terjadi kesalahan saat mencari data.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencari data.');
        }
    }
}