<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $returnType       = 'App\Entities\Usuario';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'email', 'cpf', 'password', 'reset_hash', 'reset_expira_em', 'imagem'];

    // Dates
    protected $useTimestamps = true; //significa que quer fazer uso dos campos criado_em, atualizado_em, deletado_em
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome'         => 'required|min_length[3]|max_length[120]',
         'email'        => 'required|valid_email|is_unique[usuarios.email,id,{id}]|max_length[230]',
         'password'     => 'required|min_length[6]',
         'password_comfirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo nome é obrigatório.',
        ],

        'email' => [
            'required' => 'O campo email é obrigatório.',
        ],

        'password' => [
            'required' => 'O campo senha é obrigatório',
            'min_length' => 'O campo senha deve conter no mínimo 6 caracteres.'
        ],
        

        'password_comfirmation' => [
            'required_with' => 'Por favor, confime sua senha.',
            'matches' => 'As senhas precisam combinar.'
        ],
    ];

    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['password_comfirmation']);

        }

        
        return $data;
    }

    public function buscaUsuarioPorEmail(string $email){

        return $this->where('email', $email)->where('deletado_em', null)->first();   

    }
}
