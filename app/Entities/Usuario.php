<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Usuario extends Entity
{
    protected $dates   = ['criado_em', 'atualizado_em', 'deletado_em'];

    public function exibeSituacao(){

        if ($this->deletado_em != null){

            //usuário excluído

            $icone = '<span class="text-white">Excluído</span>&nbsp; <i class="fa fa-undo"></i>&nbspDesfazer';
            
            $btnDesfazer = anchor("usuarios/restaurarexclusao/$this->id", $icone, ['class' => 'btn btn-outline-succes btn-sm']);

            return $btnDesfazer;

        }

        if($this->ativo == true){

            return '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo';
            
        }

        if($this->ativo == false){

            return '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo';
            
        }

    }




}
