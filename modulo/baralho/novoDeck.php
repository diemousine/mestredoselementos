<div class='row'>
	<div class='col-sm-10'>
<?php

// Inclui a página onde devem está as configurações de conexão ao banco de dados.
include_once 'bdcon.php';

// - Realiza a conexão com o banco de dados.
$conn = bdcon();

$consulta = mysqli_query($conn, "SELECT * FROM cartas");
$cartas = $_SESSION['cartas'];
$qtdCart = explode(',', $cartas);
$i = 0;
$j = ceil($qtdCart[0]/6);
while($res = mysqli_fetch_assoc($consulta)) {
	$carta = ','.str_pad($res['num_atom'], 3, '0', STR_PAD_LEFT);
	//if($i%6 == 0 && $j > 0) echo "<div class='row'><div class='col-sm-12'>";
	if(strripos($cartas, $carta)) {
		echo ("
			<div class='col-sm-2' style='height: 220px'>
				<button class='col-sm-12 btn btn-success' id='carta_".$res['idcartas']."' name='".$res['simbolo']."' value='".$res['idcartas']."' style='height: 200px' onclick='decker(this.id, this.name, this.value)'>
					<div class='col-sm-8'>
						<p><strong>".$res['num_atom']."</strong></p>
						<h1><strong>".$res['simbolo']."</strong></h1>
						<p>".$res['nome']."<br />".$res['mas_atom']."</p>
					</div>
					<div class='col-sm-4'>
			");
		$eletrons = explode(',', $res['eletrons']);
		foreach ($eletrons as $e) {
			echo ("<li style='list-style-type: none'>".$e."</li>");
		}
		echo ("
					</div>
				</button>
			</div>
			");
		$i++;
	}
	//if($i%6 == 0  && $j > 0) { echo "</div></div>"; $j--; }
}
mysqli_close($conn);
?>
	</div>
	<div class='col-sm-2'>
		<div class="panel panel-info">
		  <div class="panel-heading">
		    <h3 class="panel-title">DECK</h3>
		  </div>
		  <div class="panel-body">
		    <form action='/modulo/baralho/' method='POST'>
			    <div class='form-group' id='deck'>
			    	<?php
			    	 for($i = 0; $i < 15; $i++) {
			    		echo("<button type='button' class='btn btn-info' id='".$i."' onclick='undecker(this.id)' style='width: 45px; height: 50px; text-align: center; display: none'></button>");
			    	}
			    	?>
		    	</div>
				<input name='ordem' value='salvar-deck' hidden />
				<center><button type='submit' id='deck-btn-submit' class='btn btn-primary' disabled>Salvar</button></center>
		    </form>
		  </div>
		</div>		
	</div>
</div>
<script language="javascript">document.getElementById("menu-baralho").setAttribute("class", "active");</script>