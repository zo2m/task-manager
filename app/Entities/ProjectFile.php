<?php

namespace TaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectFile extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'name',
        'description',
        'extension',
    ];


    /**
     * Cria um relacionamento entre os arquivos e seus projetos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
