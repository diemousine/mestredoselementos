<?php
/*
 * Esse arquivo contém todas as rotinas necessárias para:
 * - Atribui as 49 primeiras cartas do jogar;
 */

// Inclui a página onde devem está as configurações de conexão ao banco de dados.
include_once 'bdcon.php';

// Esta função retorna uma messagem em formato utf-8 legível;
function baralhoErro($mensagem) {
	header('Content-Type: text/html; charset=utf-8');
	echo($mensagem);
}

// Esta função sortei as 49 primeiras cartas do novo usuário;
function primeirasCartas($id) {

	// - Realiza a conexão com o banco de dados.
	$conn = bdcon();

	// - Gera 49 cartas aleatórias entre 1 e 118.
	$i = 0;
	$cartas = '';
	while ($i < 49) {
		$carta = ','.str_pad(rand(1,118), 3, '0', STR_PAD_LEFT);
		if(strripos($cartas, $carta) === FALSE){
			$cartas .= $carta;
			$i++;
		}
	}
	$cartas = $i.$cartas;

	// - Reatribui as variáveis após remover possíveis caracteres que podem deixar o banco de dados vulnerável.
	$cartas = mysqli_real_escape_string($conn, $cartas);

	$consulta = mysqli_query($conn, "UPDATE usuario SET cartas = '$cartas' WHERE idusuario = $id");
	if(mysqli_affected_rows($conn) > 0) {
		$_SESSION['cartas'] = $cartas;
		$_SESSION['novo-us'] = true;
	}

	// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
	mysqli_close($conn);
}

// Esta função registra os valores do deck;
function registrarDeck($deck) {

	// Verifica se o cookie id existe e inicia a sessão.
	if(isset($_COOKIE['id'])) {
		session_id(htmlspecialchars($_COOKIE['id']));
		session_start();

		// Verifica se o IP na sessão é igual ao IP dado na hora do login (para evitar cópia de cookies) e carrega o sistema.
		if(isset($_SESSION['host']) && $_SESSION['host'] == $_SERVER['REMOTE_ADDR']) {

			// - Realiza a conexão com o banco de dados.
			$conn = bdcon();

			// - Reatribui as variáveis após remover possíveis caracteres que podem deixar o banco de dados vulnerável.
			$deck = mysqli_real_escape_string($conn, $deck);
			$id = mysqli_real_escape_string($conn, $_SESSION['idus']);

			$consulta = mysqli_query($conn, "UPDATE usuario SET deck = '$deck' WHERE idusuario = $id");
			if(mysqli_affected_rows($conn) > 0) {
				$_SESSION['deck'] = $deck;
				echo 'ok';
			}

			// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
			mysqli_close($conn);
		}
	} else {
		echo("Você está usando um cookie inválido. Clique <a href='./modulo/credencial/?ordem=logoff'><strong>aqui</strong></a> para limpar os cookies.");
	}
}

/*
 * ROTINA PRINCIPAL
 */

// Mensagem passada por POST.
if(isset($_POST['ordem'])) {
	switch (htmlspecialchars($_POST['ordem'])) {
		case 'salvar-deck':
			$i = 0;
			$deck = '';
			while($i < 15) {
				if(isset($_POST['sel_'.$i]) && is_numeric(htmlspecialchars($_POST['sel_'.$i]))) {
					$carta = ','.str_pad(htmlspecialchars($_POST['sel_'.$i]), 3, 0, STR_PAD_LEFT);
					if(strripos($deck, $carta) === false) {
						$deck .= $carta;
						$i++;
					} else {
						die(baralhoErro('Erro inesperado. O sistema recebeu valores repetidos. Retorne e tente novamente.'));
					}
				} else {
					die(baralhoErro('Erro inesperado. O sistema recebeu valores indevidos. Retorne e tente novamente.'));
				}
			}
			$deck = $i.$deck;
			registrarDeck($deck);
			header('Location: http://'.$_SERVER['HTTP_HOST'].'?ordem=baralho');
			break;
		
		default:
			# code...
			break;
	}
}
?>