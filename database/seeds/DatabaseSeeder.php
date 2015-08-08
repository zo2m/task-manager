<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //carrega factory de clientes para gerar dados fake.
        $this->call(ClientTableSeeder::class);

        Model::reguard();
    }
}
