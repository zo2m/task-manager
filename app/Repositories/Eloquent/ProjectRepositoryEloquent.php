<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\Project;
use TaskManager\Repositories\InterfaceProjectRepository;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace TaskManager\Repositories;
 */
class ProjectRepositoryEloquent extends BaseRepository implements InterfaceProjectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
       return Project::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}