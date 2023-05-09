<?= $this->extend('Layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>


<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-6">

        <div class="block">

            <div class="block-body">

                <?php echo form_open("usuarios/excluir/$usuario->id") ?>

                <div class="alert alert-warning" role="alert">
                    Tem certeza da exclusão do Usuário <?php echo esc($usuario->nome); ?>?
                </div>


                <div class="form-group mt-5 mb-2">

                    <input id="btn-salvar" type="submit" value="Confirmar exclusão" class="btn btn-danger mr-2">

                    <a href="<?php echo site_url("usuarios/exibir/$usuario->id"); ?>"
                        class="btn btn-secondary ml-2">Cancelar</a>

                </div>
                <?php echo form_close() ?>


            </div>

        </div> <!-- Fim do block -->


    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>

<!-- enviando os dados via AJAX REQUEST para o Controllador Usuário -->

<script>

</script>



<?= $this->endSection(); ?>