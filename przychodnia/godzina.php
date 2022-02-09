<?php
	session_start();
	
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

	<style type-"text/css">
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


	</style>
</head>

<body>	
	
	<?php
	
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
		
		$data = $_SESSION['data'];
		$selected = $_SESSION['selected'];
		
		// informacje
		
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
	
	<div>
		<form action="" method="POST">
			<?php
				for($i=0;$i<sizeof($dostepne_godziny);$i++){
					echo "<input type='radio' name='godz' value=". $dostepne_godziny[$i] .">&nbsp;&nbsp;" . $dostepne_godziny[$i] . "&nbsp;&nbsp;&nbsp;&nbsp;";
				}
					
			?>

			<input type="submit" name="zarezerwuj" value="Zarezerwuj termin wizyty">
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
				echo $selected." ".$id_pacjenta." ".$data." ".$godzina;
				$polaczenie->query("INSERT INTO wizyty VALUES (null, '$selected', '$id_pacjenta', '$data', '$godzina')");
				
				$polaczenie->close();
			} else {
				echo "Proszę wybrać godzinę";
			}
		}
		
		
	?>

</body>
</html>