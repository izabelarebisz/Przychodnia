<?php
	session_start();
	ob_start();
	
	if(isset($_SESSION['niezalogowano'])){
		header('Location: index.php');
		exit();
	}
	
	echo "<p>Witaj ".$_SESSION['uzytkownik'].'![<a href="logout.php">Wyloguj się</a>]</p>';
	echo "<p>Pesel ".$_SESSION['pesel']."!";
	
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Przychodnia</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
	<style type-"text/css">
		body{
			margin: 0;
			padding: 0;
			font-family: sans-serif;
			background-image: url(gradient.jpg);
			background-size: cover;
		}
		#datepicker{
			width: 200px;
			margin: 20px 20px 20px 0px;
		}
		#datepicker > span:hover{
			cursor: pointer;
		}
		.row{
			display: flex;
			align-items: stretch;
			flex-wrap: wrap;
			margin-left: 20px;
			
		}
		.main{
			margin-left: 40px;
		}
		.radioBox{
			margin-bottom: 30px;
			margin-top: 30px;
		}
	


	</style>
	
</head>

<body>	
	
	<?php
	
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
		
		$data = $_SESSION['data'];
		$selected = $_SESSION['selected'];
		
		// informacje o lekarzu
		
		echo "<div class='main'>";
		$specjalista = $polaczenie->query("SELECT * FROM lekarze WHERE id_lekarza='$selected' ");
		while($rows = $specjalista->fetch_assoc()){
			echo "<h2>".$rows['imie']." ".$rows['nazwisko']."</h2>";
			echo "<h3>".$rows['specjalnosc']."</h3>";
		}
		echo "<h2>Dzień '$data'</h2></div>";

		
		$id_pacjenta = $_SESSION['id_pacjenta'];
		
		// $data - data wizyty $selected - id lekarza $id_pacjenta - id pacjenta
		
		
		// wolne godziny wybranego lekarza w wybrany dzień
		
		$wszystkie_godziny = array("9:00","9:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30");			
		$wizyty_lekarza = $polaczenie->query("SELECT * FROM wizyty WHERE id_lekarza='$selected' AND data='$data'");	// wszystkie wizyty tego lekarza w dany dzień			
		$zajete = array();
		while($rows = $wizyty_lekarza->fetch_assoc()) {
			array_push($zajete, $rows['godzina']);
			//echo "</br>test:".$zajeta."</br>";
		}

		
		$dostepne_godziny = array();
		for($i=0;$i<sizeof($wszystkie_godziny);$i++){
			if(czyWolna($zajete,$wszystkie_godziny[$i])==1) array_push($dostepne_godziny, $wszystkie_godziny[$i]); // 
			
		}
		
		
		/*
		echo "<div class='main'><form action='' method='POST'>";
		for($i=0;$i<sizeof($dostepne_godziny);$i++){
			echo "<input type='radio' name='godz' value=". $dostepne_godziny[$i] ."/>&nbsp;&nbsp;" . $dostepne_godziny[$i] . "&nbsp;&nbsp;&nbsp;&nbsp;";
			//if($i!=0 && $i%2==0) echo "</br>";
		}
		echo '</form></div>';
		*/		
			



		$polaczenie->close();
		
		function czyWolna($zajete,$sprawdz){
			for($i=0;$i<sizeof($zajete);$i++){
				if($zajete[$i]==$sprawdz) return 0;
			} return 1; // tej godziny nie znaleziono wśród zajętych - jest wolna - może zostać wyświetlona jako jedna z proponowanych
		}
		
		
		
		
		//$_SESSION['godzina'] = $godzina;
	?>
	
	<div class="main">
		<form action="" method="POST">
			<?php
				echo "<div class='radioBox'>";
				echo "<table>";
					for($i=0;$i<sizeof($dostepne_godziny);$i++){
						
						if($i!=0 && $i%4==0) echo "</tr>";
						if($i%4==0 && $i!=sizeof($dostepne_godziny)) echo "<tr>";
						echo "<td width='100px'><input type='radio' name='godz' value=". $dostepne_godziny[$i] .">&nbsp;&nbsp;" . $dostepne_godziny[$i] . "</td>";
					}			
				echo "</tr></table></div>";		
						
			?>

			<!--<input type="submit" name="zarezerwuj" value="Zarezerwuj termin wizyty">==>
			<!-- Modal -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Szczegóły wizyty</h5>
					<button type="button" class="close" data-dismiss="modal" label="Close">
					  <span hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							  <div class="col-8 col-sm-6">
								Lekarz: </br>
								Specjalista: </br>
								<hr>
								Data: </br>
								<!-- Godzina: </br> -->
							  </div>
							  <div class="col-4 col-sm-6">
								<?php
									
									//$_SESSION['godzina'] = $_POST['godz']; 
									$n = $_SESSION['kto'];
									$s = $_SESSION['spec'];
									$d = strval($_SESSION['data']);
									//$g = strval($_SESSION['godzina']);
									$d = $d[3].$d[4].".".$d[0].$d[1].".".$d[6].$d[7].$d[8].$d[9];
									echo $n."</br>";
									echo $s."</br>";
									echo "<hr>";
									echo $d."</br>";
							?> 
								</div>
							</div>
						</div>				
				  </div>
				  <div class="modal-footer">				  
					<input type="submit" class="btn btn-outline-success" name="zarezerwuj" value="Zarezerwuj termin wizyty">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Zamknij</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<button name="Button" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"> Zarezerwuj termin wizyty </button>
		</form>
	</div>
	
	
	
		<?php
	
		if(isset($_POST['zarezerwuj'])){	
			if(!empty($_POST['godz'])) {
				
				$_SESSION['godzina'] = $_POST['godz']; 
				//echo $_SESSION['godzina'];
				
				require_once "polaczenie.php";
				$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
		
				$selected = $_SESSION['selected'];
				$id_pacjenta = $_SESSION['id_pacjenta'];
				$data = $_SESSION['data'];
				$godzina = $_SESSION['godzina'];
				//echo $selected." ".$id_pacjenta." ".$data." ".$godzina;
				$polaczenie->query("INSERT INTO wizyty VALUES (null, '$selected', '$id_pacjenta', '$data', '$godzina')");
				
				$polaczenie->close();
				
				
				header('Location: wizyta_zapisana.php');
				
			} else {
				echo "Proszę wybrać godzinę";
			}
		}
		
		
	?>

</body>
</html>