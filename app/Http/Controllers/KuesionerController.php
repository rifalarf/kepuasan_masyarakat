<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKuesionerRequest;
use App\Http\Requests\UpdateKuesionerRequest;
use App\Models\Kuesioner;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Http\Request;

class KuesionerController extends Controller
{
    public function index()
    {
        $query = Kuesioner::query();

        if (auth()->user()->role === 'satker') {
            $query->where('village_id', auth()->user()->village_id);
        }

        $kuesioner = $query->with(['unsur', 'village'])->latest()->paginate(10);
        return view('pages.dashboard.kuesioner.index', compact('kuesioner'));
    }

    public function create()
    {
        $unsurs = Unsur::all();
        // Admin utama perlu memilih Satker mana yang akan dibuatkan kuesioner
        $villages = (auth()->user()->role === 'admin') ? Village::orderBy('name')->get() : null;

        return view('pages.dashboard.kuesioner.create', compact('unsurs', 'villages'));
    }

    public function store(StoreKuesionerRequest $request)
    {
        try {
            $data = $request->only('question', 'unsur_id');

            // Jika admin, ambil village_id dari form. Jika satker, ambil dari user yg login.
            $data['village_id'] = (auth()->user()->role === 'admin')
                ? $request->village_id
                : auth()->user()->village_id;

            Kuesioner::create($data);
            return redirect()
                ->route('kuesioner.index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
        }
    }

    public function edit(Kuesioner $kuesioner)
    {
        // Otorisasi: Pastikan admin satker hanya bisa edit kuesioner miliknya
        if (auth()->user()->role === 'satker' && $kuesioner->village_id !== auth()->user()->village_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        try {
            $unsurs = Unsur::all();
            $villages = (auth()->user()->role === 'admin') ? Village::orderBy('name')->get() : null;

            return view('pages.dashboard.kuesioner.edit', compact('kuesioner', 'unsurs', 'villages'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengambil data!', $th->getMessage()]]);
        }
    }

    public function update(UpdateKuesionerRequest $request, Kuesioner $kuesioner)
    {
        // Otorisasi
        if (auth()->user()->role === 'satker' && $kuesioner->village_id !== auth()->user()->village_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        try {
            $kuesioner->question = $request->question;
            $kuesioner->unsur_id = $request->unsur_id;
            if (auth()->user()->role === 'admin') {
                $kuesioner->village_id = $request->village_id;
            }
            $kuesioner->update();
            return redirect()->route('kuesioner.index', $kuesioner->uuid)->with('success', 'Data berhasil diedit!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengedit data!', $th->getMessage()]]);
        }
    }

    public function destroy(Kuesioner $kuesioner)
    {
        // Otorisasi
        if (auth()->user()->role === 'satker' && $kuesioner->village_id !== auth()->user()->village_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        try {
            Kuesioner::destroy($kuesioner->id);
            return redirect()->route('kuesioner.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data!', $th->getMessage()]]);
        }
    }

    public function checks(Request $request)
    {
        try {
            $action = $request->action;
            $checks = $request->checks;

            if ($action == 'delete') {
                Kuesioner::whereIn('uuid', $checks)->delete();
            }

            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data', $th->getMessage()]]);
        }
    }
}