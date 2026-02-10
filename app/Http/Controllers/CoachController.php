<?php



namespace App\Http\Controllers;



use App\Models\ClassModel;

use App\Models\Schedule;

use Illuminate\Http\Request;



class CoachController extends Controller

{

    public function dashboard(Request $request)

    {

        $coach = auth()->user();

        

        // Base query for coach's classes

        $classesQuery = $coach->classesAsCoach();

        

        // Apply class filter if selected

        $selectedClassId = $request->get('class_id');

        if ($selectedClassId) {

            $classesQuery->where('id', $selectedClassId);

        }

        

        // Get all coach's classes for filter dropdown

        $allClasses = $coach->classesAsCoach()->withCount('enrollments')->where('status', 'approved')->get();

        

        // Get filtered classes with statistics

        $classes = $classesQuery->with(['schedules', 'enrollments.member'])

            ->withCount('enrollments')

            ->where('status', 'approved') // Only show approved courses

            ->get();

        

        // Calculate statistics

        $totalClasses = $allClasses->count();

        $totalEnrollments = $allClasses->sum('enrollments_count');

        

        // Class-specific statistics if filter is applied

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

                        ->get()

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

        $classes = auth()->user()->classesAsCoach()

            ->withCount('enrollments')

            ->where('status', 'approved') // Only show approved courses

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

            'status' => 'pending', // Set status as pending by default

        ]);



        // Send notification to admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\NewCourseCreated($class, auth()->user()));
        }



        return redirect()->route('coach.classes.show', $class)

            ->with('success', 'Cours créé avec succès! En attente de validation par l\'administrateur.');

    }



    public function classesShow(ClassModel $classModel)

    {

        if(auth()->user()->id !== $classModel->coach_id){

            abort(403, 'Unauthorized');

        }

        

        $classModel->load(['schedules', 'enrollments.member']);



        return view('coach.classes.show', compact('classModel'));

    }



    public function classesEdit(ClassModel $classModel)

    {

        if (auth()->id() !== $classModel->coach_id) {

            abort(403, 'Unauthorized');}



        return view('coach.classes.edit', compact('classModel'));

    }



    public function classesUpdate(Request $request, ClassModel $classModel)

    {

        if (auth()->id() !== $classModel->coach_id) {

            abort(403, 'Unauthorized');}





        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'capacity' => 'required|integer|min:1',

            'duration' => 'required|integer|min:15',

        ]);



        $classModel->update($validated);



        return redirect()->route('coach.classes.show', $classModel)

            ->with('success', 'Cours mis à jour avec succès!');

    }



    /**

     * Delete a class

     * Only the coach who owns the class can delete it

     * Detaches all enrolled members before deletion

     */

    public function classesDestroy(ClassModel $classModel)

    {

        // Verify ownership

        if (auth()->id() !== $classModel->coach_id) {

            abort(403, 'Unauthorized');

        }



        try {

            // Detach all members from this class using existing many-to-many relationship

            // This removes records from enrollments pivot table

            $classModel->members()->detach();

            

            // Delete all schedules for this class

            $classModel->schedules()->delete();

            

            // Delete the class

            // Note: Enrollments are already detached, so no orphan records

            $classModel->delete();

            

            return redirect()->route('coach.classes.index')

                ->with('success', 'Cours supprimé avec succès!');

        } catch (\Exception $e) {

            // Log error and return with message

            \Log::error('Error deleting class: ' . $e->getMessage());

            return redirect()->route('coach.classes.index')

                ->with('error', 'Erreur lors de la suppression. Veuillez réessayer.');

        }

    }



    public function schedulesStore(Request $request, ClassModel $classModel)

    {

        if (auth()->id() !== $classModel->coach_id) {

            abort(403, 'Unauthorized');}



        $validated = $request->validate([

            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',

            'start_time' => 'required|date_format:H:i',

            'end_time' => 'required|date_format:H:i|after:start_time',

        ]);



        $classModel->schedules()->create($validated);



        return redirect()->route('coach.classes.show', $classModel)

            ->with('success', 'Horaire ajouté avec succès!');

    }



    public function schedulesDestroy(ClassModel $classModel, Schedule $schedule)

    {

        if (auth()->id() !== $classModel->coach_id) {

            abort(403, 'Unauthorized');}



        if ($schedule->class_id !== $classModel->id) {

            abort(403);

        }



        $schedule->delete();



        return redirect()->route('coach.classes.show', $classModel)

            ->with('success', 'Horaire supprimé avec succès!');

    }

}