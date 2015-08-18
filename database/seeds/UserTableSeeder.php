<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\TaskManager\Entities\User::truncate();
        factory(\TaskManager\Entities\User::class, 10)->create();
    }
}
