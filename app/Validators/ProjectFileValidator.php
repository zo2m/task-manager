<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 16/08/15
 * Time: 16:31
 */

namespace TaskManager\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectFileValidator extends LaravelValidator
{
    protected $rules = [
        'name' => 'required',
        'file'=> 'required',
        'description' => 'required',
        'project_id' => 'required|integer',
    ];


}