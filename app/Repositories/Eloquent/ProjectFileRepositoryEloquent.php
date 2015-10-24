<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\ProjectFile;
use TaskManager\Repositories\InterfaceProjectFileRepository;
use TaskManager\Presenters\ProjectNotePresenter;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace TaskManager\Repositories;
 */
class ProjectFileRepositoryEloquent extends BaseRepository implements InterfaceProjectFileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectFile::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }



}