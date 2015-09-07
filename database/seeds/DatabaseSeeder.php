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

       /*\TaskManager\Entities\Client::truncate();
       \TaskManager\Entities\Project::truncate();
        \TaskManager\Entities\User::truncate();*/

        //carrega factory de clientes para gerar dados fake.
        $this->call(UserTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(ProjectNoteTableSeeder::class);
        $this->call(ProjectTaskTableSeeder::class);
        $this->call(ProjectMembersTableSeeder::class);

        Model::reguard();
    }
}
