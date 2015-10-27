<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\OauthClients;
use TaskManager\Repositories\InterfaceOauthClientsRepository;

/**
 * Class OauthClientsRepositoryEloquent
 * @package namespace TaskManager\Repositories;
 */
class OauthClientsRepositoryEloquent extends BaseRepository implements InterfaceOauthClientsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OauthClients::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}