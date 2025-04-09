<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremApplicantRequest;
use App\Http\Requests\UpdatemApplicantRequest;
use App\Models\MApplicant;

class MApplicantController extends Controller
{
   
    public function index()
    {
        $m_applicants = MApplicant::all();
        // return view('test', compact('m_applicants'));
        return response()->json($m_applicants);
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
    public function store(StoremApplicantRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $applicant = mApplicant::find($id);
        return response()->json($applicant);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(mApplicant $mApplicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemApplicantRequest $request, mApplicant $mApplicant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mApplicant $mApplicant)
    {
        //
    }
}
