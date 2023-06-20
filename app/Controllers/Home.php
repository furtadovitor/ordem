<?php

namespace App\Controllers;

use App\Libraries\Autenticacao;

class Home extends BaseController
{
    public function index()
    {
        $data = [

            'titulo' => 'Home',


        ];
        return view('Home/index', $data);
    }

    public function login(){

        $autenticacao = service('autenticacao');    
        
        $autenticacao->login('cr-cris@hotmail.com', '123456');

        $usuario = $autenticacao->pegaUsuarioLogado();

        dd($usuario->temPermissaoPara('excluir-usuarios'));
    }
}
