<?= $this->extend('Layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>


<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<div class="row">


<div class="col-md-12">


    <?php if($grupo->id < 3): ?>
    <div class="alert alert-info" role="alert">
  <h4 class="alert-heading">Importante!</h4>
  <p>O grupo <b><?php echo ($grupo->nome); ?></b> não pode ser alterado ou excluído, pois não pode ter suas permissões revogadas.</p>
  <hr>
  <p class="mb-0">Não se preocupe, os demais grupos estão habilitados para edição e/ou exclusão.</p>
</div>

    </div>

    <?php endif; ?>
    <div class="col-lg-4">

        <div class="user-block block">

    
            <h5 class="card-title mt-2">Nome: <?php echo esc($grupo->nome); ?> </h5>
            <p class="card-text">Descrição: <?php echo esc($grupo->descricao); ?> <p>
            <p class="card-text">Criado em: <?php echo esc($grupo->criado_em); ?> <p>
            <p class="card-text">Atualizado em: <?php echo esc($grupo->atualizado_em); ?> <p>
            <p class="contributions mt-0">Situação: <?php echo $grupo->exibeSituacao(); ?> 
            <?php if($grupo->deletado_em == null): ?>
            <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Dúvidas sobre situação" data-content="Esse grupo <?php echo ($grupo->exibir == true ? 'será' : 'não será'); ?> exibido como opção na hora de definir um: <b>responsável técnico</b> pela ordem de serviço"><i class="fa fa-solid fa-question text-danger"></i></a>
            <?php endif; ?>
</p>

           
            <br>

            <?php if($grupo->id > 2): ?>

            <div class="btn-group mr-2">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo site_url("grupos/editar/$grupo->id"); ?>">Editar grupo de acesso</a>
                    <div class="dropdown-divider"></div>

                    <?php if($grupo->deletado_em == null): ?> 

                        <a class="dropdown-item" href="<?php echo site_url("grupos/excluir/$grupo->id"); ?>">Excluir grupo de acesso</a>

                    <?php else: ?>

                        <a class="dropdown-item" href="<?php echo site_url("grupos/restaurarexclusao/$grupo->id"); ?>">Recuperar grupo de acesso</a>

                    <?php endif; ?>
                </div>
            </div>

            <?php endif; ?>

            <a href="<?php echo site_url('grupos'); ?>" class="btn btn-secondary ">Voltar</a>


        </div> <!-- Fim do block -->


    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>




<?= $this->endSection(); ?>