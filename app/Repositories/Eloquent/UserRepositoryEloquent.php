<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 10/08/15
 * Time: 20:56
 */

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use TaskManager\Entities\User;
use TaskManager\Repositories\InterfaceUserRepository;


class UserRepositoryEloquent extends BaseRepository implements InterfaceUserRepository
{
    public function model()
    {
        return User::class;
    }
}