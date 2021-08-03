<?php
/* Propriedades periódicas das cartas
 * Author: Diego Mousine
 */

function modelo1($resultado) {

	$conn = bdcon();

	$cartaDesaf = $resultado['cartadesaf'];
	$cartaOpon = $resultado['cartaopon'];
	$cD = $cO = array();

	$consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $cartaDesaf OR idcartas = $cartaOpon");

	while ($res = mysqli_fetch_assoc($consulta)) {
		if($cartaDesaf == $res['idcartas']) $cD = $res;
		else $cO = $res;
	}

	if($cD['periodo'] > $cO['periodo']) {
		return 1;
	} else if($cD['periodo'] < $cO['periodo']) {
		return 2;
	} else {
		if($cD['familia'] < $cO['familia']) {
			return 1;
		} else if($cD['familia'] > $cO['familia']) {
			return 2;
		}
	}
	mysqli_close($conn);
}

function modelo2($resultado) {

	$conn = bdcon();

	$cartaDesaf = $resultado['cartadesaf'];
	$cartaOpon = $resultado['cartaopon'];
	$cD = $cO = array();
	
	$consulta = mysqli_query($conn, "SELECT * FROM cartas WHERE idcartas = $cartaDesaf OR idcartas = $cartaOpon");

	while ($res = mysqli_fetch_assoc($consulta)) {
		if($cartaDesaf == $res['idcartas']) $cD = $res;
		else $cO = $res;
	}

	if($cD['periodo'] < $cO['periodo']) {
		return 1;
	} else if($cD['periodo'] > $cO['periodo']) {
		return 2;
	} else {
		if($cD['familia'] > $cO['familia']) {
			return 1;
		} else if($cD['familia'] < $cO['familia']) {
			return 2;
		}
	}
	mysqli_close($conn);
}

function compare($idduelo) {

	$conn = bdcon();

	$consulta = mysqli_query($conn, "SELECT * FROM duelo WHERE idduelo = '$idduelo'");
	$resultado = mysqli_fetch_array($consulta);

	switch ($resultado['propriedade']) {
		case 'raio':
			return modelo1($resultado);
			break;
		
		case 'ener-ionizacao':
			return modelo2($resultado);
			break;

		case 'afinidade':
			return modelo2($resultado);
			break;

		case 'negatividade':
			return modelo2($resultado);
			break;

		case 'positividade':
			return modelo1($resultado);
			break;

		case 'pote-ionizacao':
			return modelo2($resultado);
			break;

		default:
			# code...
			break;
	}
	mysqli_close($conn);
}

?>