/* Este arquivo foi criado para uso único e particular no site mestredoselementos
 * Author: Diego Mousine
 */

/* Funções para combate */

// - Variáveis de ambiente
var fim = 0;
var mudastatus = 0;

// - Atualiza status da partida
function statusDuelo(iduelo) {
	$.getJSON("/modulo/baralho/movimentos.php?", { ordem: "status", duelo: iduelo }, function(result) {
		if(result == 1) {
			alert("VOCÊ VENCEU!");
			refreshParent;
			window.close();
		} else if(result == 2) {
			alert("VOCÊ PERDEU");
			refreshParent;
			window.close();
		} else if(result == 3) {
			alert("Esta partida terminou EMPATADA");
			refreshParent;
			window.close();
		} else {
			// - Atualiza jogador da vez
			if(result['bandeira'] != '') {
				$("#bandeira1").hide();
				$("#bandeira2").hide();
				$("#bandeira"+result['bandeira']).show();
			}
			// - Atualiza os pontos dos jogadores
			$("#ptus").html(result['ptus']);
			$("#ptopon").html(result['ptopon']);
			// - Atualiza a quantidade de jogadas
			$("#jogadas").html(result['jogadas']);
			// - Atualiza a quantidade de cartas no deck
			$("#qtd-card").text(result['qtddeck']);
			// - Atualiza a carta do oponente
			if(result['cartaOpon'] != "") {
				$("#adv-car-1").text(result['cartaOpon']);			
				$("#adv-descarte").text(result['cartaOpon']);
			} else if(result['cartaOpon'] == 0) {
				$("#adv-car-1").text("?");
			}
			// - Carta selecionada
			if(result['cartaDaVez'] != "") {
				$("#carta").html(result['cartaDaVez']);
			} else {
				$("#carta").html("<div class='col-sm-8'><p><strong>?</strong></p><h1><strong>?</strong></h1><p>?????????<br />(???)</p></div><div class='col-sm-4'><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li></div>");
			}
			// - Propriedade da vez
			if(result['propDaVez'] != null) {
				for(var i = 1; i <= 6; i++) {
					$("#prop-"+i).hide();
					if($("#prop-"+i).prop("value") == result['propDaVez']) {
						$("#prop-"+i).show();
						$("#prop-"+i).attr("class", "row col-sm-12 btn btn-danger");
						$("#prop-"+i).attr("disabled", "disabled");
					}
				}
			} else if(result['propDaVez'] == null) {
				for(var i = 1; i <= 6; i++) {
					$("#prop-"+i).show();
					$("#prop-"+i).attr("class", "row col-sm-12 btn btn-default");
					$("#prop-"+i).removeAttr("disabled");
				}
			}
		}
		//alert(result['propDaVez']);
	});
}

// - Verifica o tempo da partida
function timer(iduelo) {
	$.get("/modulo/baralho/movimentos.php", { ordem: "timer", duelo: iduelo }, function(result) {
		if(result != '') {
			fim = result;
		}
	});	
}

// - Verifica a próxima carta do deck
function loadDeck(duelo, qtd) {
	$("#deckCombate").load("/modulo/baralho/movimentos.php?ordem=deck&duelo="+duelo);
	if(qtd == 0){
		$("#deckCombate").attr("disabled", "disabled");
	}
}

// - Destribui as cartas do deck na mesa
function loadMao(duelo) {
	if($("#qtd-card").text() > 0) {
		for(i = 1; i <= 5; i++) {
			if($('#mao-'+i).val() == '') {
				$('#div-mao-'+i).load("/modulo/baralho/movimentos.php?ordem=mao&n="+i+"&duelo="+duelo);
				$("#qtd-card").text($("#qtd-card").text()-1);
			}
		}
		loadDeck(duelo, $("#qtd-card").text());		
	}
}

// - Recupera as cartas da mesa no inicio da partida
function loadMesa(duelo) {
	if($("#qtd-card").text() < 15) {
		for(i = 1; i <= 5; i++) {
			if($('#mao-'+i).val() == '') {
				$('#div-mao-'+i).load("/modulo/baralho/movimentos.php?ordem=mesa&n="+i+"&duelo="+duelo);
			}
		}
	}
}

// - Seleciona uma carta
function selecione(id, card) {
	iduelo = $("#idduelo").val();
	$.get("/modulo/baralho/movimentos.php", { ordem: "carta", n: card, duelo: iduelo }, function(result) {
		if(result == 0) {
			alert('Não é a sua vez ainda.');
		} else {
			// - Desabilita a carta que foi selecionada
			$("#"+id).attr("disabled", "disabled");
			$("#cartaSel").html(result);
			$("#carta").removeAttr("disabled");
			$("#"+id).attr("disabled", "disabled");
			for(i = 1; i <= 5; i++) {
				if($("#mao-"+i).prop("disabled") == true) {
					$("#div-mao-"+i).html("<div class='col-sm-12 text-center'><button id='mao-"+i+"' class='col-sm-10 btn btn-info' style='height:170px' disabled><div class='col-sm-8'><p><strong>?</strong></p><h1><strong>?</strong></h1><p>?????????<br />(???)</p></div><div class='col-sm-4'><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li><li style='list-style-type: none'>?</li></div></button></div>");
				}
			}
			$("#descarte").html($("#carta").html());
		}
	});
}

// - Finaliza a jogada enviando a propriedade do combate
function selProp(id, propr) {
	iduelo = $("#idduelo").val();
	$.get("/modulo/baralho/movimentos.php", { ordem: "propriedade", prop: propr, duelo: iduelo }, function(result) {
		if(result === 0) {
			alert('Você não pode mais selecionar uma propriedade.'); 
		}
	});
}
$(document).on("ready", function() { 
	loadDeck($("#idduelo").val()); 
	loadMesa($("#idduelo").val()); 
	timer($("#idduelo").val()); 
});

$("#deckCombate").on("click", function() { loadMao($("#idduelo").val()); });

setInterval(function() { 
	statusDuelo($("#idduelo").val()); 
	$("#timer").html("<h5><span class='glyphicon glyphicon-time'></span> "+(fim--)+"</h4>");
}, 1000);

//ADDED START
window.onunload = refreshParent;
function refreshParent() {
    window.opener.location.reload();
}
//ADDED END