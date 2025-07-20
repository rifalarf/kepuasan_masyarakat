<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Village;
use App\Models\SatkerType; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'satker');

        // Pencarian berdasarkan nama atau email
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admin_satkers = $query->with('village')->latest()->paginate(10);

        return view('pages.dashboard.admin-satker.index', compact('admin_satkers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil SatkerType beserta relasi villages-nya.
        // Hanya ambil Tipe yang memiliki Satuan Kerja.
        $satkerTypes = SatkerType::with('villages')->whereHas('villages')->orderBy('name')->get();
        return view('pages.dashboard.admin-satker.create', compact('satkerTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'village_id' => ['required', 'exists:villages,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'satker', // Set peran secara eksplisit
            'village_id' => $request->village_id,
            'avatar' => hash('sha256', $request->email), // <-- TAMBAHKAN INI
        ]);

        return redirect()->route('admin-satker.index')->with('success', 'Admin Satker berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin_satker)
    {
        // Lakukan hal yang sama untuk method edit
        $satkerTypes = SatkerType::with('villages')->whereHas('villages')->orderBy('name')->get();
        return view('pages.dashboard.admin-satker.edit', compact('admin_satker', 'satkerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin_satker)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($admin_satker->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'village_id' => ['required', 'exists:villages,id'],
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'village_id' => $request->village_id,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $admin_satker->update($dataToUpdate);

        return redirect()->route('admin-satker.index')->with('success', 'Admin Satker berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin_satker)
    {
        $admin_satker->delete();
        return redirect()->route('admin-satker.index')->with('success', 'Admin Satker berhasil dihapus.');
    }
}
