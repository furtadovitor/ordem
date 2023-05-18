<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Grupo;

class Grupos extends BaseController
{

    private $grupoModel;
    private $grupoPermissaoModel;

    public function __construct(){

        $this->grupoModel = new \App\Models\GrupoModel();
        $this->grupoPermissaoModel = new \App\Models\GrupoPermissaoModel();

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

    public function criar(int $id = null)
    {
 
        $grupo = new Grupo();

        $data =  [

            'titulo' => 'Criando um novo grupo ',
            'grupo' => $grupo,

        ];


        return view('Grupos/criar', $data);
    }


    public function cadastrar()
    {


        if (!$this->request->isAjax()) {

            return redirect()->back();
        }

        //envio do hash do token do form
        $retorno['token'] = csrf_hash();

      
        //recupero o post da requisição
        $post = $this->request->getPost();

        //criando um objeto da classe entidade grupo
        $grupo = new Grupo($post);

        if ($this->grupoModel->save($grupo)) {

            $btnCriar = anchor("grupos/criar", 'Cadastrar novo grupo', ['class' => 'btn btn-danger mt-2']);

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', "Dados salvos com sucesso! <br> $btnCriar");

            //retornamos o último ID insetiro na tabela de grupos
            $retorno['id'] = $this->grupoModel->getInsertID();

            return $this->response->setJSON($retorno);
        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->grupoModel->errors();



        //retorno para o ajax request
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
         
            //return redirect()->back()->with('error', ' O Grupo <b>' . esc($grupo->nome) . '</b> não pode ser editado ou excluído');

            $retorno['erro'] = 'Por favor, verifique os erros abaixo e tente novamente.';
            $retorno['erros_model'] = ['grupo' => ' O Grupo <b>' . esc($grupo->nome) . '</b> não pode ser editado ou excluído'];

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

    public function excluir(int $id = null)
    {

        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->id < 3){
         
            return redirect()->back()->with('error', ' O Grupo <b>' . esc($grupo->nome) . '</b> não pode ser editado ou excluído');
        }


        if($grupo->deletado_em != null){

        return redirect()->back()->with('info', " Usuário já encontra-se excluído.");

        }


        if($this->request->getMethod() === 'post'){


            //Excluindo o usuário
            $this->grupoModel->delete($grupo->id);

            //$this->grupoModel->save($grupo);

            //retornando para a view grupos
            return redirect()->to(site_url('grupos'))->with('sucesso', "Grupo $grupo->nome excluído com sucesso");


        }

        $data =  [

            'titulo' => 'Excluindo o usuário ' . esc($grupo->nome) . '',
            'grupo' => $grupo,

        ];


        return view('Grupos/excluir', $data);
    }

    public function restaurarExclusao(int $id = null)
    {

        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->deletado_em == null){

            return redirect()->back()->with('info', " Apenas usuários excluídos podem ser recuperados.");

        }

        $grupo->deletado_em = null;

        $this->grupoModel->protect(false)->save($grupo);

        return redirect()->back()->with('sucesso', " Grupo $grupo->nome recuperado com sucesso.");


    }

    public function permissoes(int $id = null)
    {

        $grupo = $this->buscaGrupoOu404($id);

        if($grupo->id < 3){
         
            return redirect()->back()->with('info', ' Não é necessário atribuir permissões de acesso para o grupo <b>' . esc($grupo->nome) . '</b>');
        }

        $grupo->permissoes = $this->grupoPermissaoModel->recuperaPermissoesDoGrupo($grupo->id, 5);
        $grupo->pager = $this->grupoPermissaoModel->pager;


        $data =  [

            'titulo' => 'Gerenciando as permissões do grupo de acesso ' . esc($grupo->nome) . '',
            'grupo' => $grupo,

        ];


        return view('Grupos/permissoes', $data);
    }

    private function buscaGrupoOu404(int $id = null)
    {

        if (!$id || !$grupo = $this->grupoModel->withDeleted(true)->find($id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Grupo não encontrado $id");
        }

        return $grupo;
    }
}
