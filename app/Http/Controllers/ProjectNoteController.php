<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Http\Request;

use TaskManager\Http\Requests;
use TaskManager\Services\ProjectNoteServices;

class ProjectNoteController extends Controller
{


    /**
     * @var ProjectNoteServices
     */
    private $services;


    public function __construct(ProjectNoteServices $services)
    {

        $this->services = $services;
    }


    /**
     * Retorna todos os notes com seus respectivos projetos
     *
     * @return mixed
     */
    public function index()
    {
        return $this->services->showAll();
    }


    /**
     * Grava as notas em seu respectivo projeto
     *
     * @param Request $request
     * @return array|mixed
     */

    public function store(Request $request)
    {
        return $this->services->create($request->all());
    }


    /**
     * Mostra apenas uma nota de um projeto
     *
     * @param $id
     * @param $noteId
     * @return mixed
     */

    public function show($id, $noteId)
    {
        return $this->services->show($id, $noteId);
    }


    /**
     * Mostra todas as notas de um projeto com base em seu id
     * @param $id
     * @return mixed
     */

    public function showAllNotesFromProject($id)
    {
        return $this->services->showAllNotesFromProject($id);
    }


    /**
     * Atualiza uma nota específica com base em seu id
     * @param Request $request
     * @param $noteId
     * @return array|mixed
     */

    public function update(Request $request, $noteId)
    {
        return $this->services->update($request->all(), $noteId);
    }


    /**
     * Apaga uma nota específica com base em seu id
     * @param $noteId
     * @return int
     */

    public function destroy($noteId)
    {
        return $this->services->delete($noteId);
    }
}
