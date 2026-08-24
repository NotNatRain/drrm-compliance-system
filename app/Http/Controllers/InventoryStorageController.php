<?php

namespace App\Http\Controllers;

use App\Models\DefaultListItem;
use App\Models\InventoryStorage;
use App\Models\School;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryStorageController extends Controller
{
    /* ─────────────────────────────────────────────────────────────────
     |  CANONICAL ITEM CATALOGUE (used by Default List + PDF export)
     ──────────────────────────────────────────────────────────────── */
    private const ITEMS_A = [
        '2fold_aluminum_stretcher'           => '2-fold Aluminum Stretcher',
        'cadaver_bag'                        => 'Cadaver Bag',
        'c_collars'                          => 'C-Collars',
        'cot_battlefield_bed'                => 'Cot (Battlefield Bed)',
        'cpr_board'                          => 'CPR Board',
        'emergency_head_lamp'                => 'Emergency Head Lamp',
        'emergency_whistle'                  => 'Emergency Whistle',
        'fire_extinguisher'                  => 'Fire Extinguisher',
        'go_bag_learner'                     => 'Go Bag with Multi-Tool for each Learner',
        'go_bag_personnel'                   => 'Go Bag with Multi-Tool for each Personnel',
        'handheld_base_radios'               => 'Handheld / Base Radios',
        'led_search_light'                   => 'LED Search Light, 850 lumens',
        'life_vest'                          => 'Life Vest / Life Jacket',
        'medical_cushion'                    => 'Medical Cushion',
        'plastic_spine_board'                => 'Plastic Spine Board with Safety Belts',
        'portable_pa_system'                 => 'Portable P.A. System',
        'safety_coat'                        => 'Safety Coat',
        'safety_helmet'                      => 'Safety Helmet',
        'safety_shoes'                       => 'Safety Shoes',
        'splinter'                           => 'Splinter',
        'steel_boxes'                        => 'Steel Boxes',
        'steel_cabinets'                     => 'Steel Cabinets',
        'traffic_vest'                       => 'Traffic Vest',
        'transport_bags_45l'                 => 'Transport Bags, 45L',
        'trauma_bag'                         => 'Trauma Bag with Contents for 20–25 Persons',
        'universal_head_immobilizer'         => 'Universal Head Immobilizer',
    ];

    private const ITEMS_B = [
        'bicycle'       => 'Bicycle',
        'fire_hose'     => 'Fire Hose',
        'motor_banca'   => 'Motor Banca',
        'power_sprayer' => 'Power Sprayer',
    ];

    /** Public accessor for Blade templates */
    public static function getItemsCatalogue(string $section): array
    {
        return $section === 'A' ? self::ITEMS_A : self::ITEMS_B;
    }

    /* ─────────────────────────────────────────────────────────────────
     |  SCHOOL RESOLUTION  (mirrors FireSafetyController pattern)
     ──────────────────────────────────────────────────────────────── */
    private function resolveActiveSchool(): ?School
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return School::find($user->school_id);
        }

        $activeId = session('inventory_active_school_id');
        if ($activeId) {
            $s = School::find($activeId);
            if ($s) return $s;
        }

        return School::orderBy('school_name')->first();
    }

    private function getSchoolOptions(): \Illuminate\Support\Collection
    {
        if (auth()->user()->role === 'admin') {
            return School::orderBy('school_name')->get(['id', 'school_name', 'school_id']);
        }

        $s = School::find(auth()->user()->school_id, ['id', 'school_name', 'school_id']);
        return $s ? collect([$s]) : collect();
    }

    /* ─────────────────────────────────────────────────────────────────
     |  SET ACTIVE SCHOOL (admin only)
     ──────────────────────────────────────────────────────────────── */
    public function setSchool(Request $request)
    {
        $id = (int) $request->input('school_id');

        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        session(['inventory_active_school_id' => $id]);
        return response()->json(['success' => true]);
    }

    /* ─────────────────────────────────────────────────────────────────
     |  DASHBOARD
     ──────────────────────────────────────────────────────────────── */
    public function dashboard()
    {
        $school  = $this->resolveActiveSchool();
        $schools = $this->getSchoolOptions();
        $items   = $school ? InventoryStorage::where('school_id', $school->id)->latest()->get() : collect();

        return view('InventoryStorage.dashboard', compact('items', 'school', 'schools'));
    }

    /* ─────────────────────────────────────────────────────────────────
     |  DEFAULT LIST
     ──────────────────────────────────────────────────────────────── */
    public function defaultList()
    {
        return redirect()->route('inventory-storage.dashboard')
                         ->with('open_panel', 'default-list');
    }

    /* ─────────────────────────────────────────────────────────────────
     |  INVENTORY CRUD
     ──────────────────────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $school = $this->resolveActiveSchool();
        if (!$school) {
            return redirect()->back()->withErrors(['error' => 'No active school selected.']);
        }

        $validated = $request->validate([
            'item_name'     => 'required|string|max:255',
            'unit'          => 'nullable|string|max:50',
            'quantity'      => 'required|integer|min:0',
            'status'        => 'required|string',
            'location'      => 'nullable|string|max:255',
            'fund_source'   => 'nullable|string|max:255',
            'date_received' => 'nullable|date',
            'date_checked'  => 'nullable|date',
        ]);

        $validated['school_id'] = $school->id;
        InventoryStorage::create($validated);

        return redirect()->back()->with('success', 'Inventory item added successfully!');
    }

    public function update(Request $request, $id)
    {
        $item = InventoryStorage::findOrFail($id);

        $validated = $request->validate([
            'item_name'     => 'required|string|max:255',
            'unit'          => 'nullable|string|max:50',
            'quantity'      => 'required|integer|min:0',
            'status'        => 'required|string',
            'location'      => 'nullable|string|max:255',
            'fund_source'   => 'nullable|string|max:255',
            'date_received' => 'nullable|date',
            'date_checked'  => 'nullable|date',
        ]);

        $item->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item updated.']);
        }

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = InventoryStorage::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted.']);
    }

    /* ─────────────────────────────────────────────────────────────────
     |  DEFAULT LIST — SAVE COMPLIANCE DATA
     ──────────────────────────────────────────────────────────────── */
    public function saveDefaultList(Request $request)
    {
        $school = $this->resolveActiveSchool();

        if (!$school) {
            return response()->json(['success' => false, 'message' => 'No school selected.'], 422);
        }

        $section = strtoupper($request->input('section')); // 'A' or 'B'
        $rows    = $request->input('items', []);           // array of item data

        $catalogue = $section === 'A' ? self::ITEMS_A : self::ITEMS_B;

        foreach ($rows as $row) {
            $key = $row['item_key'] ?? null;
            if (!$key || !array_key_exists($key, $catalogue)) continue;

            DefaultListItem::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'section'   => $section,
                    'item_key'  => $key,
                ],
                [
                    'item_name'      => $catalogue[$key],
                    'has_item'       => (bool) ($row['has_item'] ?? false),
                    'quantity_owned' => (int)  ($row['quantity_owned'] ?? 0),
                    'source'         => $row['source'] ?? null,
                    'source_detail'  => $row['source_detail'] ?? null,
                    'date_checked'   => $row['date_checked'] ?? null,
                    'remarks'        => $row['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Section ' . $section . ' saved successfully.',
        ]);
    }

    /* ─────────────────────────────────────────────────────────────────
     |  PDF EXPORT
     ──────────────────────────────────────────────────────────────── */
    public function exportPdf(Request $request)
    {
        $school = $this->resolveActiveSchool();

        if (!$school) {
            abort(404, 'No school selected.');
        }

        // Load saved compliance rows keyed by [section][item_key]
        $saved = DefaultListItem::where('school_id', $school->id)
            ->get()
            ->groupBy('section')
            ->map(fn ($g) => $g->keyBy('item_key'));

        $inventoryItems = InventoryStorage::where('school_id', $school->id)->latest()->get();

        $pdf = Pdf::loadView('InventoryStorage.export_pdf', [
            'school'         => $school,
            'itemsA'         => self::ITEMS_A,
            'itemsB'         => self::ITEMS_B,
            'saved'          => $saved,
            'inventoryItems' => $inventoryItems,
            'generatedAt'    => now()->format('F d, Y g:i A'),
        ])->setPaper('a4', 'portrait');

        $filename = 'inventory-compliance-' . str_replace(' ', '-', strtolower($school->school_name)) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /* ─────────────────────────────────────────────────────────────────
     |  GET ITEM DATA (AJAX — for edit modal pre-fill)
     ──────────────────────────────────────────────────────────────── */
    public function getItem($id)
    {
        $item = InventoryStorage::findOrFail($id);
        return response()->json($item);
    }
}
