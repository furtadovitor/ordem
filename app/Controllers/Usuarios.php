<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

        $usuarios = $this->usuarioModel->select($atributos)->findAll();

  
        //recebe o array com os objetos de usuários
        $data = [];
  
        foreach($usuarios as $usuario){

            $data[] = [
                'imagem' => $usuario->imagem,
                'id' => $usuario->id,
                'nome' => esc($usuario->nome),
                'email' => esc($usuario->email),
                'cpf' => $usuario->cpf,
                'ativo' => ($usuario->ativo == true ? 'Ativo' : '<span class="text-warning">Inativo</span>'),
            ];


        }

        $retorno = [

            'data' => $data,
        ];


        
        return $this->response->setJSON($retorno);

    }
}
