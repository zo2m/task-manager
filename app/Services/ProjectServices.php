<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 16:23
 */

namespace TaskManager\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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

    protected $member;

    private $user;

    private $is_project; //Armazena verificação se projeto existe
    private $is_user; //Armazena verificação se usuário existe
    private $is_member; //Armazena verificação se é membro do projeto


    /**
     * Classe constrtutora para incialização de instâncias
     *
     * @param InterfaceProjectRepository $repository
     * @param ProjectValidator $validator
     * @param ProjectMembersServices $member
     * @param UserServices $user
     */

    public function __construct(InterfaceProjectRepository $repository, ProjectValidator $validator, ProjectMembersServices $member, UserServices $user)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->member = $member;
        $this->user = $user;
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
        catch(QueryException $e)
        {
            return[
                'error_log' => $e->getCode(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'message_log' => $e->getMessage(),
                'error' => true,
                'message' => 'Erro ao criar o projeto'
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

                    'error_log' => $e->getCode(),
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile(),
                    'message_log' => $e->getMessageBag(),
                    'error' => true,
                    'message' => 'O projeto que você quer atualizar não existe.'
                ];
            }
       }
       catch(ModelNotFoundException $e)
       {
           return [
               'error_log' => $e->getCode(),
               'error_line' => $e->getLine(),
               'error_file' => $e->getFile(),
               'message_log' => $e->getMessage(),
               'error' => true,
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
                return [
                    'error' => false,
                    'data' => $this->repository->find($id)
                ];
            }
            catch (ValidatorException $e)
            {
                return [
                    'error_log' => $e->getCode(),
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile(),
                    'message_log' => $e->getMessageBag(),
                    'error' => true,
                    'message' => 'Projeto não existe.'
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
            return [
                'error_log' => $e->getCode(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'message_log' => $e->getMessage(),
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
                        'error'=>true,
                        'message'=>'Projeto excluído com sucesso.'
                    ];

                };
            }
            catch (ValidatorException $e)
            {
               return [
                   'error_log' => $e->getCode(),
                   'error_line' => $e->getLine(),
                   'error_file' => $e->getFile(),
                   'message_log' => $e->getMessageBag(),
                   'error'=>true,
                   'message'=>'Projeto excluído com sucesso.'
                ];
            }
        }
        catch(ModelNotFoundException $e)
        {
          return [
              'error_log' => $e->getCode(),
              'error_line' => $e->getLine(),
              'error_file' => $e->getFile(),
              'message_log' => $e->getMessage(),
              'error' => true,
              'message' => 'Não foi possível excluir o projeto porque ele não existe.'
           ];
        }

    }


    /**
     * Classe para verificar se projeto existe
     *
     * @param $id
     * @return int
     */

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


    /**
     * Trás todos os membros do projeto
     * status = válidado em 06/09/2015 por Alexandre
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
                return $this->repository->with(['members'])->find($id);
            }
            catch(ValidatorException $e)
            {
                return [
                    'error_log' => $e->getCode(),
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile(),
                    'message_log' => $e->getMessageBag(),
                    'error' => true,
                    'message' => 'Nenhum membro no projeto'
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
                'message' => 'Não há membros para o projeto porque ele não existe.'
            ];
        }

    }


    /**
     * Adiciona membros ao projeto
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param array $data
     * @return array|mixed
     */

    public function addMembers(array $data)
    {
        //antes de adicionar faz validação para ver se o membro já pertence ao projeto, se o projeto existe e se o
        //membro existe

        $this->is_member = $this->isMember($data['member_id'], $data['project_id']);

        //se não houver erros, adiciona o membro ao projeto
        return $this->is_member['error'] == false ? $this->member->create($data) : [
            'message' => $this->is_member['message']
        ];

    }


    /**
     * Verifica se um usuário é membro do projeto
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param $member_id
     * @param $project_id
     * @return array
     */

    public function isMember($member_id, $project_id)
    {

        //verifica se o membro existe e se o projeto existe
        $this->is_user = $this->user->show($member_id);
        $this->is_project = $this->show($project_id);

        //se não houver erro, ou seja, se o usuário existir, verifica o projeto
        if($this->is_user['error'] <> true)
        {
            //se não houver erro, ou seja, se o projeto existir, executa a verificação
            return $this->is_project['error'] <> true ? $this->member->isMember($member_id, $project_id) : [
                'error' => true,
                'error-code' => 'project-not-exists',
                'message' => 'Este projeto não existe.'
            ];
        }
        else
        {
            return [
                'error' => true,
                'error-code' => 'member-not-exists',
                'message'=> 'Este membro não existe.'
            ];
        }

    }


    /**
     * Exclui o membro do projeto. Não foi necessário utilizar o método de verificação de membros do projeto isMember da classe
     * ProjecServices porque não havia a necessidade de verificar se um usuário ou projeto existe uma vez que isso foi realizado
     * quando um membro foi adicionado ao projeto. Esta validação já foi realizada na classe ProjectMembersServices
     *
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param $member_id
     * @param $project_id
     * @return int
     */

    public function removeMember($member_id, $project_id)
    {
        return $this->member->delete($project_id, $member_id);
    }


}