<div class="form-group">
    <label class="form-control-label">Nome Completo</label>
    <input type="text" name="nome"  placeholder="Insira o nome completo" class="form-control" value="<?php echo esc($usuario->nome); ?>">
</div>


<div class="form-group">
    <label class="form-control-label">Email</label>
    <input type="email" name="email" placeholder="Insira o seu e-mail" class="form-control" value="<?php echo esc($usuario->email);?>">
</div>

<div class="form-group">
    <label class="form-control-label">CPF</label>
    <input type="text" name="cpf" placeholder="Insira o seu CPF" class="form-control" value="<?php echo esc($usuario->cpf);?>">
</div>

<div class="form-group">
    <label class="form-control-label">Senha</label>
    <input type="password" name="password" placeholder="Senha de acesso" class="form-control">
</div>


<div class="form-group">
    <label class="form-control-label">Confirme sua senha</label>
    <input type="password" name="password_comfirmation" placeholder="COnfirme sua senha de acesso" class="form-control">
</div>

<div class="form-check form-switch">

    <input type="hidden" name="ativo" value="0">

  <input class="form-check-input" name="ativo" value="1" type="checkbox" id="ativo" <?php if($usuario->ativo == true): ?> checked <?php endif;?> >

  <label class="form-check-label" for="ativo">Usuário ativo</label>

</div>