<?php

namespace App\Services;


use Google\Client;
use Google\Service\Sheets;
use App\Models\MApplicant;
use App\Models\FApplicant;
use App\Models\GoogleData;



class GoogleSheetsSyncService
{
  protected Sheets $sheets;

  public function __construct()
  {
    //Get the file name from the database
    $googleData = GoogleData::all()->first();

    $file_name = $googleData->auth_config_file ?? null;

    if (!$googleData) {
      throw new \Exception('Google Data not found in the database.');
    }

    $client = new Client();
    $client->setAuthConfig(storage_path("app/private/" . $file_name));
    $client->addScope(Sheets::SPREADSHEETS_READONLY);

    $this->sheets = new Sheets($client);
  }


  public function syncMaleSheet()
  {
    return $this->syncSheetByName('Male', MApplicant::class);
  }

  public function syncFemaleSheet()
  {
    return $this->syncSheetByName('Female', FApplicant::class);
  }

  private function syncSheetByName(string $sheetName, string $applicantModel)
  {
    $googleSheet = GoogleData::where('sheet_name', $sheetName)->first();

    $spreadsheetId = $googleSheet->spreadsheetId ?? null;
    $range = $googleSheet->range ?? null;

    if (!$spreadsheetId || !$range) {
      throw new \Exception('Spreadsheet ID or range not found in the database.');
    }

    $response = $this->sheets->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (empty($values)) {
      return ['message' => 'No data found in the sheet.'];
    }

    array_shift($values); // Remove header row

    $added = 0;

    foreach ($values as $row) {
      // [name, email, phone, city]
      $exists = $applicantModel::where('email', $row[1] ?? '')->exists();

      if ($exists) {
        continue;
      }

      // Uncomment to actually create records:
      // $applicantModel::create([
      //     'name' => $row[0] ?? null,
      //     'email' => $row[1] ?? null,
      //     'phone' => $row[2] ?? null,
      //     'city' => $row[3] ?? null,
      // ]);

      $added++;
    }

    dd($values);

    return ['added' => $added];
  }
}
