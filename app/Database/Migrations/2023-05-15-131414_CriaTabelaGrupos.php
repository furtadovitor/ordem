<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaGrupos extends Migration
{
    public function up()
    {
        
        $this->forge->addField([

            'id' => [

                'type'           => 'INT',
                'constraint'     => '5',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'nome' => [

                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],

            'descricao' => [

                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],

            
            'exibir' => [ //Se o grupo estiver com esse campo como TRUE, então ele será exibido como opção
                          //na hora de definir um responsável técnico pela Ordem de Serviço 

                'type'           => 'BOOLEAN',
                'null'           => false,
            ],

            'criado_em' => [

                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],

            'atualizado_em' => [

                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],

            'deletado_em' => [

                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],

            

        ]);


        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nome');
        
        $this->forge->createTable('grupos');
        
    }

    public function down()
    {
        $this->forge->dropTable('grupos');
    }
}
