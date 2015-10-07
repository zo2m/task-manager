<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 21/09/15
 * Time: 22:28
 */

namespace TaskManager\Transformers;

use TaskManager\Entities\Project;
use League\Fractal\TransformerAbstract;
use TaskManager\Entities\ProjectNote;

/**
 * Class ProjectTransformer
 *
 * Formata a saída do Json conforme necessidade de exibição. Os métodos abaixo são obrigatórios do pacote Fractal.
 * É passado para a classe qual entidade ela deve serializar e retornar uma coleção de dados (array) com o formato
 * desejado. Para incluir mais uma ou várias coleções de dados, basta criar um novo método que deve chamar um transformer
 * referente à serielização que se deseja criar.
 *
 * @package TaskManager\Transformers
 */

class ProjectTransformer extends TransformerAbstract
{

    /**
     * Variável protegida chamada defaultIncludes onde devem ser passados todos os includes
     * que devem ser inseridas na serialização principal. São os subdados de uma consulta
     * principal. Sempre passar com nome em minúsculo.
     *
     * @var array
     */

    protected  $defaultIncludes = ['members', 'notes'];

    /**
     * Método obrigatório onde é passada a entitade que se quer serializar. Retorna uma array com o formato
     * desejado para a saída Json.
     *
     * @param Project $project
     * @return array
     */

    public function transform(Project $project)
    {
        return[
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'owner_id' => $project->user_id,
            'project_name' => $project->name,
            'project_description' => $project->description,
            'project_progress' => $project->progress,
            'project_status' => $project->status,
            'project_due_date' => $project->due_date,
            'project_created_at' => $project->created_at,
        ];
    }

    /**
     * Método para inclusão de dados na serialização principal. É passada a entidade que se quer serializar. Sempre
     * começar com o include seguindo do que se quer relacionar. No caso o includeMembers onde Members refere-se
     * ao relacionamento existente entre projetos e membros. Esse relacionamento está na entidade de Projetos.
     * Retorna uma nova coleção de dados onde é informado o relacionamento e qual transforme utilizar para serializar
     * a saída
     *
     * @param Project $project
     * @return \League\Fractal\Resource\Collection
     */

    public function includeMembers(Project $project)
    {

        return $this->collection($project->members, new ProjectMemberTransformer());

    }


    /**
     * Método para a inclusão de todas as notas do projeto
     *
     * @param Project $project
     * @return \League\Fractal\Resource\Collection
     */

    public function includeNotes(Project $project)
    {
        return $this->collection($project->notes, new ProjectNoteTransformer());
    }

}