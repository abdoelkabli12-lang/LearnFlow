<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Artificial Intelligence',
            'Cybersecurity',
            'Cloud Computing',
            'DevOps',
            'UI/UX Design',
            'Graphic Design',
            'Digital Marketing',
            'Business & Entrepreneurship',
            'Project Management',
            'Finance & Accounting',
            'Personal Development',
            'Language Learning',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name],
            );
        }
    }
}
