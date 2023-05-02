<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

    private $usuarioModel;

    public function __construct(){

        $this->usuarioModel = new \App\Models\UsuarioModel();


    }

    public function index()
    {
        
        
        $data = [

            'titulo' => 'Listando os usuários do sistema.'
        ];

        return view('Usuarios/index', $data);
        
    }

    public function recuperaUsuarios(){

         if(!$this->request->isAjax()){
            
             return redirect()->back();
         }


        $atributos = [

            'id',
            'nome',
            'email',
            'cpf',
            'ativo',
            'imagem',


        ];

        $usuarios = $this->usuarioModel->select($atributos)->orderBy('ID', 'desc')->findAll();

  
        //recebe o array com os objetos de usuários
        $data = [];
  
        foreach($usuarios as $usuario){

            $nomeUsuario = esc($usuario->nome);

            $data[] = [
                'imagem' => $usuario->imagem,
                'id' => $usuario->id,
                'nome' => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title = "Exibir usuário '. $nomeUsuario. '" '),
                'email' => esc($usuario->email),
                'cpf' => $usuario->cpf,
                'ativo' => ($usuario->ativo == true ? '<span class="text-success">Ativo <i class="fa fa-unlock"></i></span>' : '<span class="text-danger">Inativo <i class="fa fa-lock"></i></span>'),
            ];


        }

        $retorno = [

            'data' => $data,
        ];


        
        return $this->response->setJSON($retorno);

    }


    public function criar(int $id = null){

        $usuario = new Usuario();
                
        $data =  [
          
            'titulo' => 'Criando um novo usuário ',
            'usuario' => $usuario,
            
        ];


        return view('Usuarios/criar', $data);





    }

    public function cadastrar(){
        

        if(!$this->request->isAjax()){
            
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

        //criando um objeto da classe entidade usuário
        $usuario = new Usuario($post);

        //desabilitei a proteção por conta do "ativo".
        //Só pode ser desabilidade a proteção pois esse formulário específico só é manipulado por ADMIN.
        if($this->usuarioModel->protect(false)->save($usuario)){

            $btnCriar = anchor("usuarios/criar", 'Cadastrar novo usuário', ['class' => 'btn btn-danger mt-2' ]);

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', "Dados salvos com sucesso! <br> $btnCriar");

            //retornamos o último ID insetiro na tabela de usuários
            $retorno['id'] = $this->usuarioModel->getInsertID();

            return $this->response->setJSON($retorno);

        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();



        //retorno para o ajax request
        return $this->response->setJSON($retorno);

    }

    public function exibir(int $id = null){
        
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data =  [
          
            'titulo' => 'Detalhando o usuário '. esc($usuario->nome). '',
            'usuario' => $usuario,
            
        ];


        return view('Usuarios/exibir', $data);





    }

    public function editar(int $id = null){
        
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data =  [
          
            'titulo' => 'Editando o usuário '. esc($usuario->nome). '',
            'usuario' => $usuario,
            
        ];


        return view('Usuarios/editar', $data);





    }

    public function editarimagem(int $id = null){
        
        $usuario = $this->buscaUsuarioOu404($id);
        
        $data =  [
          
            'titulo' => 'Alterando a imagem do usuário '. esc($usuario->nome). '',
            'usuario' => $usuario,
            
        ];


        return view('Usuarios/editar_imagem', $data);

    }

    public function atualizar(){
        

        if(!$this->request->isAjax()){
            
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
        $usuario = $this->buscaUsuarioOu404($post['id']);


        // Se não for irformado a senha, removemos do $post.
        // Pois, se não fizermos dessa forma, o hashPassword fará o hash de uma string vazia
        if(empty($post['password'])){
            unset($post['password']);
            unset($post['password_comfirmation']);
            
        }
    
    
        //Preenchendo os atributos do usuário com os valores do post
        $usuario->fill($post);

        if($usuario->hasChanged() == false){

            $retorno['info'] = 'Não há dados para serem atualizados';
            
            return $this->response->setJSON($retorno);
        }

        //desabilitei a proteção por conta do "ativo".
        //Só pode ser desabilidade a proteção pois esse formulário específico só é manipulado por ADMIN.
        if($this->usuarioModel->protect(false)->save($usuario)){

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', 'Dados salvos com sucesso');

            return $this->response->setJSON($retorno);

        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();



        //retorno para o ajax request
        return $this->response->setJSON($retorno);

    }

    // // //Horário para manipulação de folha de ponto 
    //  public function horario($dias = 20){

     
    //      for ($i=1; $i <= $dias ; $i++) { 
            
        
    //     $hora_ini_aleatorio = rand(7,8);
       
    //     if($hora_ini_aleatorio == 7){
    //      $minuto_ini_aleatorio = rand(48,59);
    //     } else{
    //      $minuto_ini_aleatorio = rand(00,14);
    //     }
       
    //     $hora_ini_almoco_aleatorio = rand(11,12);
       
    //     if($hora_ini_almoco_aleatorio == 7){
    //      $min_ini_almoco_aleatorio = rand(30,59);
    //     } else{
    //      $min_ini_almoco_aleatorio = rand(00,59);
    //     }     


    //     $minuto_fim = rand(1, 7);
    //     $ini =  mktime($hora_ini_aleatorio, $minuto_ini_aleatorio, 0, 7, 1, 2000);
       
    //     $ini_almoco  = mktime($hora_ini_almoco_aleatorio, $min_ini_almoco_aleatorio, 0, 7, 1, 2000);
       
    //     $fim_almoco  = mktime($hora_ini_almoco_aleatorio+1, $min_ini_almoco_aleatorio + rand(1,6), 0, 7, 1, 2000);

    //     $fim =  mktime($hora_ini_aleatorio + 9, $minuto_ini_aleatorio + $minuto_fim, 0, 7, 1, 2000);
       
    //     echo $i .'---' . date('H:i:s', $ini).'---------------'.date('H:i:s', $ini_almoco) . '-----------'.date('H:i:s', $fim_almoco) . '-----------'. date('H:i:s', $fim).'<br>';

    //  }
    //  }



    //método que recupera o usuário no banco de dados
    private function buscaUsuarioOu404(int $id = null){

        if(!$id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)){

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuário não encontrado $id");
        }

        return $usuario;
    }


}
