<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'email', 'cpf', 'password', 'reset_hash', 'reset_expira_em', 'imagem' ]; 

    // Dates
    protected $useTimestamps = true; //significa que quer fazer uso dos campos criado_em, atualizado_em, deletado_em
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    // Callbacks
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];
}
