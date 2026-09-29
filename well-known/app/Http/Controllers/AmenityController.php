<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    // Admin: List all amenities
    public function index()
    {
        $amenities = Amenity::orderBy('name')->get();
        return view('admin.amenities.index', compact('amenities'));
    }

    // Admin: Show create form
    public function create()
    {
        return view('admin.amenities.create');
    }

    // Admin: Store new amenity
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:amenities,name',
        ]);
        Amenity::create($data);
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity added successfully!');
    }

    // Admin: Show edit form
    public function edit($id)
    {
        $amenity = Amenity::findOrFail($id);
        return view('admin.amenities.edit', compact('amenity'));
    }

    // Admin: Update amenity
    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:amenities,name,' . $id,
        ]);
        $amenity->update($data);
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated successfully!');
    }

    // Admin: Delete amenity
    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted successfully!');
    }
}
