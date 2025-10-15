<?php

namespace App\Http\Controllers;

use App\Models\Disagreement;
use Illuminate\Http\Request;

class DisagreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disagreements = Disagreement::with(['mApplicant', 'fApplicant'])->get();
    
        $data = $disagreements->map(function ($disagreement) {
            return [
                'male' => [
                    'id' => $disagreement->mApplicant->id,
                    'full_name' => $disagreement->mApplicant->full_name ?? '',
                    'phone' => $disagreement->mApplicant->phone ?? '',
                    'city' => $disagreement->mApplicant->city ?? ''
                ],
                'female' => [
                    'id' => $disagreement->fApplicant->id,
                    'full_name' => $disagreement->fApplicant->full_name ?? '',
                    'phone' => $disagreement->fApplicant->phone ?? '',
                    'city' => $disagreement->fApplicant->city ?? ''
                ],
                'created_at' => $disagreement->created_at,
                'updated_at' => $disagreement->updated_at
            ];
        });

    
        return response()->json(  $data, 200);
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
