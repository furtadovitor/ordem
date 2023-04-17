<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;


class UsuarioFakerSeeder extends Seeder
{
    public function run()
    {

        //instanciando modelo de usuario

        $usuarioModel = new \App\Models\UsuarioModel();
        
        // instanciando a biblioteca do faker
        $faker = \Faker\Factory::create('pt_BR');        
        
        $criarQuantosUsuarios = 50;

        $usuarios_push = [];

        for($contador = 0; $contador < $criarQuantosUsuarios; $contador++){

            array_push($usuarios_push, [

                'nome' => $faker->unique()->name,
                'email' => $faker->unique()->email,
                'cpf' => $faker->unique()->cpf,
               // 'cpf' => $faker->unique()->cpf,
                'password_hash' => '123456', //alterar o fake seeder quando conhecermos como criptografar a senha
                'ativo' => 'true',

            ]);



        }

    // echo '<pre>';
    // print_r($usuarios_push);
    // echo '<pre>';
    // exit;

    $usuarioModel->skipValidation(true) //bypass na validação (pular as validações do model)
                 ->protect(false) //bypass na proteção dos campos AllowedFields
                 ->insertBatch($usuarios_push);   


                
    echo "$criarQuantosUsuarios criados com sucesso.";







    }
}
