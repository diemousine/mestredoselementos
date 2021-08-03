<?php
include_once 'cabecalho.php';
include_once 'bdcon.php';

// Verifica se o cookie id existe e inicia a sessão.
if(isset($_COOKIE['id'])) {
	session_id(htmlspecialchars($_COOKIE['id']));
	session_start();

	// Verifica se o IP na sessão é igual ao IP dado na hora do login (para evitar cópia de cookies) e carrega o sistema.
	if(isset($_SESSION['host']) && $_SESSION['host'] == $_SERVER['REMOTE_ADDR']) {
		if(!($_SESSION['deck'] == '') && isset($_GET['versus'])) {

			$conn = bdcon();
			
			$id = $_SESSION['idus'];
			$deckdes = $_SESSION['deck'];
			$idopon = htmlspecialchars($_GET['versus']);
			$agora = time();

			// Verifica se existe um partida em andamento
			$consulta = mysqli_query($conn, "SELECT idduelo FROM duelo WHERE (iddesaf = $id OR idopon = $id) AND fim > $agora");
			
			// Se não, inicia uma nova partida
			if(mysqli_affected_rows($conn) == 0) {
				if(is_numeric($idopon)) {
					// Se o id do oponente é 1, cria uma nova partida versus a Inteligência Artificial
					if($idopon == 1) {

						include_once 'modulo/baralho/ia.php';

						$deckopon = iadeck();

						$idduelo = md5(time());
						$inicio = time();
						$fim = time()+600;
						$mudastatus = time()+30;
						mysqli_query($conn, "INSERT INTO duelo (idduelo, inicio, fim, iddesaf, deckdes, idopon, deckopon, status, mudastatus) VALUES ('$idduelo', $inicio, $fim, $id, '$deckdes', 1, '$deckopon', $id, $mudastatus)");
					}
				}
			} else {
				$resultado = mysqli_fetch_assoc($consulta);
				$idduelo = $resultado['idduelo'];
			}
			// - DADOS DA PARTIDA
			echo("
				<!-- Dados da partida -->
				<input type='text' id='idduelo' name='idduelo' value='".$idduelo."' hidden 
				");
			include_once 'modulo/baralho/combate.php';
		} else {
			echo("Você precisa criar um novo DECK para poder iniciar uma partida.");
		}
	} else {
		echo("Você está usando um cookie inválido. Clique <a href='./modulo/credencial/?ordem=logoff'><strong>aqui</strong></a> para limpar os cookies.");
	}
} else {
	echo 'Erro. Essa sessão expirou ou não está ativa.';
}
include_once 'rodape.php';
?>
<script src="js/combate.js"></script>