<?php

use App\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $parentCat1 = Category::create(['name' => $faker->name]);
        $parentCat2 = Category::create(['name' => $faker->name]);
        $parentCat3 = Category::create(['name' => $faker->name]);


        // child categories
        for ($i=0; $i < 5; $i++) {
            Category::create([
                'name' => $faker->jobTitle,
                'parent_id' => $parentCat1->id
            ]);
        }

        for ($i=0; $i < 5; $i++) {
            Category::create([
                'name' => $faker->name,
                'parent_id' => $parentCat2->id
            ]);
        }

        for ($i=0; $i < 5; $i++) {
            Category::create([
                'name' => $faker->name,
                'parent_id' => $parentCat3->id
            ]);
        }
    }
}
