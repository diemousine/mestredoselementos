
<form class='form-horizontal' action='/modulo/credencial/' method='post'>
  <div class='form-group'>
    <label for='usuario' class='col-sm-3 control-label'> Primeiro nome: </label>
    <div class='col-sm-6'>
      <input class='form-control' type='text' title='Primeiro nome' placeholder='<?php echo $_SESSION['nome'] ?>' readonly />
    </div>
  </div>
  <div class='form-group'>
    <label class='col-sm-3 control-label'> Email: </label>
    <div class='col-sm-6'>
      <input class='form-control' type='email' title='Email' placeholder='<?php echo $_SESSION['email'] ?>' readonly />
    </div>
  </div>
  <div class='form-group'>
    <label for='sbnome' class='col-sm-3 control-label'> Sobrenome: </label>
    <div class='col-sm-6'>
      <input class='form-control' type='text' id='sbnome' name='sbnome' title='Sobrenome' placeholder='Sobrenome' <?php if($_SESSION['sbnome']!='') echo("Value='".$_SESSION['sbnome']."'") ?> maxlength=45 required />
    </div>
  </div>
  <?php
  $opcoes = "
  <div class='form-group'>
    <label for='sexo' class='col-sm-3 control-label'> Sexo: </label>
    <div class='col-sm-6'>
      <select class='form-control' id='sexo' name='sexo' required>
        <option value=''>SELECIONE</option>
        <option value='Masculino'>Masculino</option>
        <option value='Feminino'>Feminino</option>
      </select>
    </div>
  </div>
  ";
  $sexo = $_SESSION['sexo'];
  if($sexo!='') {
    $search = "value='".$sexo."'";
    $replace = "value='".$sexo."' selected";
    $opcoes = str_replace($search, $replace, $opcoes);
  }
  echo($opcoes);
  ?>
  <div class='form-group'>
    <label for='dtnasc' class='col-sm-3 control-label'> Data de nascimento: </label>
    <div class='col-sm-6'>
      <input class='form-control' type='date' id='dtnasc' name='dtnasc' title='Data de nascimento' placeholder='00-00-0000' <?php if($_SESSION['dtnasc']!='0000-00-00') echo("Value='".$_SESSION['dtnasc']."'") ?> maxlength=10 required />
    </div>
  </div>
  <?php
  $opcoes = "
  <div class='form-group'>
    <label for='nvescolar' class='col-sm-3 control-label'> N??vel escolar atual: </label>
    <div class='col-sm-6'>
      <select class='form-control' id='nvescolar' name='nvescolar' required>
        <option value=''>SELECIONE</option>
        <OPTGROUP label='FORMA????O GERAL'>
          <option value='1'>Primeiro ano do Ensino M??dio</option>
          <option value='2'>Segundo ano do Ensino M??dio</option>
          <option value='3'>Terceiro ano do Ensino M??dio</option>
        </OPTGROUP>
        <OPTGROUP label='FORMA????O T??CNICA'>
          <option value='4'>Primeiro ano do Ensino T??cnico</option>
          <option value='5'>Segundo ano do Ensino T??cnico</option>
          <option value='6'>Terceiro ano do Ensino T??cnico</option>
          <option value='7'>Quarto ano do Ensino T??cnico</option>
        </OPTGROUP>
        <OPTGROUP label='FORMA????O SUPERIOR'>
          <option value='8'>Tecnol??gico | Licenciatura | Bacharelado</option>
          <option value='9'>Especializa????o</option>
          <option value='10'>Mestrado</option>
          <option value='11'>P??s-gradua????o</option>
          <option value='12'>Doutorado</option>
          <option value='13'>P??s-doutorado</option>
        </OPTGROUP>
      </select>
    </div>
  </div>
  ";
  $serie = $_SESSION['serie'];
  if($serie!='') {
    $serie = explode(':', $serie);
    $search = "value='".$serie[0]."'";
    $replace = "value='".$serie[0]."' selected";
    $opcoes = str_replace($search, $replace, $opcoes);
  }
  echo($opcoes);
  if(isset($serie[1]) && $serie[1] != '') {
    echo ("
    <div id='especify' class='form-group'>
      <label for='especif' class='col-sm-3 control-label'> Especifique a forma????o: </label>
      <div class='col-sm-6'>
        <input class='form-control' type='text' id='especif' name='especif' title='Especifique a forma????o' placeholder='Digite aqui...' value='".$serie[1]."' maxlength=90/>
      </div>
    </div>
    ");
  } else {
    echo ("
    <div id='especify' class='form-group' hidden='hidden'>
      <label for='especif' class='col-sm-3 control-label'> Especifique a forma????o: </label>
      <div class='col-sm-6'>
        <input class='form-control' type='text' id='especif' name='especif' title='Especifique a forma????o' placeholder='Digite aqui...' maxlength=90/>
      </div>
    </div>
    ");
  }
  ?>
  <input class='form-control' type='hidden' name='ordem' value='atualizar' />
  <div class='form-group'>
    <div class='col-sm-12 text-center'>
      <button type='submit' id='perf-btn-submit' class='btn btn-primary' title='Salvar'>Salvar</button>
      <a class='btn btn-default' title='Cancelar' href='<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>'>Cancelar</a>
    </div>
  </div>
</form>
