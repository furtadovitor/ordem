<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Grupo;

class Grupos extends BaseController
{

    private $grupoModel;

    public function __construct(){

        $this->grupoModel = new \App\Models\GrupoModel();

    }


    public function index()
    {


        $data = [

            'titulo' => 'Listando os grupos de acesso de permissões do sistema.'
        ];

        return view('Grupos/index', $data);
    }

    public function recuperaGrupos()
    {

        if (!$this->request->isAjax()) {

            return redirect()->back();
        }


        $atributos = [

            'id',
            'nome',
            'descricao',
            'exibir',
            'deletado_em',


        ];

        $grupos = $this->grupoModel->select($atributos)->withDeleted(true)->orderBy('ID', 'desc')->findAll();


        //recebe o array com os objetos de usuários
        $data = [];

        foreach ($grupos as $grupo) {


         

            $nomeGrupo = esc($grupo->nome);

            $data[] = [
                'id' => $grupo->id,
                'nome' => anchor("grupos/exibir/$grupo->id", esc($grupo->nome), 'title = "Exibir grupo ' . $nomeGrupo . '" '),
                'descricao' => esc($grupo->descricao),
                'exibir' => $grupo->exibeSituacao(),
            ];
        }

        $retorno = [

            'data' => $data,
        ];



        return $this->response->setJSON($retorno);
    }

    public function exibir(int $id = null)
    {

        $grupo = $this->buscaGrupoOu404($id);

        $data =  [

            'titulo' => 'Detalhando o grupo ' . esc($grupo->nome) . '',
            'grupo' => $grupo,

        ];


        return view('Grupos/exibir', $data);
    }

    private function buscaGrupoOu404(int $id = null)
    {

        if (!$id || !$grupo = $this->grupoModel->withDeleted(true)->find($id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Grupo não encontrado $id");
        }

        return $grupo;
    }
}
