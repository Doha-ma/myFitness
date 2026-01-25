<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        $totalMembers = Member::count();
        $totalPaymentsToday = Payment::whereDate('payment_date', today())->sum('amount');
        $recentMembers = Member::latest()->take(5)->get();

        return view('receptionist.dashboard', compact(
            'totalMembers',
            'totalPaymentsToday',
            'recentMembers'
        ));
    }

    public function membersIndex()
    {
        $members = Member::latest()->paginate(15);
        return view('receptionist.members.index', compact('members'));
    }

    public function membersCreate()
    {
        return view('receptionist.members.create');
    }

    public function membersStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        Member::create($validated);

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Membre ajouté avec succès!');
    }

    public function membersEdit(Member $member)
    {
        return view('receptionist.members.edit', compact('member'));
    }

    public function membersUpdate(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return redirect()->route('receptionist.members.index')
            ->with('success', 'Membre mis à jour avec succès!');
    }

    public function paymentsIndex()
    {
        $payments = Payment::with(['member', 'receptionist'])
            ->latest()
            ->paginate(15);

        return view('receptionist.payments.index', compact('payments'));
    }

    public function paymentsCreate()
    {
        $members = Member::where('status', 'active')->get();
        return view('receptionist.payments.create', compact('members'));
    }

    public function paymentsStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

        Payment::create([
            ...$validated,
            'receptionist_id' => auth()->id(),
        ]);

        return redirect()->route('receptionist.payments.index')
            ->with('success', 'Paiement enregistré avec succès!');
    }
}
