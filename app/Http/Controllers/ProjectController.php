<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Http\Request;

use TaskManager\Http\Requests;
use TaskManager\Repositories\InterfaceProjectRepository;
use TaskManager\Services\ProjectServices;

class ProjectController extends Controller
{


    /**
     * @var ProjectServices
     */
    private $services;


    public function __construct(ProjectServices $services)
    {

        $this->services = $services;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->services->showAll();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->services->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->services->show($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->services->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->services->delete($id);
    }
}
