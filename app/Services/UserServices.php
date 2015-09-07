<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 14:43
 */

namespace TaskManager\Services;


use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceUserRepository;
use TaskManager\Validators\UserValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Trás todos a listagem de todos os clientes com seus projetos
     * @return mixed
     */

    public function showAllUserWithProject()
    {
        return $this->repository->with(['project'])->all();
    }


    /**
     * Retorna apenas os usuários do sistema
     * @return mixed
     */

    public function showAll()
    {
        return $this->repository->all();
    }


    /**
     * Cria cliente com validação
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {
        try {

            try {
                $this->validator->with($data)->passesOrFail();
                return $this->repository->create($data);
            } catch (ValidatorException $e) {
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
                'sqlError' => $e->getMessage(),
                'message' => 'Não foi possível criar o usuário. Ele já existe.'
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
                     'message' => 'Dados atualizados com sucesso',
                     'data' => $this->repository->update($data, $id)
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
         catch (ModelNotFoundException $e)
         {
             return[
                 'error' => $e->getMessage(),
                 'message' => 'Não foi possível atualizar os dados do usuário porque ele não existe.'
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
            catch(ValidatorException $e)
            {
                return[
                    'error_log' => $e->getCode(),
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile(),
                    'message_log' => $e->getMessageBag(),
                    'error' =>true,
                    'message'=> 'Usuário não existe'
                ];
            }

        }
        catch(ModelNotFoundException $e)
        {
            return[
                'error_log' => $e->getCode(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'message_log' => $e->getMessage(),
                'error' =>true,
                'message'=> 'Usuário não existe'
            ];
        }

    }


    /**
     * Exclui cliente de acordo com seu ID
     * @param $id
     * @return array message
     */

    public function delete($id)
    {
        try
        {
            try
            {
                $message = [
                    'message' => 'Usuário excluído com sucesso.',
                    'data' => $this->repository->delete($id)
                ];

                echo json_encode($message);
            }
            catch(ValidatorException $e)
            {
                $error = [
                    'error' => $e->getMessage(),
                    'message' => 'Erro ao excluir usuário.'
                ];

                echo json_encode($error);
            }
        }
        catch(ModelNotFoundException $e)
        {
            $error = [
                'error' => $e->getMessage(),
                'message' => 'Não foi possível excluir o usuário porque ele não existe.'
            ];

            echo json_encode($error);
        }
    }


    /**
     * Trás os projetos em que o usuário é membro
     *
     * @param $id
     * @return array|mixed
     */

    public function members($id)
    {

        try
        {

            try
            {
                return $this->repository->with(['projectsMember'])->find($id);
            }
            catch(ValidatorException $e)
            {
                return [
                    'error' => $e->getMessage(),
                    'message' => 'Usuário não participa de nenhum projeto.'
                ];
            }

        }
        catch(ModelNotFoundException $e)
        {
            return[
                'error' => $e->getMessage(),
                'message' => 'Usuário não existe.'
            ];
        }

    }


}