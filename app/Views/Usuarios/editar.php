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

                <!-- Exibirá os retornos do back-end -->
                <div id="response">

                </div>

                <?php echo form_open('/', ['id' => 'form'], ['id' => "$usuario->id"]) ?>

                <?php echo $this->include('Usuarios/_form'); ?>

                <div class="form-group mt-5 mb-2">

                    <input id="btn-salvar" type="submit" value="Salvar" class="btn btn-danger mr-2">

                    <a href="<?php echo site_url("usuarios/exibir/$usuario->id"); ?>" class="btn btn-secondary ml-2">Voltar</a>

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
    $(document).ready(function() {

        $("#form").on('submit', function(e) {

            e.preventDefault();

            $.ajax({

                type: 'POST', //pego os dados via POST
                url: '<?php echo site_url('usuarios/atualizar'); ?>', //redireciono para o método atualizar
                data: new FormData(this), //pego todos os dados do formulário
                dataType: 'json', //tipo de dado que terei como retorno
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $("#response").html('');
                    $("#btn-salvar").val('Por favor, aguarde...');


                },

                success: function(response) {

                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr('disabled');
                    $("[name=csrf_ordem]").val(response.token);


                    if (!response.erro) {
                        $("[name=csrf_ordem]").val(response.token);



                        if (response.info) {

                            $("#response").html('<div class="alert alert-info">' + response.info + '</div>');

                        } else {
                            //Tudo certo com a validação do usuário, podemso redicionar tranquilamente  

                            window.location.href = "<?php echo site_url("usuarios/exibir/$usuario->id"); ?>";

                        }

                    }

                    if (response.erro) {

                        $("#response").html('<div class="alert alert-danger">' + response.erro + '</div>');

                        //percorrendo erros de validação que virão do modelo 
                        if(response.erros_model){

                            $.each(response.erros_model, function(key, value){

                                $("#response").append('<ul class="list-unstyled"><li class="text-danger">'+ value +'</li></ul>')



                            });

                        }




                    }

                },

                error: function() {

                    alert('Não foi possível processar a solitação, favor entrar em contato com o suporte.');
                    $("#btn-salvar").val('Salvar');
                    $("#btn-salvar").removeAttr(disabled)
                }
            });


        });

        $("form").submit(function (){

            $(this).find(":submit").attr('disabled', 'disabled');

        });


    });
</script>



<?= $this->endSection(); ?>