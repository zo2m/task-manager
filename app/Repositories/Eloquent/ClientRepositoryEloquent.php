<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 10/08/15
 * Time: 20:56
 */

namespace TaskManager\Repositories\Eloquent;

use TaskManager\Entities\Client;
use Prettus\Repository\Eloquent\BaseRepository;
use TaskManager\Repositories\InterfaceClientRepository;


class ClientRepositoryEloquent extends BaseRepository implements InterfaceClientRepository
{
    public function model()
    {
        return Client::class;
    }
}