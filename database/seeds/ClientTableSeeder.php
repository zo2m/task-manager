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
        \TaskManager\Client::truncate();
        factory(\TaskManager\Client::class, 10)->create();
    }
}
