<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FMarried;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agreements = FMarried::with('mMarried')->get();
    
        $data = $agreements->map(function ($agreement) {
            return [
                'male' => [
                    'id' => $agreement->mMarried->id,
                    'full_name' => $agreement->mMarried->full_name ?? '',
                    'phone' => $agreement->mMarried->phone ?? '',
                    'city' => $agreement->mMarried->city ?? ''
                ],
                'female' => [
                    'id' => $agreement->id,
                    'full_name' => $agreement->full_name ?? '',
                    'phone' => $agreement->phone ?? '',
                    'city' => $agreement->city ?? ''
                ],
                'created_at' => $agreement->created_at,
                'updated_at' => $agreement->updated_at
            ];
        });

    
        return response()->json($data);
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
