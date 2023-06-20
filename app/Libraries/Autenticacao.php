<?php 

namespace App\Libraries;

class Autenticacao{

    private $usuario;
    private $usuarioModel;
    private $grupoUsuarioModel;


    public function __construct(){

        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->grupoUsuarioModel = new \App\Models\GrupoUsuarioModel();


    }
 
    
    //Métdo que realiza o login na aplicação.
    public function login(string $email, string $password): bool{

        //recuperando o usuário pelo Email
        $usuario = $this->usuarioModel->buscaUsuarioPorEmail($email);

        //verificando se o usuário foi encontrado
        if($usuario === null){

            exit('Usuário não encontrado');
    
            return false;
        }

        //Verificamos se as senhas são idênticas
        if($usuario->verificaPassword($password) == false){
            exit('Senha incorreta');
            return false;

        }

        //verificando se o usuário pode logar na aplicalão (se ele está ativo)
        if($usuario->ativo == false){
            exit('Inativo');

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

    /** método que define as permissões que o usuário logado possui  
     * Usado exclusivamente no método pegaUsuarioDaSessao()
    */
    private function definePermissoesDoUsuarioLogado($usuario): object{


        //definindo se o usuário logado é admin
        //isAdmin será utilizado no método temPermissaoPara() na entity Usuario
        $usuario->isAdmin = $this->isAdmin();

        //Se o usuário for admin, então não é cliente.
        if($usuario->isAdmin == true){

            $usuario->is_cliente = false;
        
        //Nesse ponto, verifiquei se o usuário logado é um cliente, visto que ele não é admin
        }else{

            $usuario->is_cliente = $this->isCliente();
        }


        /**
         * Sò recuperamos as permissões de um usário que não seja admin e não seja cliente
         * pois esses dois grupos não possuem permissões.
         * o atributo $usuario->permissoes será examinado na Entity Usuario para verificarmos se 
         * o mesmo pode ou não visualizar e acessar alguma rota.
         * Notem que se o usuário logado possui o atributo $usuario->permissoes, 
         * é pq ele não é admin e não é cliente
         */
        if($usuario->isAdmin == false && $usuario->isCliente == false){

            $usuario->permissoes = $this->recuperaPermissoesDoUsuarioLogado();

        }


        /**
         * Nesse ponto já definimos se é admin ou se é cliente
         * Caso não seja nem admin e nem cliente, então o objeto possui o atributi permissões,
         * que pode ou não estar vazio
         * Portanto, podemos retornar $usuario
         */
        return $usuario;

    }

    /**
     * Método que retorna as permissões Usuário Logado
     * 
     * $return $array
     */
    private function recuperaPermissoesDoUsuarioLogado(): array{

        $permissoesDoUsuario = $this->usuarioModel->recuperaPermissoesDoUsuarioLogado(session()->get('usuario_id'));

        return array_column($permissoesDoUsuario, 'permissao');
    }


    // ------------------------- métodos privados ---------------------- // 
    
    //
    private function isAdmin(): bool{

        $grupoAdmin = 1;
        $administrador = $this->grupoUsuarioModel->usuarioEstaNoGrupo($grupoAdmin, session()->get('usuario_id'));

        if($administrador == null){

            return false;
        }

        return true;
    }

    private function isCliente(): bool{

        $grupoCliente = 2;
        $cliente = $this->grupoUsuarioModel->usuarioEstaNoGrupo($grupoCliente, session()->get('usuario_id'));

        if($cliente == null){

            return false;
        }

        return true;
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

        //Definindo as permissãos do usuário logado

        $usuario = $this->definePermissoesDoUsuarioLogado($usuario);


        return $usuario;
        


    }

    



}