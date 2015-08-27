<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 14:43
 */

namespace TaskManager\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceClientRepository;
use TaskManager\Validators\ClientValidator;

class ClientServices
{
    /**
     * Instancia métodos do repositório
     * @var InterfaceClientRepository
     */
    protected $repository;


    /**
     * Instancia métodos dos validadores
     * @var ClientValidator
     */
    private $validator;


    /**
     * Classe construtora inicializar instancias de repositório e validadores
     * @param InterfaceClientRepository $repository
     * @param ClientValidator $validator
     *
     */

    public function __construct(InterfaceClientRepository $repository, ClientValidator $validator)
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
                'message' => 'O cliente que você quer atualizar não existe.'
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
                return $this->repository->find($id);
            }
            catch (ValidatorException $e)
            {
                return [
                    'error' => $e->getCode(),
                    'message' => 'O cliente não existe.'
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
            return [
                'error' => $e->getMessage(),
                'message' => 'O Cliente não existe.'
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
            /*return [
                'error' => $e->getMessage(),
                'message' => 'Não foi possível excluir o cliente porque ele não existe.'
            ];*/
            echo 'Não foi possível excluir o cliente porque ele não existe.';
        }

    }
}