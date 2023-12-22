<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Models\Event;


class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $slug)
    {
        try {
            $organizer = AuthController::checkLoginted($request);
            if (!$organizer) return redirect('/login');
            $event = Event::getInfor($organizer->id, $slug);
            if (!$event) return redirect('/error-404');
            return view('session.create', compact('organizer', 'event'));
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return view('error.500');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
