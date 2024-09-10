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

    public const INDEX_ROUTE = 'settings.teachers';
    public const CREATE_ROUTE = self::INDEX_ROUTE . '.create';
    public const STORE_ROUTE = self::INDEX_ROUTE . '.store';
    public const SHOW_ROUTE = self::INDEX_ROUTE . '.show';
    public const EDIT_ROUTE = self::INDEX_ROUTE . '.edit';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const DESTROY_ROUTE = self::INDEX_ROUTE . '.destroy';
    public const DESTROY_MANY_ROUTE = self::INDEX_ROUTE . '.destroy-many';

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

        Session::flash('message', ["content" => trans('message.create_ok'), "type" => "success"]);

        return redirect()->route(self::INDEX_ROUTE);

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
        Session::flash('message', ["content" => trans('message.update_ok'), "type" => "success"]);
        // Redirect to the teacher listing with a success message
        return redirect()->route(self::SHOW_ROUTE, ["teacher" => $teacher->getKey()]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {

        // Ensure the user is actually a teacher
        if ($teacher->user_type !== User::$TEACHER_ROLE) {
            Session::flash('message', ["content" => trans('This user is not a student.'), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE);
        }

        // Attempt to delete the teacher
        try {
            $teacher->delete();
            Session::flash('message', ["content" => trans('message.delete_ok'), "type" => "success"]);
            return redirect()->route(self::INDEX_ROUTE);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Failed to delete the major: ' . $e->getMessage(), ['exception' => $e]);
            Session::flash('message', ["content" => trans('message.delete_fail'), "type" => "error"]);
            return redirect()->route(self::INDEX_ROUTE);
        }

    }
    public function destroyMany(Request $request)
    {
        // Retrieve the array of teacher IDs from the request
        $ids = $request->input('ids', []);

        // Ensure we have an array of teacher IDs
        if (empty($ids) || !is_array($ids)) {
            Session::flash('message', ["content" => trans('message.delete_empty', ['resource' => trans('noun.student')]), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE);
        }

        try {
            // Perform a batch deletion using the teacher IDs
            // Perform a batch deletion using the teacher IDs and get the count of deleted records
            $deletedCount = User::whereIn('id', $ids)
                ->where('user_type', User::$TEACHER_ROLE) // Ensure only teacher records are deleted
                ->delete();
            $message = trans('message.delete_ok', ['count' => $deletedCount, 'resource' => trans('noun.student')]);
            // Check if any teachers were actually deleted
            if ($deletedCount > 0) {
                Session::flash('message', ["content" => $message, "type" => "success"]);
                return redirect()->route(self::INDEX_ROUTE);
            } else {
                Session::flash('message', ["content" => $message, "type" => "error"]);
                return redirect()->route(self::INDEX_ROUTE);
            }
        } catch (\Exception $e) {
            Session::flash('message', ["content" => trans('message.delete_fail'), "type" => "error"]);

            return redirect()->route(self::INDEX_ROUTE)
                ->with('message', ['content' => 'Failed to delete selected teachers.', 'type' => 'error']);
        }
    }
}
