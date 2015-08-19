<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 16:23
 */

namespace TaskManager\Services;

use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceProjectNoteRepository;
use TaskManager\Validators\ProjectNoteValidator;


class ProjectNoteServices
{

    /**
     * @param ProjectNoteValidator $project
     */
    protected $repository;


    private $validator;



    public function __construct(InterfaceProjectNoteRepository $repository, ProjectNoteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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
     * Cria cliente com validação
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        }catch (ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }

    }


    /**
     * Atualiza dados do cliente com validação
     * @param array $data
     * @param $id
     * @return array|mixed
     */

    public function update(array $data, $noteId)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $noteId);
        }catch (ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }



    public function show($id, $noteId)
    {
        return $this->repository->findWhere(['project_id' => $id, 'id' => $noteId]);
    }

    public function showAllNotesFromProject($id)
    {
        return $this->repository->findWhere(['project_id'=>$id]);
    }


    /**
     * Exclui cliente de acordo com seu ID
     * @param $id
     * @return int
     */

    public function delete($noteId)
    {
        return $this->repository->delete($noteId);
    }
}