<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    /** @use HasFactory<\Database\Factories\ConnectionFactory> */
    use HasFactory;
    


    protected $fillable = [
        'm_applicant_id',
        'f_applicant_id',
        'connection_date',
        'status',
        'remarks'
    ];

    

    public function mApplicant()
    {
        return $this->belongsTo(MApplicant::class);
    }

    public function fApplicant()
    {
        return $this->belongsTo(FApplicant::class);
    }

}
