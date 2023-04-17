<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>

<link href="https://cdn.datatables.net/v/bs4/dt-1.13.4/r-2.4.1/datatables.min.css" rel="stylesheet" />



<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-12">
        <div class="block">
            <div class="table-responsive">
                <table id="ajax-table" style="width:100%" class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Situação</th>

                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>


</div>



<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>

<script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/r-2.4.1/datatables.min.js"></script>

<script>
    $(document).ready(function() {

        const DATATABLE_PTBR = {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }


        $('#ajax-table').DataTable({

            "oLanguage": DATATABLE_PTBR,
            ajax: "<?php echo site_url('Usuarios/recuperausuarios'); ?>",
            columns: [{
                    data: 'imagem'
                },
                {
                    data: 'id'
                },
                {
                    data: 'nome'
                },
                {
                    data: 'email'
                },
                {
                    data: 'cpf'
                },
                {
                    data: 'ativo'
                }
            ],
        });
    });
</script>


<?= $this->endSection(); ?>