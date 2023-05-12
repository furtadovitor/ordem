<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Entities\Usuario;

class Usuarios extends BaseController
{

    private $usuarioModel;

    public function __construct()
    {

        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function index()
    {


        $data = [

            'titulo' => 'Listando os usuários do sistema.'
        ];

        return view('Usuarios/index', $data);
    }

    public function recuperaUsuarios()
    {

        if (!$this->request->isAjax()) {

            return redirect()->back();
        }


        $atributos = [

            'id',
            'nome',
            'email',
            'cpf',
            'ativo',
            'imagem',
            'deletado_em',


        ];

        $usuarios = $this->usuarioModel->select($atributos)->withDeleted(true)->orderBy('ID', 'desc')->findAll();


        //recebe o array com os objetos de usuários
        $data = [];

        foreach ($usuarios as $usuario) {


            //Definindo o caminho da imagem do usuário
            if($usuario->imagem != null){

                $imagem = [
                    'src' => site_url("usuarios/imagem/$usuario->imagem"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => esc($usuario->nome),
                    'width' => '50',
                ];


            }else{


                $imagem = [
                    'src' => site_url("recursos/img/usuario_sem_imagem.png"),
                    'class' => 'rounded-circle img-fluid',
                    'alt' => "Usuário sem imagem",
                    'width' => '50',
                ];



            }

            $nomeUsuario = esc($usuario->nome);

            $data[] = [
                'imagem' => $usuario->imagem = img($imagem),
                'id' => $usuario->id,
                'nome' => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title = "Exibir usuário ' . $nomeUsuario . '" '),
                'email' => esc($usuario->email),
                'cpf' => $usuario->cpf,
                'ativo' => $usuario->exibeSituacao(),            
            
            ];
        }

        $retorno = [

            'data' => $data,
        ];



        return $this->response->setJSON($retorno);
    }


    public function criar(int $id = null)
    {

        $usuario = new Usuario();

        $data =  [

            'titulo' => 'Criando um novo usuário ',
            'usuario' => $usuario,

        ];


        return view('Usuarios/criar', $data);
    }

    public function cadastrar()
    {


        if (!$this->request->isAjax()) {

            return redirect()->back();
        }

        //envio do hash do token do form
        $retorno['token'] = csrf_hash();

        // $retorno['erro'] = "Essa é uma mensagem de erro de validação. ";
        // $retorno['erros_model'] = [
        //     'nome' => 'O nome é obrigatório.',
        //     'email' => 'E-mail inválido',        

        // ];


        //recupero o post da requisição
        $post = $this->request->getPost();

        //criando um objeto da classe entidade usuário
        $usuario = new Usuario($post);

        //desabilitei a proteção por conta do "ativo".
        //Só pode ser desabilidade a proteção pois esse formulário específico só é manipulado por ADMIN.
        if ($this->usuarioModel->protect(false)->save($usuario)) {

            $btnCriar = anchor("usuarios/criar", 'Cadastrar novo usuário', ['class' => 'btn btn-danger mt-2']);

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', "Dados salvos com sucesso! <br> $btnCriar");

            //retornamos o último ID insetiro na tabela de usuários
            $retorno['id'] = $this->usuarioModel->getInsertID();

            return $this->response->setJSON($retorno);
        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();



        //retorno para o ajax request
        return $this->response->setJSON($retorno);
    }

    public function exibir(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $data =  [

            'titulo' => 'Detalhando o usuário ' . esc($usuario->nome) . '',
            'usuario' => $usuario,

        ];


        return view('Usuarios/exibir', $data);
    }

    public function editar(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        $data =  [

            'titulo' => 'Editando o usuário ' . esc($usuario->nome) . '',
            'usuario' => $usuario,

        ];


        return view('Usuarios/editar', $data);
    }

    public function editarimagem(int $id = null)
    {

        
        $usuario = $this->buscaUsuarioOu404($id);

        $data =  [

            'titulo' => 'Alterando a imagem do usuário ' . esc($usuario->nome) . '',
            'usuario' => $usuario,

        ];


        return view('Usuarios/editar_imagem', $data);
    }

    public function upload()
    {


        if (!$this->request->isAjax()) {

            return redirect()->back();
        }

        //envio do hash do token do form
        $retorno['token'] = csrf_hash();

        $validacao = service('validation');

        //aplicando as regras de validação para arquivos

        $regras = [

            'imagem' => 'uploaded[imagem]|max_size[imagem, 1024]|ext_in[imagem,png,jpg,jpeg,webp]',

        ];

        $mensagens = [

            'imagem' => [

                'uploaded' => 'Por favor, escolha uma imagem.',
                'max_size' => 'Por favor, escolha uma imagem de no máximo 1024',
                'ext_in' => 'Por favor escolha uma imagem com as seguintes extensões: imagem, png, jpg, jpeg, webp.',
            ],
        ];


        $validacao->setRules($regras, $mensagens);

        if($validacao->withRequest($this->request)->run() == false){

            $retorno['erro'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = $validacao->getErrors();

            return $this->response->setJSON($retorno);

        }



        //recupero o post da requisição
        $post = $this->request->getPost();



        //validamos a existência do usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);

        //recuperando a imagem do usuario que veio no post 
        $imagem = $this->request->getFile('imagem');


        //Método list é para pegar a largura e a altura do get através do getimagesize
        list($largura, $altura) = getimagesize($imagem->getPathname());


        //Validação para tamanho mínimo de altura e largura
        if($largura < "300" || $altura < "300"){

            $retorno['erro'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $retorno['erros_model'] = ['dimensao' => 'A imagem não pode ser menor do que 300 x 300 pixels'];

            return $this->response->setJSON($retorno);

        }

        //cria a pasta da imagem
        $imagemCaminho = $imagem->store('usuarios');

        //mostra o caminho para salvar a imagem
        $imagemCaminho = WRITEPATH . 'uploads/' . $imagemCaminho;

 

        //Recuperando a possível imagem antiga
        $imagemAntiga = $usuario->imagem;
    

        $this->manipulacaoImagem($imagemCaminho, $usuario->id);

        //A partir daqui, estamos atualizando a tabela de usuários
        $usuario->imagem = $imagem->getName();
        
        $this->usuarioModel->save($usuario);

        if($imagemAntiga != null){

            $this->removeImagemDoFileSystem($imagemAntiga);
        }



        session()->setFlashdata('sucesso', 'Imagem atualizada com sucesso');


        //retorno para o ajax request
        return $this->response->setJSON($retorno);
    }

    public function imagem(string $imagem = null){

        if($imagem != null){

            $this->exibeArquivo('usuarios', $imagem);
        }


    }

    public function atualizar()
    {


        if (!$this->request->isAjax()) {

            return redirect()->back();
        }

        //envio do hash do token do form
        $retorno['token'] = csrf_hash();

        // $retorno['erro'] = "Essa é uma mensagem de erro de validação. ";
        // $retorno['erros_model'] = [
        //     'nome' => 'O nome é obrigatório.',
        //     'email' => 'E-mail inválido',        

        // ];


        //recupero o post da requisição
        $post = $this->request->getPost();



        //validamos a existência do usuário
        $usuario = $this->buscaUsuarioOu404($post['id']);


        // Se não for irformado a senha, removemos do $post.
        // Pois, se não fizermos dessa forma, o hashPassword fará o hash de uma string vazia
        if (empty($post['password'])) {
            unset($post['password']);
            unset($post['password_comfirmation']);
        }


        //Preenchendo os atributos do usuário com os valores do post
        $usuario->fill($post);

        if ($usuario->hasChanged() == false) {

            $retorno['info'] = 'Não há dados para serem atualizados';

            return $this->response->setJSON($retorno);
        }

        //desabilitei a proteção por conta do "ativo".
        //Só pode ser desabilidade a proteção pois esse formulário específico só é manipulado por ADMIN.
        if ($this->usuarioModel->protect(false)->save($usuario)) {

            //Vamos conhecer mensagem de flashdata
            session()->setFlashdata('sucesso', 'Dados salvos com sucesso');

            return $this->response->setJSON($retorno);
        }

        //retornando os erros de validação 
        $retorno['erro'] = 'Por favor, verifique os erros de validação e tente novamente';
        $retorno['erros_model'] = $this->usuarioModel->errors();



        //retorno para o ajax request
        return $this->response->setJSON($retorno);
    }

    public function excluir(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);


        if($usuario->deletado_em != null){

        return redirect()->back()->with('info', " Usuário já encontra-se excluído.");

        }


        if($this->request->getMethod() === 'post'){


            //Excluindo o usuário
            $this->usuarioModel->delete($usuario->id);

            if($usuario->imagem != null){

                //Deletando a imagem do fileSystem
                
                $this->removeImagemDoFileSystem($usuario->imagem);
            }

            //Excluindo a imagem e tornando o usuário inativo.
            $usuario->imagem = null;
            $usuario->ativo = false;

            $this->usuarioModel->protect(false)->save($usuario);

            //retornando para a view usuarios
            return redirect()->to(site_url('usuarios'))->with('sucesso', "Usuário $usuario->nome excluído com sucesso");


        }

        //mostrando a foto do usuário nulo 
        $usuario->imagem = null;

        $data =  [

            'titulo' => 'Excluindo o usuário ' . esc($usuario->nome) . '',
            'usuario' => $usuario,

        ];


        return view('Usuarios/excluir', $data);
    }

    public function restaurarExclusao(int $id = null)
    {

        $usuario = $this->buscaUsuarioOu404($id);

        if($usuario->deletado_em == null){

            return redirect()->back()->with('info', " Apenas usuários excluídos podem ser recuperados.");

        }

        $usuario->deletado_em = null;
        $this->usuarioModel->protect(false)->save($usuario);

        return redirect()->back()->with('success', " Usuário $usuario->nome recuperado com sucesso.");


    }


    // // //Horário para manipulação de folha de ponto 
    //  public function horario($dias = 20){


    //      for ($i=1; $i <= $dias ; $i++) { 


    //     $hora_ini_aleatorio = rand(7,8);

    //     if($hora_ini_aleatorio == 7){
    //      $minuto_ini_aleatorio = rand(48,59);
    //     } else{
    //      $minuto_ini_aleatorio = rand(00,14);
    //     }

    //     $hora_ini_almoco_aleatorio = rand(11,12);

    //     if($hora_ini_almoco_aleatorio == 7){
    //      $min_ini_almoco_aleatorio = rand(30,59);
    //     } else{
    //      $min_ini_almoco_aleatorio = rand(00,59);
    //     }     


    //     $minuto_fim = rand(1, 7);
    //     $ini =  mktime($hora_ini_aleatorio, $minuto_ini_aleatorio, 0, 7, 1, 2000);

    //     $ini_almoco  = mktime($hora_ini_almoco_aleatorio, $min_ini_almoco_aleatorio, 0, 7, 1, 2000);

    //     $fim_almoco  = mktime($hora_ini_almoco_aleatorio+1, $min_ini_almoco_aleatorio + rand(1,6), 0, 7, 1, 2000);

    //     $fim =  mktime($hora_ini_aleatorio + 9, $minuto_ini_aleatorio + $minuto_fim, 0, 7, 1, 2000);

    //     echo $i .'---' . date('H:i:s', $ini).'---------------'.date('H:i:s', $ini_almoco) . '-----------'.date('H:i:s', $fim_almoco) . '-----------'. date('H:i:s', $fim).'<br>';

    //  }
    //  }



    //método que recupera o usuário no banco de dados
    private function buscaUsuarioOu404(int $id = null)
    {

        if (!$id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)) {

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuário não encontrado $id");
        }

        return $usuario;
    }

    private function removeImagemDoFileSystem(string $imagem){

        $imagemCaminho = WRITEPATH . 'uploads/usuarios/' . $imagem;

        if(is_file($imagemCaminho)){

            unlink($imagemCaminho);
        }

    }

    private function manipulacaoImagem(string $imagemCaminho, int $usuario_id){

        //Manipulando a imagem que já está salva no diretório
        service('image')
        ->withFile($imagemCaminho)
        ->fit(300, 300, 'center')
        ->save($imagemCaminho);

           $anoAtual = date('Y');
   
           //Adicionando marca d'água 
           \Config\Services::image('imagick')
           ->withFile($imagemCaminho)
           ->text("Ordem $anoAtual - User-ID: $usuario_id" , [
           'color'      => '#000000',
           'opacity'    => 0.5,
           'withShadow' => false,
           'hAlign'     => 'center',
           'vAlign'     => 'bottom',
           'fontSize'   => 10,
       ])
       ->save($imagemCaminho);
   
        
    }
}
