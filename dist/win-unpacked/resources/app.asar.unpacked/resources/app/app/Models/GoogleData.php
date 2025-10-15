<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleData extends Model
{
  /** @use HasFactory<\Database\Factories\GoogleDataFactory> */
  use HasFactory;


  protected $fillable = [
    'male_api',
    'male_token',
    'female_api',
    'female_token',
  ];
}
