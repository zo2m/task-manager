<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 14:43
 */

namespace TaskManager\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceClientRepository;
use TaskManager\Validators\ClientValidator;

class ClientServices
{
    //variável para armazenar os erros e exibir depois no formato json
    var $error = array();

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
            $retorno = $this->verifyIfClientExists($data);

            try
            {
                if($retorno == false)
                {
                    $this->validator->with($data)->passesOrFail();
                    return $this->repository->create($data);
                }
                else
                {
                    return [
                        'error' => true,
                        'message' => 'Não foi possível criar o usuário porque o email já existe'
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
            return  [
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
                    $message = [
                        'error'=>false,
                        'message'=>'Cliente excluído com sucesso.'
                    ];

                    echo json_encode($message);

                };
            }
            catch (ValidatorException $e)
            {
                $error = [
                    'error' => $e->getCode(),
                    'message' => $e->getMessageBag()
                ];

                echo json_encode($error);
            }

        }
        catch(ModelNotFoundException $e)
        {

           $error = [
               'error' => false,
               'message' => 'Não foi possível excluir o cliente porque ele não existe.'
           ];

            echo json_encode($error);

        }

    }


    /**
     * Método para verificar se o usuário existe
     *
     * @param $data
     * @return bool
     */

    private function verifyIfClientExists($data)
    {

        if(count($this->repository->findWhere(['email' => $data['email']])))
        {
            return true;
        }
        return false;

    }
}