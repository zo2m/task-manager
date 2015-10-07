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
use TaskManager\Repositories\InterfaceProjectMembersRepository;
use TaskManager\Validators\ProjectMemberValidator;


class ProjectMembersServices
{
    //variável para armazenar os erros e exibir depois no formato json
    var $error = array();

    /**
     * @var InterfaceProjectMembersRepository
     */
    protected $repository; //instância do repositório InterfaceProjectMembersRepository
    private $validator; //instância da classe ProjectMemberValidator
    private $is_member; //armazena verificação se o usuário é ou não membro do projeto


    /**
     * @param InterfaceProjectMembersRepository $repository
     * @param ProjectMemberValidator $validator
     */

    public function __construct(InterfaceProjectMembersRepository $repository, ProjectMemberValidator $validator)
    {
        $this->repository = $repository;
    }



    /**
     * Adiciona um membro ao projeto
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param array $data
     * @return array|mixed
     */

    public function create(array $data)
    {
        try
        {
            if($this->repository->create($data))
            {
                return [
                    'error' => false,
                    'message' => 'Membro adicionado ao projeto com sucesso.'
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
                'message' => 'Para inserir um membro ao projeto, por favor informe o usuário e o projeto.'
            ];
        }

    }


    /**
     * Atualiza dados do cliente com validação
     * status = não validado
     *
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
                    'error'=> false,
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
     * Exclui cliente de acordo com seu ID
     * status = validado em 06/09/2015 por Alexandre
     *
     * @param $id
     * @return int
     */

    public function delete($project_id, $member_id)
    {

       try
        {
            //verifia se há um membro no projeto. Se houver, executa a exclusão
            $this->is_member = $this->isMember($member_id, $project_id);

            if($this->is_member['error'] == true)
            {
                if(\TaskManager\Entities\ProjectMembers::where('project_id','=', $project_id)->where('member_id', '=', $member_id)->delete())
                {
                    return[
                        'error' => false,
                        'message' => 'Membro excluído com sucesso.'
                    ];
                };
            }
            else
            {
                return[
                    'error' => true,
                    'message' => 'Não foi possível excluir o membro do projeto porque ele não existe.'
                ];
            }

        }
        catch(ModelNotFoundException $e)
        {

           $error = [
               'error' => true,
               'message' => 'Não foi possível excluir o membro do projeto.'
           ];

            echo json_encode($error);

        }

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

        //se a consulta trouxer algum valor, o membro já participa do projeto.

        if (count($this->repository->findWhere(['project_id' => $project_id, 'member_id' => $member_id])))
        {
            return [
                    'error' => true,
                    'message' => 'Este membro já participa deste projeto'
            ];
        }
        else
        {
            return [
                'error' => false,
                'message' => 'Este membro não faz parte deste projeto.'
            ];
        }

    }


}