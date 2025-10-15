<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Google_Client;
use Google_Service_Sheets;


class FetchGoogleSheetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-google-sheet-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('formdata-453510-f37097c827c9.json'));
        
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);

        $service = new Google_Service_Sheets($client);
        $spreadsheetId = '1qkvmrynHDpX_dBbW1nsqacu6MpXDT4c5ykKFRpVSzUI'; // Replace with your actual Sheet ID
        $range = 'A2:G'; // Adjust based on your sheet structure

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            $this->info('No data found.');
            return;
        }

        // foreach ($values as $row) {
        //     FormResponse::updateOrCreate(
        //         [
        //             'timestamps' => $row[0],
        //             'name' => $row[1] ?? null,
        //             'city' => $row[2] ?? null,
        //             'age' => $row[3] ?? null,
        //         ]
        //     );
        // }

        foreach ($values as $row) {
            $data = [
                'timestamps' => $row[0],
                'name' => $row[1] ?? null,
                'city' => $row[2] ?? null,
                'age' => $row[3] ?? null,
            ];
        
            dump($data); // Laravel's debugging function
        }
        

        $this->info('Google Sheet data imported successfully.');
    
    }
}
