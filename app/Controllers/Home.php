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

        $autenticacao = new Autenticacao();
        $autenticacao->logout();
        return redirect()->back()->to(site_url('/'));
        //dd($autenticacao->login('cr-cris@hotmail.com', '11212121'));

    }
}
