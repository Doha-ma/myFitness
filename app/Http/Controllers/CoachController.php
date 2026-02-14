<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\NewCourseCreated;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function dashboard(Request $request)
    {
        $coach = auth()->user();

        $classesQuery = $coach->classesAsCoach();
        $selectedClassId = $request->get('class_id');

        if ($selectedClassId) {
            $classesQuery->where('id', $selectedClassId);
        }

        // Show all coach classes (pending/approved/rejected) in dashboard filter and list.
        $allClasses = $coach->classesAsCoach()
            ->withCount('enrollments')
            ->latest()
            ->get();

        $classes = $classesQuery
            ->with(['schedules', 'enrollments.member'])
            ->withCount('enrollments')
            ->latest()
            ->get();

        $totalClasses = $allClasses->count();
        $totalEnrollments = $allClasses->sum('enrollments_count');

        $classStats = null;
        if ($selectedClassId) {
            $selectedClass = $classes->first();
            if ($selectedClass) {
                $classStats = [
                    'class' => $selectedClass,
                    'enrollment_count' => $selectedClass->enrollments_count,
                    'capacity_utilization' => $selectedClass->capacity > 0
                        ? round(($selectedClass->enrollments_count / $selectedClass->capacity) * 100, 1)
                        : 0,
                    'recent_enrollments' => $selectedClass->enrollments()
                        ->with('member')
                        ->latest('enrollment_date')
                        ->take(5)
                        ->get(),
                ];
            }
        }

        return view('coach.dashboard', compact(
            'totalClasses',
            'totalEnrollments',
            'classes',
            'allClasses',
            'selectedClassId',
            'classStats'
        ));
    }

    public function classesIndex()
    {
        // Show all statuses so newly created pending classes are visible to coach.
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
            'status' => 'pending',
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewCourseCreated($class, auth()->user()));
        }

        return redirect()->route('coach.classes.show', $class)
            ->with('success', 'Cours cree avec succes! En attente de validation par l administrateur.');
    }

    public function classesShow(ClassModel $classModel)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        $classModel->load(['schedules', 'enrollments.member']);

        return view('coach.classes.show', compact('classModel'));
    }

    public function classesEdit(ClassModel $classModel)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        return view('coach.classes.edit', compact('classModel'));
    }

    public function classesUpdate(Request $request, ClassModel $classModel)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'duration' => 'required|integer|min:15',
        ]);

        $classModel->update($validated);

        return redirect()->route('coach.classes.show', $classModel)
            ->with('success', 'Cours mis a jour avec succes!');
    }

    public function classesDestroy(ClassModel $classModel)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        try {
            $classModel->members()->detach();
            $classModel->schedules()->delete();
            $classModel->delete();

            return redirect()->route('coach.classes.index')
                ->with('success', 'Cours supprime avec succes!');
        } catch (\Exception $e) {
            \Log::error('Error deleting class: ' . $e->getMessage());

            return redirect()->route('coach.classes.index')
                ->with('error', 'Erreur lors de la suppression. Veuillez reessayer.');
        }
    }

    public function schedulesStore(Request $request, ClassModel $classModel)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $classModel->schedules()->create($validated);

        return redirect()->route('coach.classes.show', $classModel)
            ->with('success', 'Horaire ajoute avec succes!');
    }

    public function schedulesDestroy(ClassModel $classModel, Schedule $schedule)
    {
        if (auth()->id() !== $classModel->coach_id) {
            abort(403, 'Unauthorized');
        }

        if ($schedule->class_id !== $classModel->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('coach.classes.show', $classModel)
            ->with('success', 'Horaire supprime avec succes!');
    }
}

