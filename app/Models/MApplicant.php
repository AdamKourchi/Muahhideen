<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MApplicant extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function connections()
    {
        return $this->belongsToMany(FApplicant::class, 'connections')
            ->withPivot('connection_date', 'status')
            ->withTimestamps();
    }
}
