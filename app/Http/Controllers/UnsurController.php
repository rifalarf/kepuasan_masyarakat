<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnsurRequest;
use App\Models\Unsur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Village;

class UnsurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Unsur::query();

        if ($user->role === 'admin') {
            // Admin Utama HANYA melihat Unsur Global
            $query->whereNull('village_id');
        } elseif ($user->role === 'satker') {
            // Admin Satker melihat Unsur Global DAN unsur miliknya sendiri
            $query->whereNull('village_id')
                  ->orWhere('village_id', $user->village_id);
        }

        $unsurs = $query->with('village')->latest()->paginate(10);

        return view('pages.dashboard.unsur.index', compact('unsurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villages = [];
        if (auth()->user()->role === 'admin') {
            $villages = Village::orderBy('name')->get();
        }
        return view('pages.dashboard.unsur.create', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnsurRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Tetapkan pemilik unsur
        $data['user_id'] = $user->id;

        // Jika yang membuat adalah Admin Satker, otomatis set village_id miliknya.
        // Jika Admin Utama, village_id diambil dari form (bisa null untuk Global).
        if ($user->role === 'satker') {
            $data['village_id'] = $user->village_id;
        } else {
            $data['village_id'] = $request->village_id;
        }

        Unsur::create($data);

        // Redirect kembali ke halaman sebelumnya (bisa dari modal atau halaman unsur)
        return back()->with('success', 'Unsur berhasil ditambahkan.');
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
    public function edit(Unsur $unsur)
    {
        // Otorisasi: Admin Satker tidak bisa edit unsur global
        if (auth()->user()->role === 'satker' && is_null($unsur->village_id)) {
            abort(403, 'AKSES DITOLAK. ANDA TIDAK BISA MENGEDIT UNSUR GLOBAL.');
        }
        return view('pages.dashboard.unsur.edit', compact('unsur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unsur $unsur)
    {
        // Otorisasi yang sama seperti edit
        if (auth()->user()->role === 'satker' && is_null($unsur->village_id)) {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate(['unsur' => 'required|string|max:255']);
        $unsur->update($request->only('unsur'));
        return back()->with('success', 'Unsur berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unsur $unsur)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        if ($unsur->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus unsur ini.');
        }

        $unsur->delete();
        return back()->with('success', 'Unsur berhasil dihapus.');
    }
}
