<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDashboardRequest;
use App\Models\Dashboard;
use App\Services\Models\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Dashboards/Index', [
            'dashboards' => Dashboard::filterByCurrentUser()
                ->orderBy('title')
                ->get()
                ->transform(fn(Dashboard $dashboard) => [
                    'id' => $dashboard->id,
                    'title' => $dashboard->title,
                    'description' => $dashboard->description,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Dashboards/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDashboardRequest $request
     * @param DashboardService $dashboardService
     * @return RedirectResponse
     */
    public function store(StoreDashboardRequest $request, DashboardService $dashboardService)
    {
        $validated = $request->validated();

        $dashboardService->store($validated['title'], $validated['description']);

        return to_route('dashboards.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Dashboard $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Dashboard $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Dashboard $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Dashboard $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
