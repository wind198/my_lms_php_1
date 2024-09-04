<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Session;

class StudentController extends Controller
{
    use HandlesPagination;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []); // Filters array

        $query = User::where('user_type', 'student');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($query) use ($q) {
                $query->where('email', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['created_at']['gte'])) {
            $query->where('created_at', '>=', $filters['created_at']['gte']);
        }

        if (!empty($filters['created_at']['lte'])) {
            $query->where('created_at', '<=', $filters['created_at']['lte']);
        }

        $order = $request->query('order', 'desc');
        $orderBy = $request->query('order_by', 'created_at');

        $students = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return Inertia::render('Settings/Students', [
            'data' => $students->items(),
            'params' => [
                'total' => $students->total(),
                'per_page' => $students->perPage(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'next_page_url' => $students->nextPageUrl(),
                'prev_page_url' => $students->previousPageUrl(),
                'order' => $order,
                'order_by' => $orderBy,
                'filters' => $filters,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Settings/CreateUser', ['userType' => 'student']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        $validated['user_type'] = User::$STUDENT_ROLE;
        $validated['password'] = User::generateRandomPassword(); // generate randow password

        $student = User::create($validated);

        $student->sendEmailVerificationNotification();

        Session::flash('message', ["content" => "Student created successfully.", "type" => "success"]);

        return redirect()->route('settings.students');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        return Inertia::render('Settings/ShowUser', ['recordData' => $student->toArray()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        return Inertia::render('Settings/CreateUser', ['recordData' => $student->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $student, UpdateStudentRequest $request, )
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the student's details
        $student->update($validated);

        // If email was updated, send a new email verification notification
        if ($student->wasChanged('email')) {
            $student->sendEmailVerificationNotification();
        }
        Session::flash('message', ["content" => "Student details updated successfully.", "type" => "success"]);

        // Redirect to the student listing with a success message
        return redirect()->route('settings.students.show', ["student" => $student->getKey()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        // Ensure the user is actually a student
        if ($student->user_type !== User::$STUDENT_ROLE) {
            return redirect()->route('settings.students')->with('error', 'This user is not a student.');
        }

        // Attempt to delete the student
        try {
            $student->delete();
            return redirect()->route('settings.students')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.students')->with('error', 'Failed to delete the student.');
        }
    }

}
