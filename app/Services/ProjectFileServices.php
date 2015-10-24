<?php

namespace TaskManager\Services;

use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;
use TaskManager\Repositories\InterfaceProjectFileRepository;
use TaskManager\Repositories\InterfaceProjectRepository;
use TaskManager\Validators\ProjectFileValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

//Para upload
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;

//define('MAX_FILE_SIZE', 73360);

class ProjectFileServices
{
    /**
     * @var InterfaceProjectFileRepository
     */
    private $repository;
    /**
     * @var ProjectFileValidator
     */
    private $validator;
    /**
     * @var ProjectServices
     */
    private $projects;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var InterfaceProjectRepository
     */
    private $project;


    /**
     * @param InterfaceProjectFileRepository $repository
     * @param ProjectFileValidator $validator
     * @param ProjectServices $projects
     */
    public function __construct(InterfaceProjectFileRepository $repository, InterfaceProjectRepository $project, ProjectFileValidator $validator, Filesystem $filesystem, Storage $storage)
    {

        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->project = $project;
    }


    /**
     *
     * Método para upload de arquivos de projeto
     *
     * @param array $data
     * @throws ValidatorException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */

    public function createFile(array $data)
    {

        try
        {

            //pega os dados do projeto e tira o presenter para trazer a entidade
            $project = $this->project->skipPresenter()->find($data['project_id']);

            //caminho do arquivo
            $path = $data['project_id'].'/'.$data['extension'].'/';

            //mime type do arquivo
            $file_mime_type = $this->filesystem->mimeType($data['file']);

            //tamanho do arquivo
            $file_size = $this->filesystem->size($data['file'])/1024;

            //chama o método que valida o arquivo
            $is_valid_file = $this->validateFile($file_size, $data['extension'], $file_mime_type);


            if($is_valid_file === true)
            {
                //presiste os dados no banco
                $projectFile = $project->files()->create($data);

                //faz o upload do arquivo
                $this->storage->put($path . $projectFile->id . "." . $data['extension'], $this->filesystem->get($data['file']));

               if ($projectFile)
               {
                  echo 'Arquivo enviado com sucesso';
               }
            }
            else
            {
                echo $is_valid_file['message'];
            }


        }
        catch(ModelNotFoundException $e)
        {

            /*return[
                'error_log' => $e->getCode(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'message_log' => $e->getMessage(),
                'error' =>true,
                'message' => 'Você precisa informar o projeto para o qual este arquivo deve ser enviado.'
            ];*/

            echo 'Projeto não informado ou não existe.';

        }

    }


    /**
     *
     * Método privado para validação de arquivos
     *
     * @param $file_size
     * @param $extension
     * @param $mime_type
     * @return array|bool
     */

    private function validateFile($file_size, $extension, $mime_type)
    {

        //tipos de extensões permitidas
        $avaliables_extensions = array('txt', 'pdf', 'jpg', 'png', 'xls', 'xlsx', 'ppt', 'pptx', 'doc', 'docx', 'eps', 'tif', 'zip', 'rar', 'mp3', 'psd');

        //tipos de mime types permitidos
        $avaliables_mime_types = array('image/bmp', 'image/x-windows-bmp', 'application/msword', 'application/postscript', 'application/x-compressed', 'application/x-gzip', 'image/jpeg', 'audio/mpeg3', 'video/mpeg','application/pdf', 'image/png', 'application/mspowerpoint','application/mspowerpoint', 'text/plain', 'application/msword','application/excel', 'application/x-msexcel', 'application/zip', 'multipart/x-zip');

        //tamanho máximo de arquivo para upload
        $max_file_fize = 1024; //1MB

        //verifica se a extensão é permitida
        if(!in_array($extension, $avaliables_extensions))
        {
            return[
                'error' => true,
                'message'=> 'Extensão não permitida'
            ];
        }
        //verifica se o mime type é permitido
        elseif(!in_array($mime_type, $avaliables_mime_types))
        {
            return[
                'error' => true,
                'message' => 'Tipo de arquivo não permitido'
            ];
        }
        //verifica se o tamanho do arquivo é permitido
        elseif($max_file_fize < $file_size)
        {
            return [
                'error' => true,
                'message' => 'Arquivo muito grande. Tamanho máximo permitido '. round($max_file_fize/1024,2) .'MB.'
            ];
        }

        return true;
    }


    /**
     *
     * Método para a exclusão de arquivos (físicos) e registro no banco de dados
     *
     * @param $project_id
     * @param $filename
     * @return array
     */

    public function deleteFile($project_id, $file_id)
    {

        try
        {

            //verifica se o registro existe na tabela
            $info_file = $this->repository->findWhere(['id'=>$file_id]);

            //pega a extensão do arquivo
            foreach($info_file as $file)
            {
                $extension = $file['extension'];
            }

            //se a extensão não existir. Só ocorre em casos onde o registro já foi excluído
            //e se por algum motivo o parâmetro do id do registro for passado, ao executar
            //a consulta esse registro não será encontrado e gerará um erro pois a variável
            //$extensão  não será criada. Caso essa situação ocorra, a variável é criada
            //para evitar erro de execução. Como o arquivo não existe, o erro informando que
            //o arquivo não existe será exibido

            if(!isset($extension))
            {
                $extension = 'jpg';
            }

            //Cria caminho para o arquivo
            $path = 'projectfiles/' . $project_id . '/' . $extension . '/' . $file_id . '.' . $extension;



            //verifica se o arquivo realmente existe
            if (file_exists($path))
            {


                //se existir, exclui o arquivo e o registro desse arquivo no banco de dados
                if ($this->filesystem->delete($path) and $this->repository->delete($file_id))
                {

                    //retorna mensagem informando que tudo deu certo
                    return [
                        'error' => false,
                        'message' => 'Arquivo excluído com sucesso'
                    ];

                }

                //retorna erro caso algo dê errado
                return [
                    'error' => true,
                    'message' => 'Erro desconhecido, por favor contacte o administrador'
                ];

            }

            //se o arquivo não existir, retorna o erro
            else
            {

                return [
                    'error' => true,
                    'message' => 'Não foi possível excluir o arquivo porque ele não existe'
                ];

            }

        }

        //caso a consulta do registro retorne falso, informa o erro que o registro não existe no banco de dados
        catch(ModelNotFoundException $e)
        {

            return[
                'error' => true,
                'message' => 'O arquivo não existe.'
            ];

        }


    }


}