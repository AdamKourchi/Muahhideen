<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use App\Models\MApplicant;
use App\Models\FApplicant;



class PdfController extends Controller
{
    public function generateAndSendMale($id)
    {
        // Fetch data
        $data = MApplicant::findOrFail($id);

        // // Load the HTML view

        $html = view('pdf.applicantDocument', compact('data'))->render();
    
        // Initialize mPDF with Arabic support
        $mpdf = new Mpdf([
            'default_font' => 'dejavusans', // Supports Arabic characters
            'mode' => 'utf-8',
            'format' => 'A4',
            'autoScriptToLang' => true,
            'autoLangToFont' => true
        ]);
    
        // Write the HTML to PDF
        $mpdf->WriteHTML($html);
    
        // Save the PDF to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
        $mpdf->Output($tempFile, 'F');  // Save to the temporary file

        // Check if temp file was created successfully
        if ($tempFile === false) {
            throw new \Exception('Failed to create temporary file for PDF.');
        }
        $headers = [
            'Content-Type' => 'application/pdf',
         ];
        // Return the file for download
        // return response()->download($tempFile, 'document_' .  $data->full_name . '_' . $data->id . '.pdf',$headers)->deleteFileAfterSend(true);

        return response()->download($tempFile, 'document_' . $data->full_name . '_' . $data->id . '.pdf', $headers)->deleteFileAfterSend(true);


 }




 public function generateAndSendFemale($id)
 {
     // Fetch data
     $data =FApplicant::findOrFail($id);
 
     // Load the HTML view
     $html = view('pdf.applicantDocumentFemale', compact('data'))->render();
 
     // Initialize mPDF with Arabic support
     $mpdf = new Mpdf([
         'default_font' => 'dejavusans', // Supports Arabic characters
         'mode' => 'utf-8',
         'format' => 'A4',
         'autoScriptToLang' => true,
         'autoLangToFont' => true
     ]);
 
     // Write the HTML to PDF
     $mpdf->WriteHTML($html);
 
     // Save the PDF to a temporary file
     $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
     $mpdf->Output($tempFile, 'F');  // Save to the temporary file

     // Check if temp file was created successfully
     if ($tempFile === false) {
         throw new \Exception('Failed to create temporary file for PDF.');
     }
     $headers = [
         'Content-Type' => 'application/pdf',
      ];
     // Return the file for download
     // return response()->download($tempFile, 'document_' .  $data->full_name . '_' . $data->id . '.pdf',$headers)->deleteFileAfterSend(true);

     return response()->download($tempFile, 'document_' . $data->full_name . '_' . $data->id . '.pdf', $headers)->deleteFileAfterSend(true);


}
 
    

}
