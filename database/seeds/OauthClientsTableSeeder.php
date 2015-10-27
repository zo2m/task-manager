<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\TaskManager\Entities\User::truncate();
        /*factory(\TaskManager\Entities\OauthClients::class)->create([
            'name' => 'Alexandre',
            'email' => 'alexandre@zo2m.com.br',
            'password' => bcrypt(123456),
            'remember_token' => str_random(10),
        ]);*/
        factory(\TaskManager\Entities\OauthClients::class, 1)->create();
    }
}
