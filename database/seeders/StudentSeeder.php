<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(500)->create([
            'user_type' => User::$STUDENT_ROLE,
            'education_background' => User::$EDUCATION_HIGHSCHOOL,
        ]);
    }
}
