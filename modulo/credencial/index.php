<?php
/*
 * Esse arquivo contém todas as rotinas necessárias para:
 * - Cadastrar um novo usuário no banco de dados;
 * - Verificar as credenciais do usuário (login);
 * - Atualizar perfil do usuário;
 * - Recuperar senha do usuário;
 * - Remover as credenciais do usuário (logoff);
 */

// Inclui a página onde devem está as configurações de conexão ao banco de dados.
include_once '../../bdcon.php';

// Esta função retorna uma senha criptografada;
function gerasenha($senha) {
	return md5(sha1($senha.'?delinquente!'));
}


// Esta função retorna uma messagem em formato utf-8 legível;
function credencialErro($mensagem) {
	header('Content-Type: text/html; charset=utf-8');
	echo($mensagem);
}

/*
 * LOGIN DO USUÁRIO
 */
function login($email, $senha){

	// - Realiza a conexão com o banco de dados.
	$conn = bdcon();

	// - Reatribui as variáveis após remover possíveis caracteres que podem deixar o banco de dados vulnerável.
	$email = mysqli_real_escape_string($conn, $email);
	$senha = mysqli_real_escape_string($conn, $senha);

	/*
	 * Confere o email e a senha do usuário depois inicia uma sessão.
	 */

	$consulta = mysqli_query($conn, "SELECT idlogin FROM login WHERE email LIKE '$email' AND senha LIKE '$senha'");
	if(mysqli_num_rows($consulta) > 0) {
		$resultado = mysqli_fetch_assoc($consulta);
		$idlogin = $resultado['idlogin'];
		$consulta = mysqli_query($conn, "SELECT * FROM usuario WHERE idlogin = $idlogin");
		$resultado = mysqli_fetch_assoc($consulta);
		session_id(md5($idlogin."?dmx!".$email));
		session_start();
		$_SESSION['idus'] = $resultado['idusuario'];
		$_SESSION['email'] = $email;
		$_SESSION['nome'] = $resultado['nome'];
		$_SESSION['sbnome'] = $resultado['sobrenome'];
		$_SESSION['sexo'] = $resultado['sexo'];
		$_SESSION['dtnasc'] = substr($resultado['nascimento'], 0, 10);
		$_SESSION['serie'] = $resultado['serie'];
		$_SESSION['xp'] = $resultado['experiencia'];
		$_SESSION['nivel'] = $resultado['nivel'];
		$_SESSION['cartas'] = $resultado['cartas'];
		$_SESSION['deck'] = $resultado['deck'];
		$_SESSION['host'] = $_SERVER['REMOTE_ADDR'];
		setcookie('id', session_id(), 0, '/');
		$id = $resultado['idusuario'];
		mysqli_query($conn, "UPDATE usuario SET status='online' WHERE idusuario=$id");
		mysqli_close($conn);
		header('Location: http://'.$_SERVER['HTTP_HOST']);
	} else {
		credencialErro('Email ou senha inválido. Retorne e tente novamente.');
	}
	// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
	mysqli_close($conn);
}

/*
 * CADASTRO DE USUÁRIO
 */
function cadastrar($usuario, $email, $senha) {

	// - Realiza a conexão com o banco de dados.
	$conn = bdcon();

	// - Reatribui as variáveis após remover possíveis caracteres que podem deixar o banco de dados vulnerável.
	$usuario = mysqli_real_escape_string($conn, $usuario);
	$email = mysqli_real_escape_string($conn, $email);
	$senha = mysqli_real_escape_string($conn, $senha);

	// - Realiza o cadastro.
	$consulta = mysqli_query($conn, "SELECT idlogin FROM login WHERE email LIKE '$email'");
	if(mysqli_num_rows($consulta) > 0) { 
		credencialErro('Email já cadastrado no sistema. Retorne e use o link <strong>Esqueci a senha</strong>.');
	} else {
		$consulta = mysqli_query($conn, "INSERT INTO login (email, senha) VALUES ('$email', '$senha')");
		if(mysqli_affected_rows($conn) > 0) {
			$consulta = mysqli_query($conn, "SELECT idlogin FROM login WHERE email LIKE '$email'");
			if(mysqli_num_rows($consulta) > 0) {
				$resultado = mysqli_fetch_assoc($consulta);
				$idlogin = $resultado['idlogin'];
				$consulta = mysqli_query($conn, "INSERT INTO usuario (idlogin, nome) VALUES ('$idlogin', '$usuario')");
				if(mysqli_affected_rows($conn) > 0) {
					mysqli_close($conn);
					login($email, $senha);
				} else {
					credencialErro('Erro no registro do usuário: '.mysqli_error($conn));
				}
			} else {
				credencialErro('Erro ao verificar dados de login: '.mysqli_error($conn));
			}
		} else {
			credencialErro('Erro no registro dos dados de login: '.mysqli_error($conn));
		}
	}
	// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
	mysqli_close($conn);
}

/*
 * ATUALIZAR PERFIL
 */
function atualizarPerfil($sbnome, $sexo, $dtnasc, $serie) {

	// Verifica se o cookie id existe e inicia a sessão.
	if(isset($_COOKIE['id'])) {
		session_id(htmlspecialchars($_COOKIE['id']));
		session_start();

		// Verifica se o IP na sessão é igual ao IP dado na hora do login (para evitar cópia de cookies) e carrega o sistema.
		if(isset($_SESSION['host']) && $_SESSION['host'] == $_SERVER['REMOTE_ADDR']) {

			// - Realiza a conexão com o banco de dados.
			$conn = bdcon();

			// - Reatribui as variáveis após remover possíveis caracteres que podem deixar o banco de dados vulnerável.
			$sbnome = mysqli_real_escape_string($conn, $sbnome);
			$sexo = mysqli_real_escape_string($conn, $sexo);
			$dtnasc = mysqli_real_escape_string($conn, $dtnasc);
			$serie = mysqli_real_escape_string($conn, $serie);
			$idusuario = $_SESSION['idus'];

			// - Realiza a atualização dos dados.
			$consulta = mysqli_query($conn, "UPDATE usuario SET sobrenome = '$sbnome', sexo = '$sexo', nascimento = '$dtnasc', serie = '$serie' WHERE idusuario = $idusuario");
			if(mysqli_affected_rows($conn) > 0) {
				$_SESSION['sbnome'] = $sbnome;
				$_SESSION['sexo'] = $sexo;
				$_SESSION['dtnasc'] = $dtnasc;
				$_SESSION['serie'] = $serie;
			}
			// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
			mysqli_close($conn);
		}
	}
}

/*
 * LOGOFF DO USUÁRIO
 */
function logoff() {
			
	// Verifica se o cookie id existe e inicia a sessão.
	if(isset($_COOKIE['id'])) {
		session_id(htmlspecialchars($_COOKIE['id']));
		session_start();

		// Verifica se o IP na sessão é igual ao IP dado na hora do login (para evitar cópia de cookies) e carrega o sistema.
		if(isset($_SESSION['host']) && $_SESSION['host'] == $_SERVER['REMOTE_ADDR']) {
			$id = $_SESSION['idus'];
			session_unset();
			session_destroy();
			setcookie('id', '', time(), '/');
			// - Realiza a conexão com o banco de dados.
			$conn = bdcon();

			// - Identifica o usuário com offline no banco de dados.

			mysqli_query($conn, "UPDATE usuario SET status='offline' WHERE idusuario=$id");
		} else {
			setcookie('id', '', time(), '/');
		}
	}
	header('Location: http://'.$_SERVER['HTTP_HOST']);
}

/*
 * ROTINA PRINCIPAL
 */

// Mensagem passada por POST.
if(isset($_POST['ordem'])) {

	switch (htmlspecialchars($_POST['ordem'])) {
		case 'cadastrar':
			/* 
			 * Análise de segurança dos dados passados no formulário de cadastro:
			 * - Elimina caracteres especiais e atribui os valores às variáveis $usuario, $email e $senha.
			 */
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) { 
				$captcha = $_POST['g-recaptcha-response'];
				$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfGgA0UAAAAAPPjzs3h3x_TaPTjsSSI7MGzzN5N&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
				$response = json_decode($response, true);
				if($response['success']) {
					if(isset($_POST['usuario'], $_POST['email'], $_POST['senha'])) {
						if(($usuario = str_replace(" ", "", filter_var(htmlspecialchars($_POST['usuario']), FILTER_SANITIZE_STRING))) == "") die(credencialErro('Nome inválido. Retorne e tente novamente.'));
						if(filter_var($email = filter_var(htmlspecialchars($_POST['email']), FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)===false) die(credencialErro('Email inválido. Retorne e tente novamente.'));
						$senha = (htmlspecialchars($_POST['senha'])===htmlspecialchars($_POST['r-senha']) && htmlspecialchars($_POST['senha']) !== "") ? htmlspecialchars($_POST['senha']) : die(credencialErro('Senha inválida. Retorne e tente novamente.'));
						if(!isset($_POST['termo'])) die(credencialErro('Você precisa aceitar os termos para se cadastrar. Retorne e tente novamente.'));

						// - Recorta as strings com a quantidade caracteres exigida no banco de dados.
						$usuario = substr($usuario, 0, 15);
						$email = substr($email, 0, 100);
						$senha = gerasenha(substr($senha, 0, 16));

						cadastrar($usuario, $email, $senha);
					} else {
							credencialErro('Erro nos dados de cadastro informados.');
					} 
				} else {
					credencialErro('Erro no recaptcha. Retorne e tente novamente.');
				} 
			} else {
				credencialErro('Recaptcha não encontrado. Retorne e tente novamente');
			}
			break;
		case 'login':

			/* 
			 * Análise de segurança dos dados passados no formulário de login:
			 * - Elimina caracteres especiais e atribui os valores às variáveis $email e $senha.
			 */

			if(isset($_POST['email'], $_POST['senha'])) {
				$email = htmlspecialchars($_POST['email']);
				$senha = htmlspecialchars($_POST['senha']);

				$email = substr($email, 0, 100);
				$senha = gerasenha(substr($senha, 0, 16));

				login($email, $senha);
			} else {
				credencialErro('Erro nos dados de login informados. ');
			}
			break;

		case 'atualizar':

			/* 
			 * Análise de segurança dos dados passados no formulário do perfil:
			 * - Elimina caracteres especiais e atribui os valores às variáveis $sbnome, $dtnasc e $serie.
			 */

			if(($sbnome = filter_var(trim(htmlspecialchars($_POST['sbnome'])), FILTER_SANITIZE_STRING)) == "" || $sbnome == " ") die(credencialErro('Você deixou o campo Sobrenome vazio. Retorne e tente novamente.'));
			if(($sexo = htmlspecialchars($_POST['sexo'])) != 'Masculino' && $sexo != 'Feminino') die(credencialErro('Você não selecionou o sexo. Retorne e tente novamente.'));
			if(($dtnasc = preg_replace('([^0-9-])', "", $_POST['dtnasc'])) =="" || $dtnasc == " ") die(credencialErro('Data de nascimento inválida. Retorne e tente novamente.'));
			if(($serie = (is_numeric((htmlspecialchars($_POST['nvescolar'])))) ? filter_var(trim(htmlspecialchars($_POST['nvescolar'])), FILTER_SANITIZE_STRING).":".filter_var(trim(htmlspecialchars($_POST['especif'])), FILTER_SANITIZE_STRING) : ":") == ":") die(credencialErro('Você precisa selecionar seu nível escolar. Retorne e tente novamente.'));

			$sbnome = substr($sbnome, 0, 45);
			$dtnasc = substr($dtnasc, 0, 10);
			$serie = substr($serie, 0, 100);
			
			atualizarPerfil($sbnome, $sexo, $dtnasc, $serie);

			if(isset($_SESSION['cartas']) && empty($_SESSION['cartas'])) {
				include_once '../baralho/index.php';
				primeirasCartas($_SESSION['idus']);
			}
			header('Location: http://'.$_SERVER['HTTP_HOST']);
			break;
		default:
			credencialErro('Não foi possível encontrar a ordem especificada no formulário. Retorne e tente novamente.');
			break;
	}
}

// Mensagem passada por GET.
if(isset($_GET['ordem'])) {

	switch (htmlspecialchars($_GET['ordem'])) {
		case 'logoff':
			logoff();
			break;
		default:
			credencialErro('Não foi possível encontrar a ordem especificada no formulário. Retorne e tente novamente.');
			break;
	}
}
?>