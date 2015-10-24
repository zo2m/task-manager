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
        'name' => 'required|max:255',
        'file' => 'required|max:204800|mimes:jpeg,bmp,png,jpg,psd,mp3,xls,xlsx,doc,docx,pps,ppsx,pdf,eps,zip,rar,tif',
        'description' => 'required',
        'project_id' => 'required|integer'
    ];


}