<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKuesionerRequest;
use App\Http\Requests\UpdateKuesionerRequest;
use App\Models\Kuesioner;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\KuesionerTemplateExport;
use App\Imports\KuesionerImport;
use Maatwebsite\Excel\Facades\Excel;

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
        $user = auth()->user();
        $unsursQuery = Unsur::query();

        if ($user->role === 'admin') {
            // Admin Utama HANYA bisa memilih dari Unsur Global
            $unsursQuery->whereNull('village_id');
        } elseif ($user->role === 'satker') {
            // Satker bisa memilih dari unsur global DAN unsur miliknya sendiri
            $unsursQuery->whereNull('village_id')->orWhere('village_id', $user->village_id);
        }

        $unsurs = $unsursQuery->orderBy('unsur')->get();
        $villages = Village::all();
        return view('pages.dashboard.kuesioner.create', compact('unsurs', 'villages'));
    }

    public function store(StoreKuesionerRequest $request)
    {
        // Validasi tambahan untuk memastikan 'question' adalah array
        $validated = $request->validate([
            'unsur_id' => 'required|exists:unsurs,id',
            'village_id' => 'nullable|exists:villages,id',
            'question' => 'required|array|min:1',
            'question.*' => 'required|string|max:255', // Validasi setiap item dalam array
        ]);

        try {
            $baseData = [
                'unsur_id' => $validated['unsur_id'],
                'village_id' => (auth()->user()->role === 'admin')
                    ? $validated['village_id']
                    : auth()->user()->village_id,
            ];

            foreach ($validated['question'] as $questionText) {
                if (!empty($questionText)) { // Pastikan tidak menyimpan pertanyaan kosong
                    Kuesioner::create(array_merge($baseData, ['question' => $questionText]));
                }
            }

            return redirect()
                ->route('kuesioner.index')
                ->with('success', 'Kuesioner berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
        }
    }

    public function edit(Kuesioner $kuesioner)
    {
        // Otorisasi: Pastikan admin satker hanya bisa edit kuesioner miliknya
        if (auth()->user()->role === 'satker' && $kuesioner->village_id !== auth()->user()->village_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        $user = auth()->user();
        $unsursQuery = Unsur::query();

        if ($user->role === 'admin') {
            // Admin Utama bisa memilih dari Unsur Global
            $unsursQuery->whereNull('village_id');
        } elseif ($user->role === 'satker') {
            // Satker bisa memilih dari unsur global DAN unsur miliknya sendiri
            $unsursQuery->whereNull('village_id')->orWhere('village_id', $user->village_id);
        }

        $unsurs = $unsursQuery->orderBy('unsur')->get();
        $villages = Village::all();
        
        // Mengarahkan ke view yang benar untuk edit kuesioner
        return view('pages.dashboard.kuesioner.edit', compact('kuesioner', 'unsurs', 'villages'));
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

            return redirect()->route('kuesioner.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data', $th->getMessage()]]);
        }
    }

    public function showImportForm()
    {
        return view('pages.dashboard.kuesioner.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new KuesionerTemplateExport, 'template_kuesioner.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new KuesionerImport, $request->file('file'));
            return redirect()->route('kuesioner.index')->with('success', 'Kuesioner berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage()]);
        }
    }
}