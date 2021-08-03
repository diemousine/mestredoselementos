<?php
include_once 'cabecalho.php';

// Verifica se o cookie id existe e inicia a sessão.
if(isset($_COOKIE['id'])) {
	session_id(htmlspecialchars($_COOKIE['id']));
	session_start();

	// Verifica se o IP na sessão é igual ao IP dado na hora do login (para evitar cópia de cookies) e carrega o sistema.
	if(isset($_SESSION['host']) && $_SESSION['host'] == $_SERVER['REMOTE_ADDR']) {
		include_once 'topo.php';

		// Verifica se o cadastro está completo e redireciona o usuário para a página de perfil caso não esteja completo.
		if($_SESSION['cartas'] == '') {
			include_once 'modulo/credencial/incompleto.php';
			include_once 'modulo/credencial/perfil.php';
		} else if(isset($_SESSION['novo-us'])){
			include_once 'modulo/baralho/novoBaralho.php';
			include_once 'modulo/baralho/baralhoCompleto.php';
			unset($_SESSION['novo-us']);
		} else if(isset($_GET['ordem'])) {
			switch (htmlspecialchars($_GET['ordem'])) {
				case 'perfil':
					include_once 'modulo/credencial/perfil.php';
					break;
				case 'baralho':
					include_once 'modulo/baralho/baralhoCompleto.php';
					break;
				case 'novoDeck':
					include_once 'modulo/baralho/novoDeck.php';
					break;
				default:
					# code...
					break;
			}
		} else {
			include_once 'portaria.php';
		}
		// Fecha a div "Conteudo-principal" em topo.php
		echo "</div>";
	} else {
		echo("Você está usando um cookie inválido. Clique <a href='./modulo/credencial/?ordem=logoff'><strong>aqui</strong></a> para limpar os cookies.");
	}
} else {
	
	include_once 'credencial.php';
}
include_once 'rodape.php';
?>