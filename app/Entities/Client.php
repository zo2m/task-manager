<?php

namespace TaskManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'responsible', 'email', 'phone', 'address', 'obs'];

    /**
     * Cria o relacionamento da classe cliente e projetos
     * Um cliente pode participar de vários projetos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function project()
    {
        return $this->hasMany(Project::class);
    }
}
