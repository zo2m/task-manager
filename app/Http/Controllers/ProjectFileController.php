<?php

namespace TaskManager\Http\Controllers;

use TaskManager\Validators\ProjectFileValidator;
use Validator;
use Illuminate\Http\Request;
use TaskManager\Http\Requests;
use TaskManager\Services\ProjectServices;

define('MAX_FILE_SIZE', 73360);

class ProjectFileController extends Controller
{



    /**
     * @var ProjectServices
     */

    private $repository;
    /**
     * @var ProjectFileValidator
     */
    private $validator;


    public function __construct(ProjectServices $services, ProjectFileValidator $validator)
    {
        $this->services = $services;
        $this->validator = $validator;
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
     * Apaga um arquivo com base em seu id
     * @param $id
     * @return int
     */

    public function destroy($filename)
    {
        return $this->services->deleteFile($name);
    }

    public function apagar($project_id, $filename)
    {
        return $this->services->deleteFile($project_id, $filename);
    }





}
