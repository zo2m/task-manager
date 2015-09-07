<?php

use Illuminate\Database\Seeder;

class ProjectMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\TaskManager\Entities\ProjectTask::truncate();
        factory(\TaskManager\Entities\ProjectMembers::class, 10)->create();
    }
}
