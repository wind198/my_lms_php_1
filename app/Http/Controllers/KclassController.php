<?php

namespace App\Http\Controllers;

use App\Models\Kclass;
use App\Models\Klass;
use App\Http\Requests\StoreKlassRequest;
use App\Http\Requests\UpdateKclassRequest;
use App\Models\Major;
use App\Models\User;
use App\Traits\HandlesPagination;
use Inertia\Inertia;
use Log;
use Request;
use Session;

class KclassController extends Controller
{
    use HandlesPagination;

    public const INDEX_ROUTE = 'study.kclasses';
    public const CREATE_ROUTE = self::INDEX_ROUTE . '.create';
    public const STORE_ROUTE = self::INDEX_ROUTE . '.store';
    public const SHOW_ROUTE = self::INDEX_ROUTE . '.show';
    public const EDIT_ROUTE = self::INDEX_ROUTE . '.edit';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const DESTROY_ROUTE = self::INDEX_ROUTE . '.destroy';



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters = $request->input('filters', []); // Filters array

        $query = Kclass::query()->with(['mainTeacher', 'course'])->withCount(['students']);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
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

        $courses = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return Inertia::render('Study/Courses', [
            'data' => $courses->items(),
            'params' => [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'next_page_url' => $courses->nextPageUrl(),
                'prev_page_url' => $courses->previousPageUrl(),
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
        $courses = Major::all(['id', 'title']);
        $teachers = User::all(['id', 'full_name'])->where('user_type', User::$TEACHER_ROLE);
        return Inertia::render('Study/CreateKclass', ['courses' => $courses, 'teachers' => $teachers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKlassRequest $request)
    {
        $validated = $request->validated();

        $kclass = Kclass::create($validated);

        Session::flash('message', ["content" => trans('message.create_ok'), "type" => "success"]);
        return redirect()->route(self::INDEX_ROUTE);

    }

    /**
     * Display the specified resource.
     */
    public function show(Kclass $kclass)
    {
        $kclass->load(['mainTeacher', 'course'])->loadCount(['students']);
        return Inertia::render('Study/ShowCourse', ['recordData' => $kclass->toArray(),]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kclass $kclass)
    {
        $kclass->load(['mainTeacher', 'course'])->loadCount(['students']);
        $courses = Major::all(['id', 'title']);
        $teachers = User::all(['id', 'full_name'])->where('user_type', User::$TEACHER_ROLE);
        return Inertia::render('Study/CreateCourse', ['recordData' => $kclass->toArray(), 'courses' => $courses, 'teachers' => $teachers]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKclassRequest $request, Kclass $kclass)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the course's details
        $kclass->update($validated);

        Session::flash('message', ["content" => trans('message.update_ok'), "type" => "success"]);
        // Redirect to the course listing with a success message
        return redirect()->route(self::SHOW_ROUTE, ["course" => $kclass->getKey()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kclass $kclass)
    {
        try {
            // Attempt to delete the course
            $kclass->delete();
            Session::flash('message', ["content" => trans('message.delete_ok'), "type" => "success"]);
            return redirect()->route(self::INDEX_ROUTE)->with('message', [
                "content" => 'Course deleted successfully.',
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Failed to delete the kclass: ' . $e->getMessage(), ['exception' => $e]);
            Session::flash('message', ["content" => trans('message.delete_fail'), "type" => "error"]);
            return redirect()->route(self::INDEX_ROUTE)->with('message', [
                "content" => 'Failed to delete the kclass. Please try again later.',
                "type" => "error"
            ]);
        }
    }
}
