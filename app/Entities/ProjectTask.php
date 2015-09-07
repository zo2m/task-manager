<?php

namespace TaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectTask extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'id',
        'name',
        'project_id',
        'start_date',
        'due_date',
        'status'
    ];


    /**
     * Cria um relacionamento entre um projeto e sua tarefa.
     * Uma tarefa só pode pertencer a um projeto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public  function project()
    {
        return $this->belongsTo(Project::class);
    }

}
