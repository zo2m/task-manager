<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 14:43
 */

namespace TaskManager\Services;


use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceUserRepository;
use TaskManager\Validators\UserValidator;

class UserServices
{
    /**
     * Instancia métodos do repositório
     * @var InterfaceUserRepository
     */
    protected $repository;


    /**
     * Instancia métodos dos validadores
     * @var UserValidator
     */
    private $validator;


    /**
     * Classe construtora inicializar instancias de repositório e validadores
     * @param InterfaceUserRepository $repository
     * @param UserValidator $validator
     *
     */

    public function __construct(InterfaceUserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * Trás todos a listagem de todos os clientes
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

    public function update(array $data, $id)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        }catch (ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
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
        return $this->repository->find($id);
    }


    /**
     * Exclui cliente de acordo com seu ID
     * @param $id
     * @return int
     */

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}