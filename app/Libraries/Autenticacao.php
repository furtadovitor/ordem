<?php 

namespace App\Libraries;

class Autenticacao{

    private $usuario;
    private $usuarioModel;


    public function __construct(){

        $this->usuarioModel = new \App\Models\GrupoUsuarioModel();

    }
 
    
    //Métdo que realiza o login na aplicação.
    public function login(string $email, string $password): bool{

        //recuperando o usuário pelo Email
        $usuario = $this->usuarioModel->buscaUsuarioPorEmail($email);

        //verificando se o usuário foi encontrado
        if($usuario === null){
            return false;
        }

        //Verificamos se as senhas são idênticas
        if($usuario->verificaPassword($password) == false){

            return false;

        }

        //verificando se o usuário pode logar na aplicalão (se ele está ativo)
        if($usuario->ativo == false){
            return false;
        }

        //logamos na aplicação
        $this->logaUsuario($usuario);

        //retornando verdadeiro, pois deu tudo certo (usuário pode logar)
        return true;

    
    }

    public function logout(): void{

        session()->destroy();

    }

    public function pegaUsuarioLogado(){

        if($this->usuario === null){

            $this->usuario = $this->pegaUsuarioDaSessao();

        }

        return $this->usuario;
    }

    //método que verifica se o usuario esta logado
    public function estaLogado(){

        return $this->pegaUsuarioLogado() !== null;
    }

    //método responsável para pegar o id do usuário e setar na sessão
    private function logaUsuario(object $usuario): void{

        //recuperando a instância da sessão
        $session = session();

        //Antes de inserir o ID do usuário, devo gerar um novo ID da SESSÃO. (boa prática de prog)
        $session->regenerate();

        //setando na sessão o id do usuário
        $session->set('usuario_id', $usuario->id);
    }


    //método que recupera sessão o usuário logado
    private function pegaUsuarioDaSessao(){

        if(session()->has('usuario_id') == false){
            return null;
        }

        //busco o usuário na base de dados
        $usuario = $this->usuarioModel->find(session()->get('usuario_id'));

        //verificando se o usuário existe e se tem permissão de login na aplicação
        if($usuario == null || $usuario->ativo == false){
            return null;
        }
        


    }



}