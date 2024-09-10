<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Generation;
use App\Models\User;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;
use Session;

class StudentController extends Controller
{
    use HandlesPagination;

    public const INDEX_ROUTE = 'settings.students';
    public const CREATE_ROUTE = self::INDEX_ROUTE . '.create';
    public const STORE_ROUTE = self::INDEX_ROUTE . '.store';
    public const SHOW_ROUTE = self::INDEX_ROUTE . '.show';
    public const EDIT_ROUTE = self::INDEX_ROUTE . '.edit';
    public const EDIT_MANY_ROUTE = self::INDEX_ROUTE . '.edit-many';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const DESTROY_ROUTE = self::INDEX_ROUTE . '.destroy';
    public const DESTROY_MANY_ROUTE = self::INDEX_ROUTE . '.destroy-many';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []); // Filters array

        $query = User::where('user_type', 'student')->with(['generation']);

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
    {// Retrieve the list of all generations
        $generations = Generation::all(['id', 'title']); // Select only necessary fields (id and title)

        // Render the Inertia component with userType and generations as props
        return Inertia::render('Settings/CreateUser', [
            'generations' => $generations
        ]);
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

        Session::flash('message', ["content" => trans('message.create_ok'), "type" => "success"]);

        return redirect()->route(self::INDEX_ROUTE);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        // Ensure 'generation' relationship is eager-loaded to include generation details if needed
        $student->load('generation');
        return Inertia::render('Settings/ShowUser', ['recordData' => $student->toArray()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {// Retrieve the list of all generations
        $generations = Generation::all(['id', 'title']); // Select only necessary fields (id and title)

        // Render the Inertia component with userType and generations as props
        return Inertia::render('Settings/CreateUser', [
            'recordData' => $student->toArray(),
            'generations' => $generations
        ]);
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
        Session::flash('message', ["content" => trans('message.update_ok'), "type" => "success"]);

        // Redirect to the student listing with a success message
        return redirect()->route(self::SHOW_ROUTE, ["student" => $student->getKey()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        // Ensure the user is actually a student
        if ($student->user_type !== User::$STUDENT_ROLE) {
            Session::flash('message', ["content" => trans('This user is not a student.'), "type" => "error"]);
            return redirect()->route(self::INDEX_ROUTE);
        }

        // Attempt to delete the student
        try {
            $student->delete();
            Session::flash('message', ["content" => trans('message.delete_ok'), "type" => "success"]);

            return redirect()->route(self::INDEX_ROUTE);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Failed to delete the major: ' . $e->getMessage(), ['exception' => $e]);
            Session::flash('message', ["content" => trans('message.delete_fail'), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE)->with('error', trans());
        }
    }

    public function destroyMany(Request $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);

        // Ensure we have an array of student IDs
        if (empty($ids) || !is_array($ids)) {
            Session::flash('message', ["content" => trans('message.delete_empty', ['resource' => trans('noun.student')]), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE);
        }

        try {
            // Perform a batch deletion using the student IDs and get the count of deleted records
            $deletedCount = User::whereIn('id', $ids)
                ->where('user_type', User::$STUDENT_ROLE) // Ensure only student records are deleted
                ->delete();

            $message = trans('message.delete_ok', ['count' => $deletedCount, 'resource' => trans('noun.student')]);
            // Check if any students were actually deleted
            if ($deletedCount > 0) {
                Session::flash('message', ["content" => $message, "type" => "success"]);

                return redirect()->route(self::INDEX_ROUTE);
            } else {
                Session::flash('message', ["content" => $message, "type" => "error"]);

                return redirect()->route(self::INDEX_ROUTE);
            }

        } catch (\Exception $e) {
            Session::flash('message', ["content" => trans('message.delete_fail'), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE);
        }
    }


    public function editMany(Request $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);

        $selected = User::whereIn('id', $ids)
            ->where('user_type', User::$STUDENT_ROLE)->get();
        // Ensure we have an array of student IDs
        if (!is_array($ids) || empty($ids) || !$selected->count()) {
            Session::flash('message', ["content" => trans('message.update_empty', ['resource' => trans('noun.student')]), "type" => "error"]);
            return redirect()->route(self::INDEX_ROUTE);
        }

        $generations = Generation::all(['id', 'title']); // Select only necessary fields (id and title)

        return Inertia::render('Settings/EditManyUser', [
            'selectedUsers' => $selected->toArray(),
            'generations' => $generations
        ]);
    }

}
