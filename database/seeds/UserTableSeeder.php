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
        /*factory(\TaskManager\Entities\User::class)->create([
            'name' => 'Alexandre',
            'email' => 'alexandre@zo2m.com.br',
            'password' => bcrypt(123456),
            'remember_token' => str_random(10),
        ]);*/
        factory(\TaskManager\Entities\User::class, 10)->create();
    }
}
