<?php

namespace Database\Seeders;

use App\Models\course_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseTypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseTypes = ['Programming', 'Graphic Design', 'ICT'];

        foreach ($courseTypes as $type) {
            course_type::create([
                'name' => $type,
            ]);
        }
    }
}
