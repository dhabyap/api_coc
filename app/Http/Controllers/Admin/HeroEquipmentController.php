<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroEquipment;
use Illuminate\Http\Request;

class HeroEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = HeroEquipment::all();
        return view('admin.hero_equipment.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero_equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:hero_equipments,name',
            'rarity' => 'required|string',
            'rank' => 'required|string',
            'reason' => 'nullable|string',
            'image_path' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        HeroEquipment::create($validated);

        return redirect()->route('admin.hero-equipments.index')
            ->with('success', 'Hero Equipment created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroEquipment $heroEquipment)
    {
        return view('admin.hero_equipment.edit', compact('heroEquipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroEquipment $heroEquipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:hero_equipments,name,' . $heroEquipment->id,
            'rarity' => 'required|string',
            'rank' => 'required|string',
            'reason' => 'nullable|string',
            'image_path' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $heroEquipment->update($validated);

        return redirect()->route('admin.hero-equipments.index')
            ->with('success', 'Hero Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroEquipment $heroEquipment)
    {
        $heroEquipment->delete();

        return redirect()->route('admin.hero-equipments.index')
            ->with('success', 'Hero Equipment deleted successfully.');
    }
}
