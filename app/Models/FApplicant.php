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

    public function connections()
    {
        return $this->belongsToMany(MApplicant::class, 'connections')
            ->withPivot('connection_date', 'status')
            ->withTimestamps();
    }
}
