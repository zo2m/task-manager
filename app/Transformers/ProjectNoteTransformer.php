<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 21/09/15
 * Time: 22:28
 */

namespace TaskManager\Transformers;

use TaskManager\Entities\ProjectNote;
use League\Fractal\TransformerAbstract;

/**
 * Class ProjectNOteTransformer
 *
 * Formata a saída do Json conforme necessidade de exibição. Os métodos abaixo são obrigatórios do pacote Fractal.
 * É passado para a classe qual entidade ela deve serializar e retornar uma coleção de dados (array) com o formato
 * desejado. Para incluir mais uma ou várias coleções de dados, basta criar um novo método que deve chamar um transformer
 * referente à serielização que se deseja criar.
 *
 * @package TaskManager\Transformers
 */

class ProjectNoteTransformer extends TransformerAbstract
{


    /**
     * Método obrigatório onde é passada a entitade que se quer serializar. Retorna uma array com o formato
     * desejado para a saída Json.
     *
     * @param ProjectNote $projectNote
     * @return array
     */

    public function transform(ProjectNote $projectNote)
    {
        return[
            'title' => $projectNote->title,
            'note' => $projectNote->note,
            'date' => $projectNote->created_at,

        ];
    }



}