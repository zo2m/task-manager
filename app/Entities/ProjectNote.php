<?php

namespace TaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectNote extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['project_id', 'title', 'note'];


    /**
     * Cria relacionamento com a classe de projetos
     * Uma nota só pode pertencer a um projeto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
