<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // \TaskManager\Entities\Client::truncate();
        factory(\TaskManager\Entities\Client::class, 10)->create();
    }
}
