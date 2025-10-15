<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\GoogleDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MApplicantController;
use App\Http\Controllers\FApplicantController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\DisagreementController;
use App\Http\Controllers\AuthController;



Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);



Route::middleware('auth:sanctum')->group(function () {

    Route::get("m_applicants/{id}/pdf",  [PdfController::class, 'generateAndSendMale']);
    Route::get("f_applicants/{id}/pdf",  [PdfController::class, 'generateAndSendFemale']);

    Route::get("f_applicants/available/{id}",  [FApplicantController::class, 'getAvailableFApplicants']);

    Route::apiResource("m_applicants", MApplicantController::class);

    Route::apiResource("f_applicants", FApplicantController::class);

    Route::apiResource("connections", ConnectionController::class);

    Route::apiResource("agreements", AgreementController::class);

    Route::apiResource("disagreements", DisagreementController::class);

    Route::apiResource("google-data", GoogleDataController::class);
   
    Route::get("sync-male-data", [GoogleDataController::class, 'syncMaleData']);
    Route::get('sync-female-data', [GoogleDataController::class, 'syncFemaleData']);

    Route::post('/upload-csv', [GoogleDataController::class, 'uploadXlsx']);
});
