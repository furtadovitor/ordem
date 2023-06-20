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

    public function verificaPassword(string $password): bool{

        return password_verify($password, $this->password_hash);
    }

    /**
     * Método que valida se o usuário logado possui a permissão para visualizar/acessar determinada rota
     */
    public function temPermissaoPara(string $permissao): bool{

        //Se o usuário logado é admin, retornamos true
        if($this->is_admin == true){
            return true;
        }


        /**
         * Se o usuário logado($this) possui o atributo 'permissoes' vazio (empty),
         * então retornamos false também, pois a $permiossao não estará no array $permissoes.
         * Isso acontece quando o usuário logado ($this) faz parte de um grpo que não possui permissões
         * Ou não está em nenhum grupo de acesso
         * Regra não é válida para clientes, pois na classe Autenticacao defini se o usuário logado é cliente ou adm.
         */
        
        if(empty($this->permissoes)){

            return false;
        }

        /** 
         * Nesse ponto, o usuário logado possui permissões,
         * então pode ser verificado tranquilamente
         */

        if(in_array($permissao, $this->permissoes)== false) {

            return false; 


        }

        //retornando true, pois a permissão é válida

        return true;


         
    }




}
