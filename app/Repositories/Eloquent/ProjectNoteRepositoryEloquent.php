<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\ProjectNote;
use TaskManager\Repositories\InterfaceProjectNoteRepository;
use TaskManager\Presenters\ProjectNotePresenter;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace TaskManager\Repositories;
 */
class ProjectNoteRepositoryEloquent extends BaseRepository implements InterfaceProjectNoteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProjectNote::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }


    /**
     * Método obrigatório para informar ao repository qual o presenter utilizar para formatar a saída de dados
     * Json. Nome do método não pode ser modificado.
     *
     * @return mixed
     */

    public function presenter()
    {
        return ProjectNotePresenter::class;
    }
}