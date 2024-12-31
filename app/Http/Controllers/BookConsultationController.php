<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookConsultationController extends Controller
{
    /**
     * Show the form for creating a new consultation.
     */
    public function create()
    {
        return view('book-consultation');
    }

    /**
     * Store a newly created consultation in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified consultation.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified consultation.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified consultation in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified consultation from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
