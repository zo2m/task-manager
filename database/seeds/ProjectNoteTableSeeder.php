<?php

use Illuminate\Database\Seeder;

class ProjectNoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \TaskManager\Entities\ProjectNote::truncate();
        factory(\TaskManager\Entities\ProjectNote::class, 50)->create();
    }
}
