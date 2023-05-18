<?= $this->extend('Layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>


<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<div class="row">


<div class="col-md-12">

    <div class="col-lg-6">


    </div>


    <div class="col-lg-6">

        <div class="user-block block">

            <?php if(empty($grupo->permissoes)): ?>

                <p class="contributions text-warning mt-0">Esse grupo ainda não possui permissões de acesso.</p> 

            <?php else: ?>

                <div class="table-responsive">
                 
                        <table class="table table-bordered table-sm">
                           
                            <thead>
                                <tr>
                                <th>Permissão</th>
                                <th>Excluir</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach($grupo->permissoes as $permissao): ?>
                                <tr>
                                <td><?php echo esc($permissao->nome); ?></td>
                                <td> <a href="#" class="btn btn-sn btn-danger">Excluir</a></td>
                                </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="mt-3 ml-1">
                        <?php echo $grupo->pager->links(); ?>
                        </div>
                </div>

                

            
           
            <a href="<?php echo site_url('grupos'); ?>" class="btn btn-secondary ">Voltar</a>


        </div> <!-- Fim do block -->

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>




<?= $this->endSection(); ?>