<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\ProjectMembers;
use TaskManager\Repositories\InterfaceProjectMembersRepository;

/**
 * Class ProjectMembersRepositoryEloquent
 * @package namespace TaskManager\Repositories;
 */
class ProjectMembersRepositoryEloquent extends BaseRepository implements InterfaceProjectMembersRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectMembers::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }
}