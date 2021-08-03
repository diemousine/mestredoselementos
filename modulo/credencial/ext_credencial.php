<?php
/*
 * Esse arquivo contém todas as rotinas necessárias para:
 * - Aumentar experiência do usuário;
 */

// Esta função retorna uma senha criptografada;
function atualizaXP() {
	$conn = bdcon();

    $id = $_SESSION['idus'];
	mysqli_query($conn, "UPDATE usuario SET experiencia = (experiencia +1) WHERE idusuario = $id");
	$cons = mysqli_query($conn, "SELECT * FROM usuario WHERE idusuario = $id");
	$res = mysqli_fetch_assoc($cons);
	if($res['experiencia'] >= $_SESSION['nivel']) {
		mysqli_query($conn, "UPDATE usuario SET experiencia = 0, nivel = (nivel +1) WHERE idusuario = $id");
	}
	$cons = mysqli_query($conn, "SELECT * FROM usuario WHERE idusuario = $id");
	$res = mysqli_fetch_assoc($cons);
	$_SESSION['xp'] = $res['experiencia'];
	$_SESSION['nivel'] = $res['nivel'];

	mysqli_close($conn);
}

?>