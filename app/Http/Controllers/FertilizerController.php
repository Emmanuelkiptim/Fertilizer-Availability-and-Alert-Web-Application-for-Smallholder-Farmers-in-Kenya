<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FertilizerController extends Controller
{
    /**
     * Helper: get the logged-in agrovet's ID (agrovets.id).
     */
    protected function currentAgrovetId()
    {
        $user = Auth::user();

        // Ensure the user has an agrovet profile
        if (!$user || !$user->agrovet) {
            abort(403, 'No agrovet profile linked to this user.');
        }

        return $user->agrovet->id; // agrovets.id
    }

    /**
     * List all fertilizers belonging to the logged-in agrovet.
     */
    public function index()
    {
        $agrovetId = $this->currentAgrovetId();

        $fertilizers = Fertilizer::where('agrovet_id', $agrovetId)
                                 ->orderBy('name')
                                 ->get();

        return view('agrovet.fertilizers.index', compact('fertilizers'));
    }

   
    public function create()
    {
        return view('agrovet.fertilizers.create');
    }

    /**
     * Save a new fertilizer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|string|max:255',
            'qty'          => 'required|integer|min:0',
            'price'        => 'required|numeric|min:0',
            // 'availability' => 'required|boolean',
        ]);

        $agrovetId = $this->currentAgrovetId();

        Fertilizer::create([
            'agrovet_id'   => $agrovetId,
            'name'         => $request->name,
            'type'         => $request->type,
            'qty'          => $request->qty,
            'price'        => $request->price,
            // 'availability' => $request->availability,
        ]);

        return redirect()->route('fertilizers.index')
                         ->with('success', 'Fertilizer added successfully.');
    }

    /**
     * Show a single fertilizer (details page).
     */
    public function show($id)
    {
        $agrovetId = $this->currentAgrovetId();

        $fertilizer = Fertilizer::where('agrovet_id', $agrovetId)
                                ->where('fertilizer_id', $id)
                                ->firstOrFail();

        return view('agrovet.fertilizers.show', compact('fertilizer'));
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $agrovetId = $this->currentAgrovetId();

        $fertilizer = Fertilizer::where('agrovet_id', $agrovetId)
                                ->where('fertilizer_id', $id)
                                ->firstOrFail();

        return view('agrovet.fertilizers.edit', compact('fertilizer'));
    }

    /**
     * Update the fertilizer.
     */
    public function update(Request $request, $id)
    {
        $agrovetId = $this->currentAgrovetId();

    $fertilizer = Fertilizer::where('agrovet_id', $agrovetId)
                ->where('fertilizer_id', $id)
                ->firstOrFail();

    // Store original values for comparison
    $original = $fertilizer->only(['name', 'type', 'qty', 'price']);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|string|max:255',
            'add_qty'      => 'nullable|integer|min:0',
            'price'        => 'required|numeric|min:0',
        ]);

        // Only add to qty if add_qty is provided and > 0
        $newQty = $fertilizer->qty;
        if (!empty($validated['add_qty']) && $validated['add_qty'] > 0) {
            $newQty += $validated['add_qty'];
        }

        $fertilizer->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'qty'  => $newQty,
            'price' => $validated['price'],
            'availability' => $newQty > 0 ? 1 : 0,
        ]);

        // Compare and build update details
        $changes = [];
        if ($original['price'] != $validated['price']) {
            $changes[] = 'Price changed from <b>Ksh ' . number_format($original['price'],2) . '</b> to <b>Ksh ' . number_format($validated['price'],2) . '</b>';
        }
        if ($original['qty'] != $newQty) {
            $changes[] = 'Stock changed from <b>' . $original['qty'] . '</b> to <b>' . $newQty . '</b>';
        }
        if ($original['name'] != $validated['name']) {
            $changes[] = 'Name changed from <b>' . $original['name'] . '</b> to <b>' . $validated['name'] . '</b>';
        }
        if ($original['type'] != $validated['type']) {
            $changes[] = 'Type changed from <b>' . $original['type'] . '</b> to <b>' . $validated['type'] . '</b>';
        }
        $details = count($changes) ? '<br><span style="color:#2563eb;">' . implode('<br>', $changes) . '</span>' : '';

        // Send alerts to all farmers who favorited this fertilizer
        $farmers = $fertilizer->favouritedBy;
        $fertilizerUrl = route('farmers.fertilizers.show', $fertilizer->fertilizer_id);
        foreach ($farmers as $farmer) {
            \App\Models\Alert::create([
                'farmer_id' => $farmer->id,
                'message' => 'Fertilizer "' . $fertilizer->name . '" has been updated.' . $details . ' <a href="' . $fertilizerUrl . '" class="alert-action-btn">View Fertilizer</a>',
            ]);
        }

        return redirect()->route('fertilizers.show', $fertilizer->fertilizer_id)
                         ->with('success', 'Fertilizer updated and alerts sent to favoriting farmers.');
    }
}
