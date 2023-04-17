<?= $this->extend('layout/principal'); ?>

<?= $this->section('titulo'); ?>

<?php echo $titulo; ?>

<?= $this->endSection(); ?>



<?= $this->section('estilos'); ?>


<?= $this->endSection(); ?>


<?= $this->section('conteudo'); ?>

<h1>Extendendo o layout principal através da view index de home</h1>


<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>


<?= $this->endSection(); ?>






