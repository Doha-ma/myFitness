<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Schedule;
use Illuminate\Http\Request;


class CoachController extends Controller
{
    public function dashboard()
    {
        $coach = auth()->user();
        $totalClasses = $coach->classesAsCoach()->count();
        $totalEnrollments = $coach->classesAsCoach()
            ->withCount('enrollments')
            ->get()
            ->sum('enrollments_count');

        // Load classes with enrollment counts for dynamic display
        // Enrollment counts update automatically via Eloquent relationships
        $classes = $coach->classesAsCoach()
            ->with('schedules')
            ->withCount('enrollments')
            ->get();

        return view('coach.dashboard', compact(
            'totalClasses',
            'totalEnrollments',
            'classes'
        ));
    }

    public function classesIndex()
    {
        $classes = auth()->user()->classesAsCoach()
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('coach.classes.index', compact('classes'));
    }

    public function classesCreate()
    {
        return view('coach.classes.create');
    }

    public function classesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'duration' => 'required|integer|min:15',
        ]);

        $class = ClassModel::create([
            ...$validated,
            'coach_id' => auth()->id(),
        ]);

        return redirect()->route('coach.classes.show', $class)
            ->with('success', 'Cours créé avec succès!');
    }

    public function classesShow(ClassModel $class)
    {
        if(auth()->user()->id !== $class->coach_id){
            abort(403, 'Unauthorized');
        }
        
        $class->load(['schedules', 'enrollments.member']);

        return view('coach.classes.show', compact('class'));
    }

    public function classesEdit(ClassModel $class)
    {
        if (auth()->id() !== $class->coach_id) {
            abort(403, 'Unauthorized');}

        return view('coach.classes.edit', compact('class'));
    }

    public function classesUpdate(Request $request, ClassModel $class)
    {
        if (auth()->id() !== $class->coach_id) {
            abort(403, 'Unauthorized');}


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'duration' => 'required|integer|min:15',
        ]);

        $class->update($validated);

        return redirect()->route('coach.classes.show', $class)
            ->with('success', 'Cours mis à jour avec succès!');
    }

    public function schedulesStore(Request $request, ClassModel $class)
    {
        if (auth()->id() !== $class->coach_id) {
            abort(403, 'Unauthorized');}

        $validated = $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $class->schedules()->create($validated);

        return redirect()->route('coach.classes.show', $class)
            ->with('success', 'Horaire ajouté avec succès!');
    }

    public function schedulesDestroy(ClassModel $class, Schedule $schedule)
    {
        if (auth()->id() !== $class->coach_id) {
            abort(403, 'Unauthorized');}

        if ($schedule->class_id !== $class->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('coach.classes.show', $class)
            ->with('success', 'Horaire supprimé avec succès!');
    }
}