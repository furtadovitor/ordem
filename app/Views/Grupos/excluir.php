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
        <?php echo dd($grupo); ?>

            <div class="block-body">

                <?php echo form_open("grupos/excluir/$grupo->id") ?>

                <div class="alert alert-warning" role="alert">
                    Tem certeza da exclusão do Grupo <?php echo esc($grupo->nome); ?>?
                </div>


                <div class="form-group mt-5 mb-2">

                    <input id="btn-salvar" type="submit" value="Confirmar exclusão" class="btn btn-danger mr-2">

                    <a href="<?php echo site_url("grupos/exibir/$grupo->id"); ?>"
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