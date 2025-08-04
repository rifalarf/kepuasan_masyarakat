<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RespondenController extends Controller
{
    public function index(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('responden.index', array_merge($request->all(), $dates));
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->Where('gender', 'like', "%$searchTerm%")
                    ->orWhere('age', 'like', "%$searchTerm%")
                    ->orWhere('education', 'like', "%$searchTerm%")
                    ->orWhere('job', 'like', "%$searchTerm%")
                    ->orWhere('village_id', 'like', "%$searchTerm%")
                    ->orWhere('domicile', 'like', "%$searchTerm%");
            });
        }

        if (isset($request->age)) {
            if ($request->age == '0-19') {
                $age = [0, 19];
            } elseif ($request->age == '20-29') {
                $age = [20, 29];
            } elseif ($request->age == '30-39') {
                $age = [30, 39];
            } elseif ($request->age == '40-49') {
                $age = [40, 49];
            } elseif ($request->age == '50-59') {
                $age = [50, 59];
            } elseif ($request->age == '60-69') {
                $age = [60, 69];
            } elseif ($request->age == '>70') {
                $age = [70, 700];
            }
            $query->whereBetween('age', $age);
        }

        if (isset($request->gender)) {
            $query->where('gender', $request->gender);
        }

        if (isset($request->education)) {
            $query->where('education', $request->education);
        }

        if (isset($request->job)) {
            $query->where('job', $request->job);
        }

        if (isset($request->village)) {
            $query->where('village_id', $request->village);
        }

        if (isset($request->domicile)) {
            $query->where('domicile', $request->domicile);
        }


        $respondens = $query->latest()->paginate($request->per_page ?? 5);
        $villages = Village::all();

        return view('pages.dashboard.responden.index', compact('respondens', 'villages'));
    }

    public function show(Responden $responden)
    {
        return view('pages.dashboard.responden.show', compact('responden'));
    }
}
