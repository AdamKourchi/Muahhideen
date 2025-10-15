<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MApplicant extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $fillable = [
    'application_date',
    'full_name',
    'phone',
    'city',
    'age',
    'marital_status',
    'job',
    'height',
    'weight',
    'skin_color',
    'accepts_divorced_or_widowed',
    'maximum_accepted_age',
    'housing_situation',
    'partner_requirements',
    'medical_illness',
    'message_to_partner',
    'accepts_working_wife',
    'education_level',
    'scholars_you_follow',
    'has_beard',
    'prays_with_jamaah',
    'creed',
    'preferred_cities_for_marriage',
    'shared_housing_details',
    'row_hash',
    'partner_requirements'
];


    public function connections()
    {
        return $this->belongsToMany(FApplicant::class, 'connections')
            ->withPivot('connection_date', 'status')
            ->withTimestamps();
    }
    
    public function disagreements()
    {
        return $this->belongsToMany(FApplicant::class, 'disagreements')
            ->withTimestamps();
    }
}
