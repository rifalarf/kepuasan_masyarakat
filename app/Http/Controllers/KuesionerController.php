<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKuesionerRequest;
use App\Http\Requests\UpdateKuesionerRequest;
use App\Models\Kuesioner;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        // Log data yang masuk untuk debugging
        Log::info('Data request kuesioner:', $request->all());

        // HAPUS BLOK INI - validasi sudah dilakukan oleh StoreKuesionerRequest
        /*
        $validated = $request->validate([
            'unsur_id' => 'required|exists:unsurs,id',
            'village_id' => 'nullable|exists:villages,id',
            'question' => 'required|array|min:1',
            'question.*' => 'required|string|max:255',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        */

        try {
            $user = auth()->user();
            
            // Tentukan village_id berdasarkan role
            $villageId = null;
            if ($user->role === 'admin') {
                $villageId = $request->village_id;
            } elseif ($user->role === 'satker') {
                $villageId = $user->village_id;
            }

            Log::info('Village ID yang akan disimpan: ' . $villageId);

            $baseData = [
                'unsur_id' => $request->unsur_id,
                'village_id' => $villageId,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            Log::info('Base data:', $baseData);

            $savedCount = 0;
            foreach ($request->question as $index => $questionText) {
                if (!empty(trim($questionText))) {
                    $kuesionerData = array_merge($baseData, ['question' => trim($questionText)]);
                    Log::info("Menyimpan pertanyaan ke-{$index}:", $kuesionerData);
                    
                    $kuesioner = Kuesioner::create($kuesionerData);
                    $savedCount++;
                    
                    Log::info("Pertanyaan ke-{$index} berhasil disimpan dengan ID: " . $kuesioner->id);
                }
            }

            Log::info("Total {$savedCount} pertanyaan berhasil disimpan.");

            return redirect()
                ->route('kuesioner.index')
                ->with('success', "{$savedCount} Kuesioner berhasil disimpan!");
        } catch (\Throwable $th) {
            Log::error('Error saat menyimpan kuesioner: ' . $th->getMessage());
            Log::error('Stack trace: ' . $th->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Terjadi kesalahan: ' . $th->getMessage()]);
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
        if (auth()->user()->role === 'satker' && $kuesioner->village_id !== auth()->user()->village_id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        try {
            $kuesioner->question = $request->question;
            $kuesioner->unsur_id = $request->unsur_id;
            $kuesioner->start_date = $request->start_date;
            $kuesioner->end_date = $request->end_date;
            
            if (auth()->user()->role === 'admin') {
                $kuesioner->village_id = $request->village_id;
            }
            
            $kuesioner->save();

            return redirect()
                ->route('kuesioner.index')
                ->with('success', 'Kuesioner berhasil diperbarui!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan saat memperbarui data!', $th->getMessage()]]);
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