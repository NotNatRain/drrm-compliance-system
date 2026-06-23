<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryStorage;
use Illuminate\Support\Facades\Auth;

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
            'fund_source_other' => 'nullable|string|max:255',
            'date_received' => 'nullable|date',
            'date_checked' => 'nullable|date',
        ]);

        // Handle "Others" fund source if selected
        if ($validated['fund_source'] === 'Others' && !empty($validated['fund_source_other'])) {
            $validated['fund_source'] = $validated['fund_source_other'];
        }
        
        unset($validated['fund_source_other']);

        InventoryStorage::create($validated);

        return redirect()->back()->with('success', 'Inventory item added successfully!');
    }
}
