<div class='row'>
  <div class='col-sm-12'>
  	<div class='col-sm-2'></div>
  	<div class='col-sm-5 text-primary nome-jogo'>
  		<h1><strong>Mestre dos Elementos</strong></h1>
    </div>
    <div class='col-sm-5'>
    	<div class='col-sm-8' style='margin-top: 7%'>
    		<div class='col-sm-4'>
    			<?php
    			$nivel = $_SESSION['nivel'];
    			if($nivel<10) {
    				echo ("<label>Nível:</label> <p class='nivel0'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>9 && $nivel<20){
    				echo ("<label>Nível:</label> <p class='nivel1'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>19 && $nivel<30){
    				echo ("<label>Nível:</label> <p class='nivel2'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>29 && $nivel<40){
    				echo ("<label>Nível:</label> <p class='nivel3'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>39 && $nivel<50){
    				echo ("<label>Nível:</label> <p class='nivel4'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>49 && $nivel<60){
    				echo ("<label>Nível:</label> <p class='nivel5'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>59 && $nivel<70){
    				echo ("<label>Nível:</label> <p class='nivel6'>&nbsp;".$nivel."&nbsp;</p>");
    			} else if($nivel>69){
    				echo ("<label>Nível:</label> <p class='nivel7'>&nbsp;".$nivel."&nbsp;</p>");
    			}
    			?>
    		</div>
    		<div class='col-sm-8'>
    			<?php 
    			$xp = $_SESSION['xp'];
    			$nivel = $_SESSION['nivel'];
    			$xpnivel = ($xp/$nivel)*100;
    			echo("
	    			<div class='progress' title='Progresso para o próximo nível.'>
					  <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='".$xpnivel."' aria-valuemin='0' aria-valuemax='100' style='width: ".$xpnivel."%;'>
					    <p class='text-primary'>Exp.:&nbsp;".$xp."</p>
					  </div>
					</div>
				");?>
    		</div>
    	</div>
        <!-- Menu do usuário -->
        <?php include_once 'modulo/credencial/menu-us.php'; ?>
        <!-- -->
    </div>
  </div>
</div>

<!-- MENU -->
<div class='row'>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand">Menu</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li id='menu-portaria'><a href="<?php echo "http://".$_SERVER['HTTP_HOST']; ?>">PORTARIA<span class="sr-only">(atual)</span></a></li>
            <li id='menu-baralho'><a href="<?php echo "http://".$_SERVER['HTTP_HOST']."?ordem=baralho"; ?>">BARALHO</a></li>
            <li><a href="http://tabelaperiodicacompleta.com" target="_blank">ZONA DE CONHECIMENTO</a></li>
            <!--li id='menu-links'><a>LINKS</a></li-->
            <li id='menu-ajuda'><a>COMO JOGAR</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
</div>
<div id='conteudo-principal'>
