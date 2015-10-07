<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 16:23
 */

namespace TaskManager\Services;

use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceProjectNoteRepository;
use TaskManager\Validators\ProjectNoteValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectNoteServices
{

    /**
     * @param ProjectNoteValidator $project
     */
    protected $repository;

    private $validator;

    private $projects;


    /**
     * @param InterfaceProjectNoteRepository $repository
     * @param ProjectNoteValidator $validator
     * @param ProjectServices $projects
     */

    public function __construct(InterfaceProjectNoteRepository $repository, ProjectNoteValidator $validator, ProjectServices $projects)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->projects = $projects;
    }


    /**
     * Trás a listagem de todos os projetos com seus clientes, notas e usuários
     * @return mixed
     */

    public function showAll()
    {
        return $this->repository->with(['project'])->all();
    }


    /**
     * Cria nota para o projeto.
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {

        try
        {
            try
            {
                //se existir um projeto, cria nota, caso contrário gera erro
                if($this->projects->verifyIfProjectExists($data['project_id']) == true)
                {
                    $this->validator->with($data)->passesOrFail();
                    return $this->repository->create($data);
                }
                else
                {
                    return [
                        'error' => true,
                        'message' => 'Não foi possível criar a nota porque o projeto não existe.'
                    ];
                }

            }
            catch (ValidatorException $e)
            {
                return [
                    'error' => true,
                    'message' => $e->getMessageBag()
                ];
            }
        }
        catch(QueryException $e)
        {
            return[
                'error' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

    }


    /**
     * atualiza a nota de acordo com seu id
     * @param array $data
     * @param $noteId
     * @return array
     */

    public function update(array $data, $noteId)
    {

        try
        {
            try
            {

                $this->validator->with($data)->passesOrFail();
                return [
                    'error'=>false,
                    'message'=>'Dados atualizados com sucesso.',
                    'data'=> $this->repository->update($data,$noteId)
                ];

            }
            catch (ValidatorException $e)
            {
                return [
                    'error' => true,
                    'message' => $e->getMessageBag()
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
            return [
                'error' => $e->getMessage(),
                'message' => 'A nota que você quer atualizar não existe.'
            ];
        }

    }


    /**
     * Mostra a nota de acordo com o id do projeto e da nota
     * @param $id
     * @param $noteId
     * @return array|mixed
     */

    public function show($id, $noteId)
    {


        if(count($this->repository->findWhere(['project_id' => $id, 'id' => $noteId]))<>0)
        {
            return $this->repository->findWhere(['project_id' => $id, 'id' => $noteId]);
        }
        else
        {
            return [
                'error' => true,
                'message' => 'A nota que você procura não existe.'
            ];
        }


    }


    /**
     * Mostra todas as notas de um projeto de acordo com seu id
     * @param $id
     * @return array|mixed
     */

    public function showAllNotesFromProject($id)
    {
        if(count($this->repository->findWhere(['project_id'=>$id])))
        {
            return $this->repository->findWhere(['project_id'=>$id]);
        }
        else
        {
            return [
                'error' => true,
                'message' => 'Não existem notas para esse projeto.'
            ];
        }

    }


    /**
     * Exclui cliente de acordo com seu ID
     * @param $id
     * @return int
     */

    public function delete($noteId)
    {

        try
        {

            try
            {
                if($this->repository->delete($noteId))
                {
                    return [
                        'error'=>0,
                        'message'=>'Nota excluída com sucesso.'
                    ];

                };
            }
            catch (ValidatorException $e)
            {
                return [
                    'error' => $e->getCode(),
                    'message' => $e->getMessageBag()
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
            return [
                'error' => $e->getMessage(),
                'message' => 'Não foi possível excluir a nota porque ela não existe.'
            ];
        }

    }

}