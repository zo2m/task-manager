<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Http\Request;
use TaskManager\Http\Requests;
use TaskManager\Services\ProjectServices;


class ProjectController extends Controller
{


    /**
     * @var ProjectServices
     */

    private $repository;


    public function __construct(ProjectServices $services)
    {

        $this->services = $services;

    }


    /**
     * Mostra todos os projetos
     * @return mixed
     */

    public function index()
    {
        return $this->services->showAll();
    }


    /**
     * Mostra todos os projetos
     * @return mixed
     */

    public function showAll()
    {
        return $this->services->showAll();
    }


    /**
     * Grava um projeto no banco de dados
     * @param Request $request
     * @return array|mixed
     */

    public function store(Request $request)
    {
        return $this->services->create($request->all());
    }


    /**
     * Mostra um projeto com base em seu id. Só exibe se o projeto se o usuário logado for
     * proprietário do projeto
     *
     * @param $id
     * @return mixed
     */

    public function show($id)
    {
        return $this->services->show($id);
    }


    /**
     * Atualiza um projeto com base em seu id
     * @param Request $request
     * @param $id
     * @return array|mixed
     */

    public function update(Request $request, $id)
    {
        return $this->services->update($request->all(), $id);
    }


    /**
     * Apaga um projeto com base em seu id
     * @param $id
     * @return int
     */

    public function destroy($id)
    {
        return $this->services->delete($id);
    }


     /**
     * Trás os membros do projeto
     *
     * @param $id
     * @return array|mixed
     */

    public function members($id)
    {
        return $this->services->members($id);
    }


    /**
     * Adicinoa membros ao projeto
     *
     * @param Request $request
     * @return array|mixed
     */

    public function addMembers(Request $request)
    {
        return $this->services->addMembers($request->all());
    }


    /**
     * Remove membro de um projeto
     *
     * @param $project_id
     * @param $member_id
     * @return int
     */

    public function removeMember($project_id, $member_id)
    {
        return $this->services->removeMember($member_id, $project_id);
    }


}
