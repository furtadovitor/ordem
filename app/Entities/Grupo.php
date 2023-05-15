<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Grupo extends Entity
{
    protected $datamap = [];
    protected $dates   = ['criado_em', 'atualizado_em', 'deletado_em'];
    protected $casts   = [];

    public function exibeSituacao(){

        if ($this->deletado_em != null){

            //grupo excluído

            $icone = '<span class="text-white">Excluído</span>&nbsp; <i class="fa fa-undo"></i>&nbspDesfazer';
            
            $btnDesfazer = anchor("grupos/restaurarexclusao/$this->id", $icone, ['class' => 'btn btn-outline-succes btn-sm']);

            return $btnDesfazer;

        }

        if($this->exibir == true){

            return '<i class="fa fa-unlock text-success"></i>&nbsp;Exibir grupo';
            
        }

        if($this->exibir == false){

            return '<i class="fa fa-lock text-warning"></i>&nbsp;Não exibir grupo';
            
        }

    }


}


