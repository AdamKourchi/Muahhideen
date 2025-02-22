<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FMarried extends Model
{
    /** @use HasFactory<\Database\Factories\FMarriedFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function mMarried()
    {
        return $this->belongsTo(MMarried::class);
    }

}
