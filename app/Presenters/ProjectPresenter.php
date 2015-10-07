<?php

namespace TaskManager\Presenters;

use Prettus\Repository\Presenter\FractalPresenter;
use TaskManager\Transformers\ProjectTransformer;

/**
 * Classe que fica na camada entre o Trasnformer e o Repository. Apenas retorna qual Trasnformer deve utilizar para
 * formatar uma saída de dados.
 *
 * Class ProjectPresenter
 * @package TaskManager\Presenters
 */

class ProjectPresenter extends FractalPresenter
{

    public function getTransformer()
    {
        return new ProjectTransformer();
    }

}