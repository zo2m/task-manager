<?php

namespace TaskManager\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use TaskManager\Http\Requests;
use TaskManager\Services\ProjectServices;


class ProjectFileController extends Controller
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
     * Faz upload de arquivos
     * @param Request $request
     * @return array|mixed
     */

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'file' => 'required',
            'description' => 'required',
            'project_id' => 'required|integer'
        ]);

        if ($validator->fails())
        {
            return $validator->messages();
        }


        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['project_id'] = $request->project_id;

        $this->services->createFile($data);


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

    public function destroy($filename)
    {
        dd($filename);
        return $this->services->deleteFile($name);
    }

    public function apagar($project_id, $filename)
    {
        return $this->services->deleteFile($project_id, $filename);
    }





}
