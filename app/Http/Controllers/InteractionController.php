<?php

namespace App\Http\Controllers;

use App\Models\Employee\EmployeeDetail;
use App\Models\Interaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeDetail $employee): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $interactions = $employee->interactions()->orderBy('datetime', 'desc')->get();

        return view('interactions.index', compact('interactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'type' => 'required',
            'description' => 'required',
            'datetime' => 'required',
        ]);

        Interaction::create($validatedData);

        return redirect()->route('interactions.index')->with('success', 'Interaction added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
