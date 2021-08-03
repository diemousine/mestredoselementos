<!-- topo -->
<div class='row'>
  <div class='col-sm-12'>
  	<div class='col-sm-2'></div>
  	<div class='col-sm-5 text-primary nome-jogo'>
  		<h1><strong>Mestre dos Elementos</strong></h1>
    </div>
    <div class='col-sm-5'>
    	<div class='col-sm-12' style='margin-top: 7%'>
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
    </div>
  </div>
</div>
<!-- FIM TOPO -->
<!-- ZONA DE BATALHA -->
<div class='row'>
    <div class='col-sm-12' style='background: #000'>
        <br />
<!-- ÁREA DO ADVERSÁRIO -->
        <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
                <div class='col-sm-12 text-center'>
                    <button class='col-sm-10 btn btn-warning' title='Carta do oponente' style='height:170px'><h1><strong><span id='adv-car-1'>?</span></strong></h1></button>
                </div>
            </div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
                <div class='col-sm-12 text-center'>
                    <button class='col-sm-10 btn btn-warning' title='Descarte do oponente' style='height:170px' disabled><h1><strong><span id='adv-descarte'>?</span></strong></h1></button>
                </div>
            </div>
        </div>
<!-- FIM ÁREA DO ADVERSÁRIO -->
<!-- ÁREA DE CONTROLE -->
        <div class='row'>
            <div class='col-sm-2'>
                <h3>Seus descartes</h3>
            </div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2 text-center'>
                <div class='col-sm-10'>
                    <h3><span id='bandeira2' class='label label-danger glyphicon glyphicon-chevron-up'> </span></h3>
                </div>
            </div>
            <div class='col-sm-2 text-center'>
                <div class='col-sm-10'>
                    <h3><span id='bandeira1' class='label label-danger glyphicon glyphicon-chevron-down'> </span></h3>
                </div>
            </div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
        </div>
<!-- FIM ÁREA DE CONTROLE -->
<!-- ÁREA DO JOGADOR -->
        <div class='row'>
<!-- DESCARTES -->
            <div class='col-sm-2'>
                <div class='col-sm-12 text-center'>
                    <button id='descarte' class='col-sm-10 btn btn-success' title='Descartes' style='height:170px' disabled>
                        <div class='col-sm-8'>
                            <p><strong>?</strong></p>
                            <h1><strong>?</strong></h1>
                            <p>?????????<br />(???)</p>
                        </div>
                        <div class='col-sm-4'>
                            <li style='list-style-type: none'>?</li>
                        </div>
                    </button>
                </div>
            </div>
<!-- FIM DESCARTES -->
            <div class='col-sm-2'></div>
<!-- PROPRIEDADES -->
            <div class='col-sm-2'>
                <button id='prop-1' value='raio' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Raio</button>
                <button id='prop-2' value='ener-ionizacao' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Energ. Ionização</button>
                <button id='prop-3' value='afinidade' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Afinidade</button>
                <button id='prop-4' value='negatividade' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Negatividade</button>
                <button id='prop-5' value='positividade' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Positividade</button>
                <button id='prop-6' value='pote-ionizacao' type='button' class='row col-sm-12 btn btn-default' onClick='selProp(this.id, this.value)' style='height:28px; display: none'>Poten. Ionização</button>
            </div>
<!-- FIM PROPRIEDADES -->
<!-- CARTA SELECIONADA -->
            <div id='cartaSel' class='col-sm-2 text-center'>
                <div class='col-sm-12 text-center'>
                    <button id='carta' class='col-sm-10 btn btn-default' title='Sua carta' style='height:170px'>
                        <div class='col-sm-8'>
                            <p><strong>?</strong></p>
                            <h1><strong>?</strong></h1>
                            <p>?????????<br />(???)</p>
                        </div>
                        <div class='col-sm-4'>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                        </div>
                    </button>
                </div>
            </div>
<!-- FIM CARTA SELECIONADA -->
            <div class='col-sm-2'></div>
<!-- PLACAR -->
            <div class='col-sm-2 text-center'>
                <div class='row'><div id='timer' class='col-sm-12 label label-info'><h5><span class='glyphicon glyphicon-time'></span> CARREGANDO</h5></div></div>
                <div class='row'><div class='col-sm-12 label label-default'><h5>Jogadas: <span id='jogadas'>0/0</span></h5></div></div>
                <div class='row'>
                    <div class='col-sm-5 label label-success'><h3 id='ptus'>0</h3></div>
                    <div class='col-sm-2'></div>
                    <div class='col-sm-5 label label-warning'><h3 id='ptopon'>0</h3></div>
                </div>
            </div>
<!-- FIM PLACAR -->
        </div>
        <br />
        <div class='row'>
            <div class='col-sm-2 text-center'>
                <h3>DECK <span id='qtd-card' class='label label-success'>0</span></h3>
            </div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'></div>
        </div>
        <div class='row'>
<!-- DECK -->
            <div class='col-sm-2'>
                <div class='col-sm-12'>
                    <button id='deckCombate' class='col-sm-10 btn btn-success' title='Clicque para servir as cartas' style='height:170px'>
                        <div class='col-sm-8'>
                            <p><strong>?</strong></p>
                            <h1><strong>?</strong></h1>
                            <p>?????????<br />(???)</p>
                        </div>
                        <div class='col-sm-4'>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                            <li style='list-style-type: none'>?</li>
                        </div>
                    </button>
                </div>
            </div>
<!-- FIM DECK -->
<!-- MÃO -->
            <?php
            for($i = 1; $i <= 5; $i++) {
                echo("
                <div id='div-mao-".$i."' class='col-sm-2'>
                    <div class='col-sm-12 text-center'>
                        <button id='mao-".$i."' class='col-sm-10 btn btn-info' style='height:170px' disabled>
                            <div class='col-sm-8'>
                                <p><strong>?</strong></p>
                                <h1><strong>?</strong></h1>
                                <p>?????????<br />(???)</p>
                            </div>
                            <div class='col-sm-4'>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                                <li style='list-style-type: none'>?</li>
                            </div>
                        </button>
                    </div>
                </div>
                ");
            }
            ?>
<!-- FIM MÃO -->
<!-- FIM ÁREA DO JOGADOR -->
        </div>
        <br />
    </div>
</div>
