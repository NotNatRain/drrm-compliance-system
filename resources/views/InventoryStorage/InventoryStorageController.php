<?php

namespace App\Http\Controllers;

use App\Models\InventoryStorage;
use Illuminate\Http\Request;

class InventoryStorageController extends Controller
{
    public function dashboard()
    {
        $items = InventoryStorage::latest()->get();
        return view('InventoryStorage.dashboard', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|string',
            'location' => 'nullable|string|max:255',
            'fund_source' => 'nullable|string|max:255',
            'date_received' => 'nullable|date',
            'date_checked' => 'nullable|date',
        ]);

        // Handle cases where "Others" text input might be used instead of the select
        $data = $validated;
        
        InventoryStorage::create($data);

        return redirect()->back()->with('success', 'Inventory item added successfully!');
    }
}