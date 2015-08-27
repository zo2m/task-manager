<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 16:23
 */

namespace TaskManager\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceProjectRepository;
use TaskManager\Validators\ProjectValidator;

class ProjectServices
{

    /**
     * @param ProjectValidator $project
     */
    protected $repository;

    /**
     * @param InterfaceProjectRepository $project
     */
    private $validator;



    public function __construct(InterfaceProjectRepository $repository, ProjectValidator $validator)
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
        return $this->repository->with(['user','notes', 'client'])->all();
    }


    /**
     * Cria cliente com validação
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {
        try
        {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        }
        catch (ValidatorException $e)
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

    public function update(array $data, $id)
    {
       try
       {

            try
            {
                $this->validator->with($data)->passesOrFail();
                return [
                    'error'=>0,
                    'message'=>'Dados atualizados com sucesso.',
                    'data'=> $this->repository->update($data, $id)
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
               'message' => 'O projeto que você quer atualizar não existe.'
           ];
       }
    }


    /**
     * Mostra apenas um cliente de acordo com seu ID
     * @param $id
     * @return mixed
     */

    public function show($id)
    {
        try
        {
            try
            {
                //return $this->repository->find($id);
                return [
                    'error' => false,
                    'data' => $this->repository->find($id)
                ];
            }
            catch (ValidatorException $e)
            {
                return [
                    'error' => true,
                    'message' => 'Projeto não existe.'
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'O projeto não existe.'
            ];
        }

    }


    /**
     * Exclui cliente de acordo com seu ID
     * @param $id
     * @return int
     */

    public function delete($id)
    {

        try
        {

            try
            {
                if($this->repository->delete($id))
                {
                    return [
                        'error'=>0,
                        'message'=>'Projeto excluído com sucesso.'
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
              'message' => 'Não foi possível excluir o projeto porque ele não existe.'
           ];
        }

    }


    public function verifyIfProjectExists($id)
    {
        if(count($this->repository->findWhere(['id' => $id]))<>0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}