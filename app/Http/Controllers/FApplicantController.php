<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorefApplicantRequest;
use App\Http\Requests\UpdatefApplicantRequest;
use App\Models\FApplicant;

class FApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $m_applicants = fApplicant::all();
        return response()->json($m_applicants);

    }

    public function getAvailableFApplicants($maleApplicantId)
    {
        return FApplicant::whereDoesntHave('connections', function ($query) {
            $query->whereIn('status', ['انتظار الإجابة', 'انتظار الإرسال']);
        })->whereDoesntHave('connections', function ($query) use ($maleApplicantId) {
            $query->where('m_applicant_id', $maleApplicantId)
                  ->where('status', 'عدم توافق');
        })->get();
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
    public function store(StorefApplicantRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $applicant = fApplicant::find($id);
        return response()->json($applicant);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(fApplicant $fApplicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefApplicantRequest $request, fApplicant $fApplicant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(fApplicant $fApplicant)
    {
        //
    }
}
