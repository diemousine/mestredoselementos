<div class='col-sm-4' style='margin-top: 5%'>
	<div class='dropdown'>
	  <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenuUsuario' data-toggle='dropdown' aria-expanded='true'>
	    <?php echo $_SESSION['nome']; ?>
	    <span class='caret'></span>
	  </button>
	  <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuUsuario'>
	    <li role='presentation'><a role='menuitem' tabindex='-1' href='<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/?ordem=perfil'>Perfil</a></li>
	    <li role="presentation" class="divider"></li>
	    <li role='presentation'><a role='menuitem' tabindex='-1' href='<?php echo "http://".$_SERVER['HTTP_HOST']; ?>/modulo/credencial/?ordem=logoff'>Sair</a></li>
	  </ul>
	</div>
</div>