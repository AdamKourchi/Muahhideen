<?php

namespace Database\Seeders;

use App\Models\FApplicant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
     public function run(): void
    {
        FApplicant::factory()->count(0)->create();

    }
}
