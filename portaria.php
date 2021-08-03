<?php
// Inclui a página onde devem está as configurações de conexão ao banco de dados.
include_once 'bdcon.php';

$conn = bdcon();

$nivel = $_SESSION['nivel'];
$id = $_SESSION['idus'];
$xp = $_SESSION['xp'];
$agora = time();

// Atualiza o status de duelos do jogador
mysqli_query($conn, "UPDATE duelo SET idperdedor = $id, status = NULL WHERE (iddesaf = $id OR idopon = $id) AND fim < $agora AND status = $id AND idperdedor IS NULL");
mysqli_query($conn, "UPDATE duelo SET idvencedor = $id WHERE (iddesaf = $id OR idopon = $id) AND fim < $agora AND status != $id AND idvencedor IS NULL");


// Consulta os 10 melhores do nível atual do jogador e colocar em uma array da sessão.
$_SESSION['rk1'] = 
	$consulta = mysqli_query($conn,
		"SELECT idusuario, nome, nivel, experiencia
		FROM usuario
		WHERE nivel = $nivel
		ORDER BY experiencia DESC, idusuario ASC
		LIMIT 10"
	);

// Consulta a posição do jogador em relação a todos do nível dele
$_SESSION['rk2'] = mysqli_fetch_assoc(
	$consulta = mysqli_query($conn,
		"SELECT FIND_IN_SET($id, (
			SELECT GROUP_CONCAT(idusuario
			ORDER BY experiencia DESC, idusuario ASC)
			FROM usuario
			WHERE nivel = $nivel))
		AS rank"
	)
);

// Consulta os 10 melhores do jogo e colocar em uma array da sessão
$_SESSION['rk3'] =
	$consulta = mysqli_query($conn,
		"SELECT idusuario, nome, nivel, experiencia
		FROM usuario
		ORDER BY nivel DESC, experiencia DESC, idusuario ASC
		LIMIT 10"
	);

// Consulta a posição do jogador em relação a todos.
$_SESSION['rk4'] = mysqli_fetch_assoc(
	$consulta = mysqli_query($conn,
		"SELECT FIND_IN_SET($id, (
			SELECT GROUP_CONCAT(idusuario
			ORDER BY nivel DESC, experiencia DESC, idusuario ASC)
			FROM usuario))
		AS rank"
	)
);

// - Finaliza a conexão com o banco de dados para liberar memória do servidor.
mysqli_close($conn);
?>
<div class='row'>
	<div class='col-sm-4'>
		<div class='panel panel-info'>
			<!-- Default panel contents -->
			<div class='panel-heading'>RANKING DO NÍVEL <?php echo $_SESSION['nivel']; ?></div>
			<!-- Table -->
			<table class='table table-striped table-hover table-condensed'>
			  	<thead>
				  <tr>
				    <th>#</th>
				    <th>ID</th>
				    <th>NOME</th>
				    <th>XP</th>
				  </tr>
				</thead>
				<?php
				if($_SESSION['rk2']['rank']>10) {
					echo ("
			  	<tfoot>
				  <tr>
				  	<tr class='warning'>
				  		<td><strong>".$_SESSION['rk2']['rank']."º</strong></td>
				  		<td><strong>".$_SESSION['idus']."</strong></td>
				  		<td><strong>VOCÊ</strong></td>
				  		<td><strong>".$_SESSION['xp']."</strong></td></tr>
				  </tr>
				</tfoot>");
				}
				?>
				<tbody>
				  	<?php
				  	$i = 1;
				  	while($row = mysqli_fetch_assoc($_SESSION['rk1'])) {
			  			echo "
		  			<tr>
		  				<td>".$i."º</td>
		  				<td>".$row['idusuario']."</td>
		  				<td>".$row['nome']."</td>
		  				<td>".$row['experiencia']."</td>
		  			</tr>";
		  				$i++;
				  	}
				  	?>
				</tbody>
			</table>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='panel panel-danger'>
		  <!-- Default panel contents -->
		  <div class="panel-heading">RANKING GERAL</div>
			<!-- Table -->
			<table class='table table-striped table-hover table-condensed'>
			  	<thead>
				  <tr>
				    <th>#</th>
				    <th>ID</th>
				    <th>NOME</th>
				    <th>NÍVEL</th>
				    <th>XP</th>
				  </tr>
				</thead>
				<?php
				if($_SESSION['rk4']['rank']>10) {
					echo ("
			  	<tfoot>
				  <tr>
				  	<tr class='warning'>
				  		<td><strong>".$_SESSION['rk4']['rank']."º</strong></td>
				  		<td><strong>".$_SESSION['idus']."</strong></td>
				  		<td><strong>VOCÊ</strong></td>
				  		<td><strong>".$_SESSION['nivel']."</strong></td>
				  		<td><strong>".$_SESSION['xp']."</strong></td></tr>
				  </tr>
				</tfoot>");
				}
				?>
				<tbody>
				  	<?php
				  	$i = 1;
				  	while($row = mysqli_fetch_assoc($_SESSION['rk3'])) {
			  			echo "
		  			<tr>
		  				<td>".$i."º</td>
		  				<td>".$row['idusuario']."</td>
		  				<td>".$row['nome']."</td>
		  				<td>".$row['nivel']."</td>
		  				<td>".$row['experiencia']."</td>
		  			</tr>";
		  			$i++;
				  	}
				  	?>
				</tbody>
			</table>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <div class="panel-heading">CHAT EM GRUPO</div>
		  <div class="panel-body">
		    <p>Em desenvolvimento.</p>
		  </div>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<?php
		$id = $_SESSION['idus'];
		$conn = bdcon();
		$consulta = mysqli_query($conn, "SELECT * FROM duelo WHERE (iddesaf = $id OR idopon = $id) AND fim > $agora");
		if(mysqli_affected_rows($conn) > 0) {
			$resultado = mysqli_fetch_assoc($consulta);
			$_SESSION['idduelo'] = $resultado['idduelo'];
			$idoponente = ($id == $resultado['iddesaf']) ? $resultado['idopon'] : $resultado['iddesaf'];
			echo "<a class='btn btn-warning' href='tabuleiro.php?versus=".$idoponente."' target='tabuleiro' title='Continuar partida anterior'>CONTINUAR PARTIDA";
		} else {
			echo "<a class='btn btn-primary' href='tabuleiro.php?versus=1' target='tabuleiro' title='Nova partida'>JOGAR VS Mestre";
		}
		mysqli_close($conn);
		?>
			<span class='glyphicon glyphicon-new-window' aria-hidden='true'></span></a>
	</div>
</div>

<?php unset($_SESSION['rk1'], $_SESSION['rk2'], $_SESSION['rk3'], $_SESSION['rk4']); ?>
<script language="javascript">document.getElementById("menu-portaria").setAttribute("class", "active");</script>