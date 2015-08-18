<?php

namespace TaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Project extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'description',
        'progress',
        'status',
        'due_date'
    ];


    /**
     * Cria relacionamento com a classe de notas
     * Um projeto pode ter várias notas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }


    /**
     * Cria o relacionamento entre usuários e projetos
     * Um usuário pode participar de vários projetos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Cria o relacionamento entre Cliente e projetos
     * Um cliente pode participar de vários projetos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
