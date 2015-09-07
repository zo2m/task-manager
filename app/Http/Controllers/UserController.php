<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Http\Request;
use TaskManager\Services\UserServices;

class UserController extends Controller
{

    /**
     * @var UserServices
     */

    private $service;


    /**
     * Método construtor para instanciar o serviço de usuários
     * @param UserServices $service
     */

    public function __construct(UserServices $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        //return $this->repository->all();
        return $this->service->showAll();
    }


    /**
     * Trás todos os usuários e seus projetos
     * @return mixed
     */

    public function showAllUserWithProject()
    {
        return $this->service->showAllUserWithProject();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    public function show($id)
    {
        return $this->service->show($id);
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
        return $this->service->update($request->all(), $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
       $this->service->delete($id);
    }


    /**
     * Verifica se um usuário é membro de projetos
     * @param $id
     * @return array|mixed
     */

    public function member($id)
    {
        return $this->service->members($id);
    }
}
