<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total_admins' => Admin::count(),
            'active_admins' => Admin::where('is_active', true)->count(),
            'inactive_admins' => Admin::where('is_active', false)->count(),
        ];

        return view('admin.admins.index', compact('admins', 'stats'));
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['password'] = Hash::make($request->password);

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan!');
    }

    /**
     * Display the specified admin
     */
    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'is_active' => 'boolean'
        ]);

        $data = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui!');
    }

    /**
     * Remove the specified admin
     */
    public function destroy(Admin $admin)
    {
        // Prevent deleting the last admin
        $adminCount = Admin::count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.admins.index')->with('error', 'Tidak dapat menghapus admin terakhir!');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus!');
    }

    /**
     * Toggle admin status (AJAX)
     */
    public function toggleStatus(Admin $admin)
    {
        // Prevent deactivating the last active admin
        if ($admin->is_active) {
            $activeAdminCount = Admin::where('is_active', true)->count();
            if ($activeAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menonaktifkan admin terakhir yang aktif!'
                ], 400);
            }
        }

        $admin->update(['is_active' => !$admin->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $admin->is_active,
            'message' => 'Status admin berhasil diubah!'
        ]);
    }
}