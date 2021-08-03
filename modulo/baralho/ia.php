<?php
/* Inteligência artificial do jogo
 * Author: Diego Mousine
 */

// Gera deck do IA
function iadeck() {
	$i = 0;
	$cartas = '';
	while ($i < 15) {
		$carta = ','.str_pad(rand(1,118), 3, '0', STR_PAD_LEFT);
		if(strripos($cartas, $carta) === FALSE){
			$cartas .= $carta;
			$i++;
		}
	}
	return $i.$cartas;
}

// SELECIONA UMA CARTA NA ORDEM DO DECK
function iaSelCard($deck, $idduelo) {
	$conn = bdcon();
	
	$deck = explode(',', $deck);
	if(isset($deck[0]) && $deck[0] != 0) {
		$idcartas = $deck[1];
		mysqli_query($conn, "UPDATE duelo SET cartaopon = $idcartas WHERE idduelo = '$idduelo'");
		
		$deck[0]--;
		unset($deck[1]);
		$deck = implode(',', $deck);

		mysqli_query($conn, "UPDATE duelo SET deckopon = '$deck' WHERE idduelo = '$idduelo'");
	}
	mysqli_close($conn);
}

// SELECIONA UMA PROPRIEDADE ALEATÓRIA
function iaSelProp($idduelo) {
	$conn = bdcon();
	
	$mudastatus = time()+30;
	$propriedades = array("raio", "ener-ionizacao", "afinidade", "negatividade", "positividade", "pote-ionizacao");
	$prop = $propriedades[rand(0,5)];
	mysqli_query($conn, "UPDATE duelo SET propriedade = '$prop' WHERE idduelo = '$idduelo'");
	mysqli_close($conn);
}
?>