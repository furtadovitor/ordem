<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaGruposUsuarios extends Migration
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

            'grupo_id' => [ //chave estrangeria que vem da tabela de grupos

                'type'           => 'INT',
                'constraint'     => '5',
                'unsigned'       => true,
            ],

            'usuario_id' => [ 
                'type'           => 'INT',
                'constraint'     => '5',
                'unsigned'       => true,
            ],
        ]);


        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('grupo_id', 'grupos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        //1º parâmetro: chave que quero referenciar como estrangeira
        //1º parâmetro: tabela que vem a chave estrangeira 
        //1º parâmetro: coluna que vai ser a chave estrangeira 
        //1º parâmetro: Em caso de atualização: realizar um CASCADE 
        //1º parâmetro: Em caso de delete: realizar um CASCADE

        $this->forge->createTable('grupos_usuarios');
        
    }

    public function down()
    {
        $this->forge->dropTable('grupos_usuarios');
    }
}
