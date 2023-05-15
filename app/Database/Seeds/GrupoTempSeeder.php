<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GrupoTempSeeder extends Seeder
{
    public function run()
    {
        
        $grupoModel = new \App\Models\GrupoModel();

        $grupos = [

            [   'nome' => 'Administrador',
                'descricao' => 'Grupo com acesso total ao sistema',
                'exibir' => false,
            ],

            [

                'nome' => 'Clientes',
                'descricao' => 'Grupo destinado para atribuição de cliente, pois os mesmo poderão logar no sistem para acessar as suas OS ',
                'exibir' => false,
            ],

            [

                'nome' => 'Atendentes',
                'descricao' => 'Grupo destinado para os funcionários que irão manusear o sitema',
                'exibir' => false,
            ],

        ];

        foreach($grupos as $grupo){


            $grupoModel->insert($grupo);
        }

        

        echo "Grupos criados com sucesso";
    }
}
