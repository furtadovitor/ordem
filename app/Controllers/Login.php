<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Login extends BaseController
{
    public function novo()
    {

        $data = [
          
            'titulo' => 'Realize o login'
        ];

        return view('Login/novo', $data);
    }

    public function criar(){

        if (!$this->request->isAjax()) {
            return redirect()->back();
        }

        echo '<pre>';
        print_r($this->request->getPost());
        echo '<pre>';
        exit;
    }
}
