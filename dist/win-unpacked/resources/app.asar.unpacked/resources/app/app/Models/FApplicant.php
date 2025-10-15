<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FApplicant extends Model
{
    /** @use HasFactory<\Database\Factories\FApplicantFactory> */
    use HasFactory;
    use SoftDeletes;


 protected $fillable = [
    'application_date',
    'full_name',
    'phone',
    'city',
    'age',
    'marital_status',
    'activity_status',
    'willing_to_stay_home',
    'parents_opposition',
    'height',
    'weight',
    'skin_color',
    'accepts_divorced_or_widowed',
    'maximum_accepted_age',
    'accepts_living_with_inlaws',
    'medical_illness',
    'message_to_partner',
    'scholars_you_follow',
    'hijab_type',
    'prays_on_time',
    'creed',
    'nature_of_job',
    'accepts_polygamy',
    'row_hash',
    'education_level',
    "partner_requirements"
];


    public function connections()
    {
        return $this->belongsToMany(MApplicant::class, 'connections')
            ->withPivot('connection_date', 'status')
            ->withTimestamps();
    }
    
    public function disagreements()
    {
        return $this->belongsToMany(MApplicant::class, 'disagreements')
            ->withTimestamps();
    }
}
