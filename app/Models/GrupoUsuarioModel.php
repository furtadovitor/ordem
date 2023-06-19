<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupoUsuarioModel extends Model
{
    protected $table            = 'grupos_usuarios';
    protected $returnType       = 'object';
    protected $allowedFields    = ['grupo_id', 'usuario_id'];


    public function recuperaGruposDoUsuario(int $usuario_id, int $quantidade_paginacao){

        $atributos = [

            'grupos_usuarios.id AS principal_id',
            'grupos.id AS grupo_id',
            'grupos.nome',
            'grupos.descricao',


        ];

        return $this->select($atributos)
                    ->join('grupos', 'grupos.id = grupos_usuarios.grupo_id')
                    ->join('usuarios', 'usuarios.id = grupos_usuarios.usuario_id')
                    ->where('grupos_usuarios.usuario_id', $usuario_id)
                    ->groupBy('grupos.nome')
                    ->paginate($quantidade_paginacao);

    }

    /**
     * Método que recupera o Grupo no qual o usuário logado faz parte
     * usado apenas para definir se é cliente ou admin 
     */
    public function usuarioEstaNoGrupo(int $grupo_id, int $usuario_id){

        return $this->where('grupo_id', $grupo_id)
                    ->where('usuario_id', $usuario_id)
                    ->first();





    }



}
