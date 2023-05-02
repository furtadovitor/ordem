<?= $this->extend('Layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>


<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-4">

        <div class="block">

            
            <div class="text-center">

                <?php if ($usuario->imagem == null) : ?>

                <img src="<?php echo site_url('recursos/img/usuario_sem_imagem.png'); ?> " class="card-img-top"
                    style="width: 90%" alt="Usuário sem imagem">



                <?php else : ?>

                <img src="<?php echo site_url("usuarios/imagem/$usuario->imagem"); ?> " class="card-img-top"
                    style="width: 90%" alt="Usuário <?php echo esc($usuario->nome); ?>">

                <?php endif;  ?>


                <a href="<?php echo site_url("usuarios/editarimagem/$usuario->id"); ?>"
                    class="btn btn-outline-info btn-sm mt-3">Alterar imagem</a>

            </div>

            <hr class="border-secondary">

            <h5 class="card-title mt-2">Nome: <?php echo esc($usuario->nome); ?> </h5>
            <p class="card-text">CPF: <?php echo esc($usuario->cpf); ?> <p>
            <p class="card-text">Email: <?php echo esc($usuario->email); ?> </p>
            <p class="card-text">Criado em: <?php echo esc($usuario->criado_em->humanize()); ?> </p>
            <p class="card-text">Atualizado em: <?php echo ($usuario->atualizado_em->humanize()); ?> </p>
            <p class="card-text">Situação: <?php echo ($usuario->ativo == true ? 'Usuário ativo' : 'Usuário inativo'); ?> </p>

           
            <br>

            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo site_url("usuarios/editar/$usuario->id"); ?>">Editar usuário</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url("usuarios/excluir/$usuario->id"); ?>">Excluir usuário</a>
                </div>
            </div>

            <a href="<?php echo site_url('usuarios'); ?>" class="btn btn-secondary ml-2">Voltar</a>


        </div> <!-- Fim do block -->


    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>


<?= $this->endSection(); ?>