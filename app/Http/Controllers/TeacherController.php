<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\User;
use App\Traits\HandlesPagination;
use Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Log;
use Session;

class TeacherController extends Controller
{
    use HandlesPagination;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []); // Filters array

        $query = User::where('user_type', 'teacher');

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

        $teachers = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return Inertia::render('Settings/Teachers', [
            'data' => $teachers->items(),
            'params' => [
                'total' => $teachers->total(),
                'per_page' => $teachers->perPage(),
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'next_page_url' => $teachers->nextPageUrl(),
                'prev_page_url' => $teachers->previousPageUrl(),
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
        return Inertia::render('Settings/CreateUser', ['userType' => 'teacher']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        $validated = $request->validated();

        $validated['user_type'] = User::$TEACHER_ROLE;
        $validated['password'] = Hash::make('123123'); // generate randow password
        $validated['full_name'] = $validated["first_name"] . " " . $validated["last_name"];


        $teacher = User::create($validated);

        $teacher->sendEmailVerificationNotification();

        Session::flash('message', ["content" => "Teacher created successfully.", "type" => "success"]);

        return redirect()->route('settings.teachers');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $teacher)
    {
        return Inertia::render('Settings/ShowUser', ['recordData' => $teacher->toArray()]);
    }

    /* /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        return Inertia::render('Settings/CreateUser', ['recordData' => $teacher->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $teacher, UpdateTeacherRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the teacher's details
        $teacher->update($validated);

        // If email was updated, send a new email verification notification
        if ($teacher->wasChanged('email')) {
            $teacher->sendEmailVerificationNotification();
        }
        Session::flash('message', ["content" => "Teacher details updated successfully.", "type" => "success"]);

        // Redirect to the teacher listing with a success message
        return redirect()->route('settings.teachers.show', ["teacher" => $teacher->getKey()]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {

        // Ensure the user is actually a teacher
        if ($teacher->user_type !== User::$TEACHER_ROLE) {
            return redirect()->route('settings.teachers')->with('message', ["content" => 'This user is not a teacher.', "type" => "error"]);
        }

        // Attempt to delete the teacher
        try {
            $teacher->delete();
            return redirect()->route('settings.teachers')->with('message', ["content" => 'Teacher deleted successfully.', "type" => "success"]);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Failed to delete the major: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('settings.teachers')->with('message', ["content" => 'Failed to delete the teacher.', "type" => "error"]);
        }

    }
}
