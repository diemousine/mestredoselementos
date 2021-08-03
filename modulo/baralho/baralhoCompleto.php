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
				<button type='button' class='col-sm-12 btn "); if(strripos($_SESSION['deck'], $carta)) echo "btn-success'"; else echo "btn-info'"; echo(" style='height: 200px'>
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
		    <form method='GET'>
		    	<div class='form-group'>
	    		<?php
	    		if($_SESSION['deck'] != '') {
					// - Realiza a conexão com o banco de dados.
					$conn = bdcon();

					$consulta = mysqli_query($conn, "SELECT * FROM cartas");
	    			$deck = $_SESSION['deck'];
	    			while($res = mysqli_fetch_assoc($consulta)) {
	    				$carta = ','.str_pad($res['num_atom'], 3, '0', STR_PAD_LEFT);
	    				if(strripos($deck, $carta)) {
	    					echo ("<button type='button' class='btn btn-default' style='width: 45px; height: 50px; text-align: center' title='".$res['nome']."'>".$res['simbolo']."</button>");
	    					$i++;

	    				}
	    			}
			    }
	    		?>
	    		</div>
		    	<center><button type='submit' id='deck-btn-novo' name='ordem' value='novoDeck' class='btn btn-warning'>Novo</button></center>
		    </form>
		  </div>
		</div>		
	</div>
</div>
<script language="javascript">document.getElementById("menu-baralho").setAttribute("class", "active");</script>