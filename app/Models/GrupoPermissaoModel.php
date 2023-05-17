<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupoPermissaoModel extends Model
{
    protected $table            = 'grupos_permissoes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['grupo_id', 'permissao_id'];


    //Método que recupera as permissões dos grupos de acesso
    
    public function recuperaPermissoesDoGrupo(int $grupo_id, int $quantidade_paginacao){

        $atributos = [

            'grupos_permissoes.id',
            'grupos.id AS grupo_id',
            'permissoes.id AS permissao_id',
            'permissoes.nome'

        ];

        return $this->select($atributos)
                    ->join('grupos', 'grupos.id = grupos_permissoes.grupo_id')
                    ->join('permissoes', 'permissoes.id = grupos_permissoes.permissao_id')
                    ->where('grupos_permissoes.grupo_id', $grupo_id)
                    ->groupBy('permissoes.nome')
                    ->paginate($quantidade_paginacao);




    }
   

}
