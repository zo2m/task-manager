<?php

namespace TaskManager\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use TaskManager\Entities\Project;
use TaskManager\Repositories\InterfaceProjectRepository;
use TaskManager\Presenters\ProjectPresenter;

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


    /**
     * Método para verificar se um usuário é ou não proprietário de determinado projeto
     *
     * @param $project_id
     * @param $user_id
     * @return bool
     */

    public function isOwner($project_id, $user_id)
    {
        $data = $this->findWhere(['id' => $project_id, 'user_id' => $user_id]);

        if(!empty($data['data']))
        {
            return true;
        }

        return false;
    }

    /**
     * Método obrigatório para informar ao repository qual o presenter utilizar para formatar a saída de dados
     * Json. Nome do método não pode ser modificado.
     *
     * @return mixed
     */

    public function presenter()
    {
        return ProjectPresenter::class;
    }
}