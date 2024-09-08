<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Major;
use App\Traits\HandlesPagination;
use Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Log;
use Session;

class MajorController extends Controller
{
    use HandlesPagination;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []); // Filters array

        $query = Major::query();

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

        $majors = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return Inertia::render('Study/Majors', [
            'data' => $majors->items(),
            'params' => [
                'total' => $majors->total(),
                'per_page' => $majors->perPage(),
                'current_page' => $majors->currentPage(),
                'last_page' => $majors->lastPage(),
                'next_page_url' => $majors->nextPageUrl(),
                'prev_page_url' => $majors->previousPageUrl(),
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
        return Inertia::render('Study/CreateMajor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMajorRequest $request)
    {
        $validated = $request->validated();

        $major = Major::create($validated);

        $major->sendEmailVerificationNotification();

        Session::flash('message', ["content" => "Major created successfully.", "type" => "success"]);

        return redirect()->route('study.majors');

    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        return Inertia::render('Study/ShowMajor', ['recordData' => $major->toArray()]);
    }

    /* /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return Inertia::render('Study/CreateMajor', ['recordData' => $major->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Major $major, UpdateMajorRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the major's details
        $major->update($validated);


        Session::flash('message', ["content" => "Major details updated successfully.", "type" => "success"]);

        // Redirect to the major listing with a success message
        return redirect()->route('study.majors.show', ["major" => $major->getKey()]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        try {
            // Attempt to delete the major
            $major->delete();

            return redirect()->route('study.majors')->with('message', [
                "content" => 'Major deleted successfully.',
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Failed to delete the major: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('study.majors')->with('message', [
                "content" => 'Failed to delete the major. Please try again later.',
                "type" => "error"
            ]);
        }
    }
}
