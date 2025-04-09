<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateconnectionRequest;
use App\Models\MApplicant;
use App\Models\Connection;
use App\Models\FApplicant;
use App\Models\MMarried;
use App\Models\FMarried;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $connections = Connection::with([
            'mApplicant:id,full_name,phone,city',
            'fApplicant:id,full_name,phone,city'
        ])->get();
    
        $data = $connections->map(function ($connection) {
            return [
                'id' => $connection->id,
                'm_applicant' => [
                    'id' => $connection->mApplicant->id,
                    'full_name' => $connection->mApplicant->full_name ?? '',
                    'phone' => $connection->mApplicant->phone ?? '',
                    'city' => $connection->mApplicant->city ?? ''
                ],
                'f_applicant' => [
                    'id' => $connection->fApplicant->id,
                    'full_name' => $connection->fApplicant->full_name ?? '',
                    'phone' => $connection->fApplicant->phone ?? '',
                    'city' => $connection->fApplicant->city ?? ''
                ],
                'connection_date' => $connection->connection_date,
                'status' => $connection->status,
                'remarks' => $connection->remarks,
                'created_at' => $connection->created_at,
                'updated_at' => $connection->updated_at
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
        $validatedData = $request->validate([
            'm_applicant' => 'required|integer|exists:m_applicants,id',
            'f_applicant' => 'required|integer|exists:f_applicants,id',
        ]);

        $maleId = $validatedData['m_applicant'];
        $femaleId = $validatedData['f_applicant'];

        // Check if the connection already exists
        $existingConnection = DB::table('connections')
            ->where('m_applicant_id', $maleId)
            ->where('f_applicant_id', $femaleId)
            ->first();

        if ($existingConnection) {
            return response()->json(-3); // Already connected
        }

        $maleApplicant = MApplicant::find($maleId);
        // Attach new connection
        MApplicant::find($maleId)->connections()->attach($femaleId, [
            'connection_date' => now(),
            'remarks' => ''
        ]);

        $femaleApplicant = FApplicant::find($femaleId);
        
        $femaleApplicant->document_status = false;
        $femaleApplicant->save();

        $maleApplicant->document_status = false;
        $maleApplicant->save();

        return response()->json(1); // Success
    }

    /**
     * Display the specified resource.
     */
    public function show(connection $connection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(connection $connection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        if ($request->status == 'توافق') {
            $connection = Connection::findOrFail($id);

            // Copy m_applicant data to m_marrieds
            $mMarried = new MMarried();
            $mMarried->fill($connection->mApplicant->toArray());
            $mMarried->save();

            // Copy f_applicant data to f_marrieds
            $fMarried = new FMarried();
            $fMarried->fill($connection->fApplicant->toArray());
            $fMarried->m_married_id = $mMarried->id;
            $fMarried->save();

            // Delete the connection
            $connection->delete();

            // Delete the m_applicant and f_applicant
            $connection->mApplicant->delete();
            $connection->fApplicant->delete();

            return response()->json(['status' => "success"]);
        }
    
        $connection = Connection::findOrFail($id);

        $data = $request->only($connection->getFillable());



        $connection->update($data);

        if ($request->status == 'عدم توافق') {
            $connection->mApplicant->document_status = true;
            $connection->mApplicant->save();
        }

        
        return response()->json(['data' => $connection]);
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(connection $connection)
    {
        //
    }
}
