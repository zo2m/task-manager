<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\TaskManager\Entities\Project::truncate();
        factory(\TaskManager\Entities\Project::class, 10)->create();
    }
}
