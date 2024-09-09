<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenerationRequest;
use App\Http\Requests\UpdateGenerationRequest;
use App\Models\Generation;
use App\Models\User;
use App\Traits\HandlesPagination;
use DB;
use Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Log;
use Session;

class GenerationController extends Controller
{
    use HandlesPagination;

    public const INDEX_ROUTE = 'settings.generations';
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

        $query = Generation::query()->withCount([
            'students' => function ($query) {
                $query->where('user_type', User::$STUDENT_ROLE);
            }
        ]); // This adds the student_count field

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

        $generations = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return Inertia::render('Settings/Generations', [
            'data' => $generations->items(),
            'params' => [
                'total' => $generations->total(),
                'per_page' => $generations->perPage(),
                'current_page' => $generations->currentPage(),
                'last_page' => $generations->lastPage(),
                'next_page_url' => $generations->nextPageUrl(),
                'prev_page_url' => $generations->previousPageUrl(),
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
        return Inertia::render('Settings/CreateGeneration');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenerationRequest $request)
    {
        $validated = $request->validated();

        $generation = Generation::create($validated);

        Session::flash('message', ["content" => "Generation created successfully.", "type" => "success"]);

        return redirect()->route(self::INDEX_ROUTE);

    }

    /**
     * Display the specified resource.
     */
    public function show(Generation $generation)
    {
        return Inertia::render('Settings/ShowGeneration', ['recordData' => $generation->toArray()]);
    }

    /* /**
     * Show the form for editing the specified resource.
     */
    public function edit(Generation $generation)
    {
        return Inertia::render('Settings/CreateGeneration', ['recordData' => $generation->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Generation $generation, UpdateGenerationRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the generation's details
        $generation->update($validated);


        Session::flash('message', ["content" => "Generation details updated successfully.", "type" => "success"]);

        // Redirect to the generation listing with a success message
        return redirect()->route(self::SHOW_ROUTE, ["generation" => $generation->getKey()]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Generation $generation)
    {
        // Check if the generation has students associated with it
        $studentsCount = $generation->students()->count();

        // Begin a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // If there are students associated with this generation, nullify the relationship
            if ($studentsCount > 0) {
                $generation->students()->update(['generation_id' => null]);
            }

            // Now delete the generation
            $generation->delete();

            // Commit the transaction
            DB::commit();


            if ($studentsCount > 0) {
                Session::flash('message', ["content" => "$studentsCount students were disassociated from this generation.", "type" => "success"]);
            } else {
                Session::flash('message', ["content" => "Generation deleted successfully.", "type" => "success"]);
            }

            return redirect()->route(self::INDEX_ROUTE);

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            // Log the exception for debugging purposes
            Log::error('Failed to delete the generation: ' . $e->getMessage(), ['exception' => $e]);

            // Return an error message
            return redirect()->route(self::INDEX_ROUTE)->with('message', [
                "content" => 'Failed to delete the generation. Please try again later.',
                "type" => "error"
            ]);
        }
    }

}
