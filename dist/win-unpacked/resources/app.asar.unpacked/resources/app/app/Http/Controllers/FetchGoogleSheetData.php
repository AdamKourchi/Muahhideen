<?php

namespace App\Http\Controllers;

use App\Models\GoogleData;
use App\Models\MApplicant;
use App\Models\FApplicant;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;

class FetchGoogleSheetData extends Controller
{
    function fetchMaleData()
    {
        $sheetData = GoogleData::where("sheet_name", "male")->first();



        $client = new Google_Client();

        $client->setAuthConfig(storage_path($sheetData->auth_config_file));

        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $sheetData->spreadsheetId;
        $range = $sheetData->range;

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        if (empty($values)) {

            return response()->json("No data found.");
        }



        foreach ($values as $row) {
            $data = [
                'full_name' => $row[0] ?? null,
                'city' => $row[1] ?? null,
                'marital_status' => $row[2] ?? null,
                'age' => $row[3] ?? null,
                'phone' => $row[4] ?? null,
                'job' => $row[5] ?? null,
                'height' => isset($row[6]) ? (float) $row[6] : null,
                'weight' => isset($row[7]) ? (float) $row[7] : null,
                'skin_color' => $row[8] ?? null,
                'education_level' => $row[9] ?? null,
                'application_date' => $row[10] ?? null,
                'document_status' => isset($row[11]) ? (bool) $row[11] : true,
                'has_beard' => isset($row[12]) ? (bool) $row[12] : false,
                'creed' => $row[13] ?? null,
                'prays_with_jamaah' => isset($row[14]) ? (bool) $row[14] : false,
                'scholars_you_follow' => $row[15] ?? null,
                'medical_illness' => $row[16] ?? null,
                'housing_situation' => $row[17] ?? null,
                'accepts_working_wife' => isset($row[18]) ? (bool) $row[18] : false,
                'preferred_cities_for_marriage' => isset($row[19]) ? json_decode($row[19], true) : null,
                'accepts_divorced_or_widowed' => isset($row[20]) ? (bool) $row[20] : false,
                'maximum_accepted_age' => isset($row[21]) ? (int) $row[21] : null,
                'partner_requirements' => $row[22] ?? null,
                'message_to_partner' => $row[23] ?? null,
            ];


            // Generate a unique hash for the row
            $rowHash = md5(json_encode($data));

            // Check if the hash already exists
            if (MApplicant::where('row_hash', $rowHash)->exists()) {
                $data['row_hash'] = $rowHash;
                MApplicant::create($data);
                $insertedCount++;
            }
        }


        return response()->json([
            'status' => 'success',
            'inserted' => $insertedCount,
        ]);
    }
    function fetchFemaleData()
    {
        $sheetData = GoogleData::where("sheet_name", "female")->first();
    
        if (!$sheetData) {
            return response()->json("No sheet data found for 'female'.", 404);
        }
    
        $client = new Google_Client();
        $client->setAuthConfig(storage_path($sheetData->auth_config_file));
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
    
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $sheetData->spreadsheetId;
        $range = $sheetData->range;
    
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
    
        if (empty($values)) {
            return response()->json("No data found.");
        }
    
        $insertedCount = 0;
    
        foreach ($values as $row) {
            $data = [
                'full_name' => $row[0] ?? null,
                'city' => $row[1] ?? null,
                'marital_status' => $row[2] ?? null,
                'age' => isset($row[3]) ? (int) $row[3] : null,
                'phone' => $row[4] ?? null,
                'activity_status' => $row[5] ?? null,
                'application_date' => $row[6] ?? null,
                'document_status' => isset($row[7]) ? (bool) $row[7] : true,
                'prays_on_time' => isset($row[8]) ? (bool) $row[8] : false,
                'height' => isset($row[9]) ? (float) $row[9] : null,
                'wears_niqab' => isset($row[10]) ? (bool) $row[10] : false,
                'weight' => isset($row[11]) ? (float) $row[11] : null,
                'skin_color' => $row[12] ?? null,
                'education_level' => $row[13] ?? null,
                'creed' => $row[14] ?? null,
                'scholars_you_follow' => $row[15] ?? null,
                'medical_illness' => $row[16] ?? null,
                'parents_opposition' => isset($row[17]) ? (bool) $row[17] : false,
                'willing_to_stay_home' => isset($row[18]) ? (bool) $row[18] : false,
                'accepts_divorced_or_widowed' => isset($row[19]) ? (bool) $row[19] : false,
                'accepts_living_with_inlaws' => isset($row[20]) ? (bool) $row[20] : false,
                'maximum_accepted_age' => isset($row[21]) ? (int) $row[21] : null,
                'partner_requirements' => $row[22] ?? null,
                'message_to_partner' => $row[23] ?? null,
                'remarks' => $row[24] ?? null,
            ];
    
            // Generate a unique hash for the row
            $rowHash = md5(json_encode($data));
    
            // Check if the hash already exists
            if (FApplicant::where('row_hash', $rowHash)->exists()) {
                $data['row_hash'] = $rowHash;
                FApplicant::create($data);
                $insertedCount++;
            }
        }
    
        return response()->json([
            'status' => 'success',
            'inserted' => $insertedCount,
        ]);
    }
}
