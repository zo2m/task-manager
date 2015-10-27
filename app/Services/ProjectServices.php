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
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use TaskManager\Services\AuthServices;

use Illuminate\Filesystem\Filesystem;


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
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var ProjectFileServices
     */
    private $upload_file;


    /**
     * Classe constrtutora para incialização de instâncias
     *
     * @param InterfaceProjectRepository $repository
     * @param ProjectValidator $validator
     * @param ProjectMembersServices $member
     * @param UserServices $user
     */

    public function __construct(InterfaceProjectRepository $repository, ProjectValidator $validator, ProjectMembersServices $member, UserServices $user, ProjectFileServices $upload_file, Filesystem $filesystem)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->member = $member;
        $this->user = $user;
        $this->upload_file = $upload_file;
        $this->filesystem = $filesystem;
    }

    /**
     * Trás a listagem de todos os projetos com seus clientes, notas e usuários
     * @return mixed
     */

    public function showAll()
    {
        //return $this->repository->with(['user','notes', 'client'])->find(Authorizer::getResourceOwnerId());
        return $this->repository->findWhere(['user_id' => Authorizer::getResourceOwnerId()]);
    }


    /**
     * Cria projeto com validação
     *
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {
        try
        {
            try
            {

                //Se o diretório principal do projeto não existir, cria.
                if(!$this->filesystem->exists(public_path('projectfiles')))
                {
                    $directory = public_path('projectfiles');
                    $this->filesystem->makeDirectory($directory);
                }

                //validação de dados
                $this->validator->with($data)->passesOrFail();

                //se não ocorreu nenhum erro, cria o projeto
                $project = $this->repository->create($data);

                //pega o id do projeto recém criado
                $project_id = $project['data']['project_id'];

                //cria o diretório do projeto de acordo com seu id
                $directory = public_path('projectfiles/').$project_id;

                $this->filesystem->makeDirectory($directory);

                return [
                    'error' => false,
                    'message' => 'Projeto criado com sucesso.'
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
     * Atualiza dados do projeto com validação
     *
     * @param array $data
     * @param $id
     * @return array|mixed
     */

    public function update(array $data, $id)
    {
       try
       {

           if($this->isProjectOwner($id) == false)
           {

               return [
                   'message' => 'Você não possue permissão para atualizar este projeto',
               ];

           }

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
     * Mostra apenas um projeto de acordo com seu ID e se for do usuário logado
     *
     * @param $id
     * @return mixed
     */

    public function show($id)
    {
        try
        {

            if($this->checkProjectPermissions($id) == false)
            {
                return [
                    'message' => 'Você não possui permissão para visualizar este projeto',
                ];
            }

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
     * Exclui projeto de acordo com seu ID
     *
     * @param $id
     * @return int
     */

    public function delete($id)
    {

        try
        {

            if($this->isProjectOwner($id) == false)
            {
                return [
                    'message' => 'Você não possui permissão para apagar este projeto',
                ];
            }

            try
            {

                //apaga o registro do projeto do banco e o diretório de projetos contento todos os arquivos
                if($this->repository->delete($id) and $this->upload_file->deleteFilesProject($id))
                {
                    return [
                        'error'=>false,
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


    /**
     * Classe para verificar se projeto existe
     *
     * @param $id
     * @return int
     */

    public function verifyIfProjectExists($id)
    {

        $data = $this->repository->findWhere(['id' => $id]);

        if(!empty($data['data']))
        {
            return true;
        }

        return false;
    }


    /**
     * Verifica se um usuário é membro do projeto
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param $member_id
     * @param $project_id
     * @return array
     */

    private function isMember($project_id)
    {

        //pega o id do usuário logado
        $member_id = Authorizer::getResourceOwnerId();

        $this->is_member = $this->member->isMember($member_id, $project_id);

        if($this->is_member['error'] == true)
        {
            return true;
        }

        return false;

    }


    /**
     * Verifica se um membro é dono do projeto
     *
     * @param $project_id
     * @return array|bool
     */

    private function isProjectOwner($project_id)
    {
        //pega o id do usuário logado
        $user_id = Authorizer::getResourceOwnerId();

        if ($this->repository->isOwner($project_id, $user_id) == false)
        {
            return false;
        }

        return true;
    }


    /**
     * Método utilizado para validar as permissões do projeto
     *
     * @param $project_id
     * @return array|bool
     */

    private function checkProjectPermissions($project_id)
    {

        //pega o resultado se o projeto existe
        $this->is_project = $this->verifyIfProjectExists($project_id);

        //se existir verifica se o usuário é proprietário ou membro. Se for
        //retorna true
        if($this->is_project == true)
        {

            if ($this->isProjectOwner($project_id) or $this->isMember($project_id))
            {
                return true;
            }
            return false;

        }

        return  'Projeto não existe.';

    }


    /**
     * Método para upload e persistência em banco de dados
     *
     * @param array $data
     */

    public function createFile(array $data)
    {
        $is_owner_or_member = $this->checkProjectPermissions($data['project_id']);

        if($is_owner_or_member == true)
        {
            return $this->upload_file->createFile($data);
        }
        else
        {
            echo 'Você não tem permissão para enviar este arquivo para este projeto.';
        }

    }


    /**
     * Método para exclusão de arquivo e registro no banco de dados
     *
     * @param $project_id
     * @param $file_id
     * @return array
     */

    public function deleteFile($project_id, $file_id)
    {
        $is_owner_or_member = $this->checkProjectPermissions($project_id);

        if($is_owner_or_member == true)
        {
            return $this->upload_file->deleteFile($project_id, $file_id);
        }
        else
        {
            echo 'Você não tem permissão para enviar este arquivo para este projeto.';
        }

    }


}