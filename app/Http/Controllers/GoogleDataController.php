<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoogleDataRequest;
use App\Http\Requests\UpdateGoogleDataRequest;
use App\Models\FApplicant;
use App\Models\GoogleData;
use Illuminate\Http\Request;
use App\Services\GoogleSheetsSyncService;
use Illuminate\Support\Facades\Http;
use App\Models\Mapplicant;
use Maatwebsite\Excel\Facades\Excel;


class GoogleDataController extends Controller
{

    public function index()
    {
        $googleData = GoogleData::first();
        return response()->json($googleData);
    }



    public function store(Request $request)
    {
        $googleData = GoogleData::first();

        if (!$googleData) {
            $googleData = new GoogleData();
        }


        $googleData->male_api = $request->maleApiUrl;
        $googleData->male_token = $request->maleToken;
        $googleData->female_api = $request->femaleApiUrl;
        $googleData->female_token = $request->femaleToken;
        $googleData->save();


        return response()->json(1);
    }


    public function uploadCsv(Request $request)
    {
        // Check if any file with .csv extension is uploaded
        if (!$request->hasFile(null)) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Find the uploaded file with .csv extension
        $file = collect($request->files->all())
            ->first(function ($uploadedFile) {
                return $uploadedFile->getClientOriginalExtension() === 'csv';
            });

        if (!$file) {
            return response()->json(['error' => 'CSV file not found'], 400);
        }

        $type = strtolower($request->type);

        if ($type === 'female') {

            $model = FApplicant::class;
            $added = 0;

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {

                $header = fgetcsv($handle); // Skip header row

                while (($row = fgetcsv($handle)) !== false) {

                    /*
                0 Timestamp
                1 الاسم والنسب  
                2 رقمك  
                3 المدينة  
                4 العمر  
                5 الحالة الاجتماعية (عازبة ، مطلقة ، أرملة )  
                6 هل انت تدرسين أم قارة في البيت أم تعملين  
                7 هل انت على استعداد للقرار في البيت  
                8 هل والداك يعارضان تزويجك  
                9 الطول  
                10 الوزن  
                11 لون البشرة  
                12 هل تقبلين مطلقا أو أرملا  
                13 أقصى العمر الذي تقبلين به  
                14 هل تقبلين بالسكن مع والدي الزوج  
                15 ماشروطك في الطرف الاخر  
                16 هل تعاني من مرض معين  
                17 هل هناك شيء ما تود اخباره للطرف الاخر  
                18 مستواك الدراسي  
                19 شيوخك ممن تأخد الفتوى  
                20 نوع حجابك؟  
                21 هل تصلين الفريضة في وقتها  
                22 عقيدتك  
                23 إن كنت تعملين، فما طبيعة العمل؟  
                24 هل تقبلين معددا؟  

                    */

                    $full_name = $row[1] ?? null;
                    $phone = $row[2] ?? null;

                    if (!$full_name || !$phone)
                        continue;

                    $exists = $model::withTrashed()
                    ->where('full_name', $full_name)
                        ->where('phone', $phone)
                        ->exists();
                    if ($exists)
                        continue;

                    $model::create([
                        "application_date" => $row[0] ?? null,
                        'full_name' => $full_name,
                        'phone' => $phone,
                        'city' => $row[3] ?? null,
                        'age' => $row[4] ?? null,
                        'marital_status' => match ($row[5] ?? null) {
                            'عازبة' => 'عازبة',
                            'أرملة' => 'أرملة',
                            'مطلقة' => 'مطلقة',
                            default => null,
                        },
                        'activity_status' => match ($row[6] ?? null) {
                            'تدرس' => 'تدرس',
                            'قارة' => 'قارة',
                            'تعمل' => 'تعمل',
                            default => null,
                        },
                        'willing_to_stay_home' => isset($row[7]) ? ($row[7] === 'نعم' ? true : false) : false,
                        'parents_opposition' => isset($row[8]) ? ($row[8] === 'نعم' ? true : false) : false,
                        'height' => $row[9] ?? null,
                        'weight' => $row[10] ?? null,
                        'skin_color' => $row[11] ?? null,
                        'accepts_divorced_or_widowed' => isset($row[12]) ? ($row[12] === 'نعم' ? true : false) : false,
                        'maximum_accepted_age' => $row[13] ?? null,
                        'accepts_living_with_inlaws' => isset($row[14]) ? ($row[14] === 'نعم' ? true : false) : false,
                        'partner_requirements' => $row[15] ?? null,
                        'medical_illness' => $row[16] ?? null,
                        'message_to_partner' => $row[17] ?? null,
                        'education_level' => $row[18] ?? null,
                        'scholars_you_follow' => $row[19] ?? null,
                        'hijab_type' => $row[20] ?? null,
                        'prays_on_time' => $row[21] === 'نعم' ? true : false,
                        'creed' => $row[22] ?? null,
                        'nature_of_job' => $row[23] ?? null,
                        'accepts_polygamy' => isset($row[24]) ? ($row[24] === 'نعم' ? true : false) : false,
                        // Other columns will use defaults or remain null
                        'row_hash' => md5($full_name . $phone),
                    ]);
                    $added++;
                }
                fclose($handle);
            }

            return response()->json([
                'message' => 'CSV processed',
                'rows_added' => $added
            ]);
        } elseif ($type == 'male') {

            $model = MApplicant::class;
            $added = 0;

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {

                $header = fgetcsv($handle); // Skip header row

                while (($row = fgetcsv($handle)) !== false) {
                    // 0 : Timestamp
                    // 1: الاسم والنسب (full_name)
                    // 2: رقمك (phone)
                    // 3: المدينة (city)
                    // 4: العمر (age)
                    // 5: الحالة الإجتماعية (marital_status)
                    // 6: العمل
                    // 7: الطول
                    // 8: الوزن
                    // 9:  لون البشرة
                    // 10:   هل تقبل مطلقة او ارملة
                    // 11 :  أقصى العمر الذي تقبل به
                    // 12 : هل ستوفر سكنا مستقلا أم مع الوالدين :
                    // 13 : ماشروطك في الطرف الاخر :
                    // 14 : هل تعاني من مرض معين:
                    // 15 : هل هناك شيء ما تود اخباره للطرف الاخر :
                    // 16 : هل تقبل بها عاملة أو جامعية :
                    // 17 : مستواك الدراسي
                    // 18 : شيوخك ممن تأخد الفتوى
                    // 19: ملتحي ام لا
                    // 20 : هل تصلي الفريضة مع الجماعة
                    // 21: عقيدتك
                    // 22 : المدن التي تفضل الزواج منها
                    //23 : إذا كان السكن مع الوالدين فحبذا لو تعطي بعض التفاصيل عن طبيعة السكن و عدد الأفراد

                    $full_name = $row[1] ?? null;
                    $phone = $row[2] ?? null;

                    if (!$full_name || !$phone)
                        continue;

                    $exists = $model::withTrashed()
                    ->where('full_name', $full_name)
                        ->where('phone', $phone)
                        ->exists();

                        
                    if ($exists)
                        continue;

                    $model::create([
                        "application_date" => $row[0] ?? null,
                        'full_name' => $full_name,
                        'phone' => $phone,
                        'city' => $row[3] ?? null,
                        'age' => $row[4] ?? null,
                        'marital_status' => $row[5] ?? null,
                        'job' => $row[6] ?? null,

                        'height' => $row[7] ?? null,
                        'weight' => $row[8] ?? null,
                        "skin_color" => $row[9] ?? null,

                        "accepts_divorced_or_widowed" => isset($row[10]) ? ($row[10] === 'نعم' ? true : false) : false,

                        'maximum_accepted_age' => $row[11] ?? null,

                        "housing_situation" => isset($row[12]) ? ($row[12] === 'مع الوالدين' ? "with_parents" : "independent") : null,

                        "partner_requirements" => $row[13] ?? null,

                        'medical_illness' => $row[14] ?? null,

                        'message_to_partner' => $row[15] ?? null,

                        "accepts_working_wife" => isset($row[16]) ? ($row[16] === 'نعم' ? true : false) : false,

                        "education_level" => $row[17] ?? null,

                        'scholars_you_follow' => $row[18] ?? null,

                        "has_beard" => isset($row[19]) ? ($row[19] === 'نعم' ? true : false) : false,

                        "prays_with_jamaah" => isset($row[20]) ? ($row[20] === 'نعم' ? true : false) : false,

                        'creed' => $row[21] ?? null,

                        "preferred_cities_for_marriage" => $row[22] ?? null,
                        "shared_housing_details" => $row[23] ?? null,

                        'row_hash' => md5($full_name . $phone),
                    ]);

                    $added++;
                }
                fclose($handle);
            }

            return response()->json([
                'message' => 'CSV processed',
                'rows_added' => $added
            ]);


        }

        // ...existing code for male or other types...
    }
    private function excelDateToDateTime($excelFloat)
    {
        if (!is_numeric($excelFloat)) {
            return $excelFloat; // fallback if someone typed a string instead
        }

        $unixOrigin = \Carbon\Carbon::createFromFormat('Y-m-d', '1899-12-30');
        $dateTime = $unixOrigin->copy()->addDays(floor($excelFloat));
        $secondsInDay = 24 * 60 * 60;
        $timeSeconds = ($excelFloat - floor($excelFloat)) * $secondsInDay;
        $dateTime->addSeconds(round($timeSeconds));
        return $dateTime->toDateTimeString();
    }

    public function uploadXlsx(Request $request)
    {
        // Check if any file with .xlsx extension is uploaded
        if (!$request->hasFile(null)) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Find the uploaded file with .xlsx extension
        $file = collect($request->files->all())
            ->first(function ($uploadedFile) {
                return in_array($uploadedFile->getClientOriginalExtension(), ['xlsx', 'xls']);
            });

        if (!$file) {
            return response()->json(['error' => 'Excel file not found'], 400);
        }

        $type = strtolower($request->type);

        if ($type === 'female') {
            $model = FApplicant::class;
            $added = 0;

            try {
                // Load the Excel file
                $data = Excel::toArray([], $file)[0]; // Get first sheet data

                // Skip header row (first row)
                $rows = array_slice($data, 1);

                foreach ($rows as $row) {
                    /*
                    0 Timestamp
                    1 الاسم والنسب  
                    2 رقمك  
                    3 المدينة  
                    4 العمر  
                    5 الحالة الاجتماعية (عازبة ، مطلقة ، أرملة )  
                    6 هل انت تدرسين أم قارة في البيت أم تعملين  
                    7 هل انت على استعداد للقرار في البيت  
                    8 هل والداك يعارضان تزويجك  
                    9 الطول  
                    10 الوزن  
                    11 لون البشرة  
                    12 هل تقبلين مطلقا أو أرملا  
                    13 أقصى العمر الذي تقبلين به  
                    14 هل تقبلين بالسكن مع والدي الزوج  
                    15 ماشروطك في الطرف الاخر  
                    16 هل تعاني من مرض معين  
                    17 هل هناك شيء ما تود اخباره للطرف الاخر  
                    18 مستواك الدراسي  
                    19 شيوخك ممن تأخد الفتوى  
                    20 نوع حجابك؟  
                    21 هل تصلين الفريضة في وقتها  
                    22 عقيدتك  
                    23 إن كنت تعملين، فما طبيعة العمل؟  
                    24 هل تقبلين معددا؟  
                    */

                    $full_name = $row[1] ?? null;
                    $phone = $row[2] ?? null;

                    if (!$full_name || !$phone)
                        continue;

         
        
                        $exists = $model::withTrashed()
                    ->where('full_name', $full_name)
                        ->where('phone', $phone)
                        ->exists();



                    if ($exists)
                        continue;

                    $model::create([
                        "application_date" => $this->excelDateToDateTime($row[0]),
                        'full_name' => $full_name,
                        'phone' => $phone,
                        'city' => $row[3] ?? null,
                        'age' => $row[4] ?? null,
                        'marital_status' => match ($row[5] ?? null) {
                            'عازبة' => 'عازبة',
                            'أرملة' => 'أرملة',
                            'مطلقة' => 'مطلقة',
                            default => null,
                        },
                        'activity_status' => match ($row[6] ?? null) {
                            'تدرس' => 'تدرس',
                            'قارة' => 'قارة',
                            'تعمل' => 'تعمل',
                            default => null,
                        },
                        'willing_to_stay_home' => isset($row[7]) ? ($row[7] === 'نعم' ? true : false) : false,
                        'parents_opposition' => isset($row[8]) ? ($row[8] === 'نعم' ? true : false) : false,
                        'height' => $row[9] ?? null,
                        'weight' => $row[10] ?? null,
                        'skin_color' => $row[11] ?? null,
                        'accepts_divorced_or_widowed' => isset($row[12]) ? ($row[12] === 'نعم' ? true : false) : false,
                        'maximum_accepted_age' => $row[13] ?? null,
                        'accepts_living_with_inlaws' => isset($row[14]) ? ($row[14] === 'نعم' ? true : false) : false,
                        'partner_requirements' => $row[15] ?? null,
                        'medical_illness' => $row[16] ?? null,
                        'message_to_partner' => $row[17] ?? null,
                        'education_level' => $row[18] ?? null,
                        'scholars_you_follow' => $row[19] ?? null,
                        'hijab_type' => $row[20] ?? null,
                        'prays_on_time' => $row[21] === 'نعم' ? true : false,
                        'creed' => $row[22] ?? null,
                        'nature_of_job' => $row[23] ?? null,
                        'accepts_polygamy' => isset($row[24]) ? ($row[24] === 'نعم' ? true : false) : false,
                        'row_hash' => md5($full_name . $phone),
                    ]);
                    $added++;
                }

                return response()->json([
                    'message' => 'Excel file processed successfully',
                    'rows_added' => $added
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to process Excel file',
                    'details' => $e->getMessage()
                ], 500);
            }

        } elseif ($type == 'male') {
            $model = MApplicant::class;
            $added = 0;

            try {
                // Load the Excel file
                $data = Excel::toArray([], $file)[0]; // Get first sheet data

                // Skip header row (first row)
                $rows = array_slice($data, 1);

                foreach ($rows as $row) {
                    // 0 : Timestamp
                    // 1: الاسم والنسب (full_name)
                    // 2: رقمك (phone)
                    // 3: المدينة (city)
                    // 4: العمر (age)
                    // 5: الحالة الإجتماعية (marital_status)
                    // 6: العمل
                    // 7: الطول
                    // 8: الوزن
                    // 9:  لون البشرة
                    // 10:   هل تقبل مطلقة او ارملة
                    // 11 :  أقصى العمر الذي تقبل به
                    // 12 : هل ستوفر سكنا مستقلا أم مع الوالدين :
                    // 13 : ماشروطك في الطرف الاخر :
                    // 14 : هل تعاني من مرض معين:
                    // 15 : هل هناك شيء ما تود اخباره للطرف الاخر :
                    // 16 : هل تقبل بها عاملة أو جامعية :
                    // 17 : مستواك الدراسي
                    // 18 : شيوخك ممن تأخد الفتوى
                    // 19: ملتحي ام لا
                    // 20 : هل تصلي الفريضة مع الجماعة
                    // 21: عقيدتك
                    // 22 : المدن التي تفضل الزواج منها
                    // 23 : إذا كان السكن مع الوالدين فحبذا لو تعطي بعض التفاصيل عن طبيعة السكن و عدد الأفراد

                    $full_name = $row[1] ?? null;
                    $phone = $row[2] ?? null;

                    if (!$full_name || !$phone)
                        continue;

                         $exists = $model::withTrashed()
                    ->where('full_name', $full_name)
                        ->where('phone', $phone)
                        ->exists();

                    if ($exists)
                        continue;

                    $model::create([
                        "application_date" => $row[0] ?? null,
                        'full_name' => $full_name,
                        'phone' => $phone,
                        'city' => $row[3] ?? null,
                        'age' => $row[4] ?? null,
                        'marital_status' => match ($row[5] ?? null) {
                            'عازب' => 'عازب',
                            'أرمل' => 'أرمل',
                            'مطلق' => 'مطلق',
                            'متزوج' => 'متزوج',
                            default => null,
                        },
                        'job' => $row[6] ?? null,
                        'height' => $row[7] ?? null,
                        'weight' => $row[8] ?? null,
                        "skin_color" => $row[9] ?? null,
                        "accepts_divorced_or_widowed" => isset($row[10]) ? ($row[10] === 'نعم' ? true : false) : false,
                        'maximum_accepted_age' => $row[11] ?? null,
                        "housing_situation" => isset($row[12]) ? ($row[12] === 'مع الوالدين' ? "with_parents" : "independent") : null,
                        "partner_requirements" => $row[13] ?? null,
                        'medical_illness' => $row[14] ?? null,
                        'message_to_partner' => $row[15] ?? null,
                        "accepts_working_wife" => isset($row[16]) ? ($row[16] === 'نعم' ? true : false) : false,
                        "education_level" => $row[17] ?? null,
                        'scholars_you_follow' => $row[18] ?? null,
                        "has_beard" => isset($row[19]) ? ($row[19] === 'نعم' ? true : false) : false,
                        "prays_with_jamaah" => isset($row[20]) ? ($row[20] === 'نعم' ? true : false) : false,
                        'creed' => $row[21] ?? null,
                        "preferred_cities_for_marriage" => $row[22] ?? null,
                        "shared_housing_details" => $row[23] ?? null,
                        'row_hash' => md5($full_name . $phone),
                    ]);

                    $added++;
                }

                return response()->json([
                    'message' => 'Excel file processed successfully',
                    'rows_added' => $added
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to process Excel file',
                    'details' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json(['error' => 'Invalid type specified'], 400);
    }

    public function syncMaleData()
    {
        $googleData = GoogleData::first();
        if (!$googleData) {
            return response()->json(['error' => 'Google Data not found'], 404);
        }

        $maleApi = $googleData->male_api;
        $maleToken = $googleData->male_token;

        if (!$maleApi || !$maleToken) {
            return response()->json(['error' => 'Data not enough to sync'], 400);
        }

        $response = Http::get($maleApi . '?token=' . $maleToken);

        if (!$response->ok()) {
            return response()->json(['error' => 'Failed to fetch data from API'], 500);
        }

        $responseData = $response->json();


        // Check if the response has the expected structure
        if (!isset($responseData['success']) || !$responseData['success']) {
            return response()->json(['error' => 'API returned error: ' . ($responseData['message'] ?? 'Unknown error')], 500);
        }

        // Extract the actual data array
        $data = $responseData['data'] ?? [];

        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid data format received'], 500);
        }

        $added = 0;


        foreach ($data as $row) {
            // Rest of your code remains the same...
            $full_name = $row[1] ?? null;
            $phone = $row[2] ?? null;

            if (!$full_name || !$phone) {
                continue;
            }
            $exists = Mapplicant::withTrashed()
                ->where('full_name', $full_name)
                ->where('phone', $phone)
                ->exists();

            if ($exists) {
                continue;
            }

            /*
                0. Timestamp
                1. الاسم والنسب
                2. رقمك
                3. المدينة
                4. العمر
                5. الحالة الاجتماعية (عازب ، مطلق ، متزوج )
                6. العمل
                7. الطول
                8. الوزن
                9. لون البشرة
                10. هل تقبل مطلقة او ارملة
                11. أقصى العمر الذي تقبل به
                12. هل ستوفر سكنا مستقلا أم مع الوالدين
                13. ماشروطك في الطرف الاخر
                14. هل تعاني من مرض معين
                15. هل هناك شيء ما تود اخباره للطرف الاخر
                16. هل تقبل بها عاملة أو جامعية
                17. مستواك الدراسي
                18. شيوخك ممن تأخد الفتوى
                19. ملتحي ام لا
                20. هل تصلي الفريضة مع الجماعة
                21. عقيدتك
                22. المدن التي تفضل الزواج منها
                23. إذا كان السكن مع الوالدين فحبذا لو تعطي بعض التفاصيل عن طبيعة السكن و عدد الأفراد
            */

            Mapplicant::create([
                'application_date' => $row[0] ?? null,
                'full_name' => $full_name,
                'phone' => $phone,
                'city' => $row[3] ?? null,
                'age' => $row[4] ?? null,
                'marital_status' => match ($row[5] ?? null) {
                    'عازب' => 'عازب',
                    'أرمل' => 'أرمل',
                    'مطلق' => 'مطلق',
                    'متزوج' => 'متزوج',
                    default => null,
                },
                'job' => $row[6] ?? null,
                'height' => $row[7] ?? null,
                'weight' => $row[8] ?? null,
                "skin_color" => $row[9] ?? null,
                "accepts_divorced_or_widowed" => isset($row[10]) ? ($row[10] === 'نعم' ? true : false) : false,
                'maximum_accepted_age' => $row[11] ?? null,
                "housing_situation" => isset($row[12]) ? ($row[12] === 'مع الوالدين' ? "with_parents" : "independent") : null,
                "partner_requirements" => $row[13] ?? null,
                'medical_illness' => $row[14] ?? null,
                'message_to_partner' => $row[15] ?? null,
                "accepts_working_wife" => isset($row[16]) ? ($row[16] === 'نعم' ? true : false) : false,
                "education_level" => $row[17] ?? null,
                'scholars_you_follow' => $row[18] ?? null,
                "has_beard" => isset($row[19]) ? ($row[19] === 'نعم' ? true : false) : false,
                "prays_with_jamaah" => isset($row[20]) ? ($row[20] === 'نعم' ? true : false) : false,
                'creed' => $row[21] ?? null,
                "preferred_cities_for_marriage" => $row[22] ?? null,
                "shared_housing_details" => $row[23] ?? null,
                'row_hash' => md5($full_name . $phone),
            ]);

            $added++;
        }

        return response()->json([
            'message' => 'Sync complete',
            'rows_added' => $added
        ]);
    }



    public function syncFemaleData()
    {
        $googleData = GoogleData::first();
        if (!$googleData) {
            return response()->json(['error' => 'Google Data not found'], 404);
        }

        $femaleApi = $googleData->female_api;
        $femaleToken = $googleData->female_token;

        if (!$femaleApi || !$femaleToken) {
            return response()->json(['error' => 'Data not enough to sync'], 400);
        }

        $response = Http::get($femaleApi . '?token=' . $femaleToken);

        if (!$response->ok()) {
            return response()->json(['error' => 'Failed to fetch data from API'], 500);
        }

        $responseData = $response->json();


        // Check if the response has the expected structure
        if (!isset($responseData['success']) || !$responseData['success']) {
            return response()->json(['error' => 'API returned error: ' . ($responseData['message'] ?? 'Unknown error')], 500);
        }

        // Extract the actual data array
        $data = $responseData['data'] ?? [];

        if (!is_array($data)) {
            return response()->json(['error' => 'Invalid data format received'], 500);
        }

        $added = 0;


        foreach ($data as $row) {
            // Rest of your code remains the same...
            $full_name = $row[1] ?? null;
            $phone = $row[2] ?? null;

            if (!$full_name || !$phone) {
                continue;
            }


            $exists = Fapplicant::withTrashed()
            ->where('full_name', $full_name)
                ->where('phone', $phone)
                ->exists();



            if ($exists) {
                continue;
            }



            FApplicant::create([
                'application_date' => $this->excelDateToDateTime($row[0]),
                'full_name' => $full_name,
                'phone' => $phone,
                'city' => $row[3] ?? null,
                'age' => $row[4] ?? null,
                'marital_status' => match ($row[5] ?? null) {
                    'عازبة' => 'عازبة',
                    'أرملة' => 'أرملة',
                    'مطلقة' => 'مطلقة',
                    default => null,
                },
                'activity_status' => match ($row[6] ?? null) {
                    'تدرس' => 'تدرس',
                    'قارة' => 'قارة',
                    'تعمل' => 'تعمل',
                    default => null,
                },
                'willing_to_stay_home' => isset($row[7]) ? ($row[7] === 'نعم' ? true : false) : false,
                'parents_opposition' => isset($row[8]) ? ($row[8] === 'نعم' ? true : false) : false,
                'height' => $row[9] ?? null,
                'weight' => $row[10] ?? null,
                'skin_color' => $row[11] ?? null,
                'accepts_divorced_or_widowed' => isset($row[12]) ? ($row[12] === 'نعم' ? true : false) : false,
                'maximum_accepted_age' => $row[13] ?? null,
                'accepts_living_with_inlaws' => isset($row[14]) ? ($row[14] === 'نعم' ? true : false) : false,
                'partner_requirements' => $row[15] ?? null,
                'medical_illness' => $row[16] ?? null,
                'message_to_partner' => $row[17] ?? null,
                'education_level' => $row[18] ?? null,
                'scholars_you_follow' => $row[19] ?? null,
                'hijab_type' => $row[20] ?? null,
                'prays_on_time' => $row[21] === 'نعم' ? true : false,
                'creed' => $row[22] ?? null,
                'nature_of_job' => $row[23] ?? null,
                'accepts_polygamy' => isset($row[24]) ? ($row[24] === 'نعم' ? true : false) : false,
                'row_hash' => md5($full_name . $phone),
            ]);

            $added++;
        }

        return response()->json([
            'message' => 'Sync complete',
            'rows_added' => $added
        ]);
    }
}
