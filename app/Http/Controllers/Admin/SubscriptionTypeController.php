<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        $subscriptionTypes = SubscriptionType::latest()->paginate(15);
        return view('admin.subscription-types.index', compact('subscriptionTypes'));
    }

    public function create()
    {
        return view('admin.subscription-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $baseSlug = $validated['slug'];
        $counter = 1;
        while (SubscriptionType::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['is_active'] = $request->has('is_active');

        SubscriptionType::create($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement créé avec succès!');
    }

    public function edit(SubscriptionType $subscriptionType)
    {
        return view('admin.subscription-types.edit', compact('subscriptionType'));
    }

    public function update(Request $request, SubscriptionType $subscriptionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name if name changed
        if ($validated['name'] !== $subscriptionType->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure slug is unique (excluding current record)
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (SubscriptionType::where('slug', $validated['slug'])->where('id', '!=', $subscriptionType->id)->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['is_active'] = $request->has('is_active');

        $subscriptionType->update($validated);

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement mis à jour avec succès!');
    }

    public function destroy(SubscriptionType $subscriptionType)
    {
        // Check if subscription type is used in payments
        $paymentsCount = $subscriptionType->payments()->count();
        if ($paymentsCount > 0) {
            return redirect()->route('admin.subscription-types.index')
                ->with('error', 'Impossible de supprimer ce type d\'abonnement car il est utilisé dans ' . $paymentsCount . ' paiement(s).');
        }

        $subscriptionType->delete();

        return redirect()->route('admin.subscription-types.index')
            ->with('success', 'Type d\'abonnement supprimé avec succès!');
    }
}
