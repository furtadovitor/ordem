<div class="form-group">
    <label class="form-control-label">Nome</label>
    <input type="text" name="nome"  placeholder="Insira o nome completo" class="form-control" value="<?php echo esc($grupo->nome); ?>">
</div>


<div class="form-group">
    <label class="form-control-label">Descrição</label>
    <textarea name="descricao" placeholder="Insira a descrição" class="form-control"><?php echo esc($grupo->descricao);?></textarea>
</div>


<div class="form-group">
  <label class="form-control-label">Exibir grupo de acesso para edição/exclusão?</label>
  <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Dúvidas sobre situação" data-content="Esse grupo <?php echo ($grupo->exibir == true ? 'será' : 'não será'); ?> exibido como opção na hora de definir um: <b>responsável técnico</b> pela ordem de serviço"><i class="fa fa-solid fa-question text-danger"></i></a>

  <br>

<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="exibir" id="exibir" value="0">
  <label class="form-check-label" for="exibir">Não</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="exibir" id="exibir" value="1">
  <label class="form-check-label" for="exibir">Sim</label>
</div>

</div>