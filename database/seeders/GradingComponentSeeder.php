<?php

namespace Database\Seeders;

use App\Models\GradingComponent;
use Illuminate\Database\Seeder;

class GradingComponentSeeder extends Seeder
{
    public function run(): void
    {
        // DepEd Order No. 8, s. 2015 weights for Senior High School

        // Core subjects
        GradingComponent::create([
            'subject_type'        => 'Core',
            'written_weight'      => 0.40,
            'performance_weight'  => 0.40,
            'exam_weight'         => 0.20,
        ]);

        // Applied subjects (Academic Track: STEM, ABM, HUMSS, GAS)
        GradingComponent::create([
            'subject_type'        => 'Applied',
            'written_weight'      => 0.35,
            'performance_weight'  => 0.45,
            'exam_weight'         => 0.20,
        ]);

        // Specialized subjects (Technical-Vocational-Livelihood & special tracks)
        GradingComponent::create([
            'subject_type'        => 'Specialized',
            'written_weight'      => 0.20,
            'performance_weight'  => 0.60,
            'exam_weight'         => 0.20,
        ]);
    }
}
