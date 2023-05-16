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

    public function editar(int $id = null)
    {

        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->id < 3){
         
            return redirect()->back()->with('error', ' O Grupo <b>' . esc($grupo->nome) . '</b> não pode ser editado ou excluído');
        }

        $data =  [

            'titulo' => 'Editando o grupo ' . esc($grupo->nome) . '',
            'grupo' => $grupo,

        ];


        return view('Grupos/editar', $data);
    }

    public function atualizar()
    {


        if (!$this->request->isAjax()) {

            return redirect()->back();
        }

        //envio do hash do token do form
        $retorno['token'] = csrf_hash();

        // $retorno['erro'] = "Essa é uma mensagem de erro de validação. ";
        // $retorno['erros_model'] = [
        //     'nome' => 'O nome é obrigatório.',
        //     'email' => 'E-mail inválido',        

        // ];


        //recupero o post da requisição
        $post = $this->request->getPost();



        //validamos a existência do usuário
        $grupo = $this->buscaGrupoOu404($post['id']);

        if($grupo->id < 3){
         
            return redirect()->back()->with('error', ' O Grupo <b>' . esc($grupo->nome) . '</b> não pode ser editado ou excluído');

            $retorno['erro'] = 'Por favor, verifique os erros abaixo e tente novamente.';
            $retorno['erros_model'] = $this->grupoModel->errors();

            return $this->response->setJSON($retorno);
        }




        //Preenchendo os atributos do usuário com os valores do post
        $grupo->fill($post);

        if ($grupo->hasChanged() == false) {

            $retorno['info'] = 'Não há dados para serem atualizados';

            return $this->response->setJSON($retorno);
        }

        //desabilitei a proteção por conta do "ativo".
        //Só pode ser desabilidade a proteção pois esse formulário específico só é manipulado por ADMIN.
        if ($this->grupoModel->protect(false)->save($grupo)) {

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', 'Dados salvos com sucesso');

            return $this->response->setJSON($retorno);
        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->grupoModel->errors();



        //retorno para o ajax request
        return $this->response->setJSON($retorno);
    }

    private function buscaGrupoOu404(int $id = null)
    {

        if (!$id || !$grupo = $this->grupoModel->withDeleted(true)->find($id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Grupo não encontrado $id");
        }

        return $grupo;
    }
}
