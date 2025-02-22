<?php

use App\Http\Controllers\ConnectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MApplicantController;
use App\Http\Controllers\FApplicantController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AgreementController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("m_applicants/{id}/pdf",  [PdfController::class, 'generateAndSendMale']);
Route::get("f_applicants/{id}/pdf",  [PdfController::class, 'generateAndSendFemale']);

Route::get("f_applicants/available/{id}",  [FApplicantController::class, 'getAvailableFApplicants']);

Route::apiResource("m_applicants", MApplicantController::class);

Route::apiResource("f_applicants", FApplicantController::class);

Route::apiResource("connections", ConnectionController::class);


Route::apiResource("agreements", AgreementController::class);

