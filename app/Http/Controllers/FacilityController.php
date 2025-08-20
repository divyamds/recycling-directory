<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Material;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
   public function index(Request $request)
{
    $query = Facility::with('materials');

    // 🔍 Search by business name, city, or material name
    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('business_name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhereHas('materials', function ($mq) use ($search) {
                  $mq->where('materials.name', 'like', "%{$search}%");
              });
        });
    }

    // 🎯 Filter by material (dropdown)
    if ($request->filled('material_id')) {
        $query->whereHas('materials', function ($mq) use ($request) {
            $mq->where('materials.id', $request->material_id);
        });
    }

    // 📅 Sort by last update
    if ($request->input('sort') === 'last_update') {
        $query->orderBy('last_update_date', 'desc');
    } else {
        $query->orderBy('business_name', 'asc');
    }

    $facilities = $query->paginate(10);
    $materials = Material::all();

    return view('facilities.index', compact('facilities', 'materials'));
}


    public function create()
    {
        $materials = Material::all();
        return view('facilities.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'last_update_date' => 'required|date',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'materials' => 'array',
        ]);

        $facility = Facility::create($request->only([
            'business_name',
            'last_update_date',
            'street_address',
            'city',
            'state',
            'postal_code',
        ]));

        if ($request->has('materials')) {
            $facility->materials()->sync($request->materials);
        }

        return redirect()->route('facilities.index')->with('success', 'Facility created successfully.');
    }

    public function show(Facility $facility)
    {
        $facility->load('materials');
        return view('facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        $materials = Material::all();
        $facility->load('materials');
        return view('facilities.edit', compact('facility', 'materials'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'last_update_date' => 'required|date',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'materials' => 'array',
        ]);

        $facility->update($request->only([
            'business_name',
            'last_update_date',
            'street_address',
            'city',
            'state',
            'postal_code',
        ]));

        if ($request->has('materials')) {
            $facility->materials()->sync($request->materials);
        }

        return redirect()->route('facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->materials()->detach();
        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Facility deleted successfully.');
    }

    // 📥 CSV Export
    public function export(Request $request)
    {
        $query = Facility::with('materials');

        // Apply same search filter
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('materials', function ($mq) use ($search) {
                      $mq->where('materials.name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply same material filter
        if ($request->filled('material_id')) {
            $query->whereHas('materials', function ($mq) use ($request) {
                $mq->where('materials.id', $request->material_id);
            });
        }

        $facilities = $query->get();

        $filename = "facilities_export.csv";
        $handle = fopen($filename, 'w+');

        fputcsv($handle, ['Business Name', 'Last Update', 'Street Address', 'City', 'State', 'Postal Code', 'Materials']);

        foreach ($facilities as $facility) {
            fputcsv($handle, [
                $facility->business_name,
                $facility->last_update_date,
                $facility->street_address,
                $facility->city,
                $facility->state,
                $facility->postal_code,
                implode(', ', $facility->materials->pluck('name')->toArray())
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
