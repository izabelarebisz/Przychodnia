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

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css" rel="stylesheet" id="bootstrap-css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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

	
	
	<div class="main">
	
	
	
		<!-- kalendarz -->		
		<form action="" method="POST">
			<h2>Data</h2>
			<div id="datepicker" class="input-group date" data-date-formate="yyyy-mm-dd">
				
					<input name="Data" class="form-control" type="text" />		
				
				<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				
			</div>
		
				
				
			<!-- wybór lekarza -->	
			<select name="Lekarze">
				<?php
				require_once "polaczenie.php";
				$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

				$lekarze = $polaczenie->query("SELECT * FROM lekarze");	
					// uzupełniamy opcje kolejnymi imionami i nazwiskami lekarzy
					while($rows = $lekarze->fetch_assoc()) {
						$id = $rows['id_lekarza'];
						$imie = $rows['imie'];
						$nazwisko = $rows['nazwisko'];
						echo "<option value='$id' style='background:lightblue;'>dr $imie $nazwisko</option>";
					}
					
					$polaczenie->close();
				?> 

			</select>	
			
			<!-- znajdź termin wizyty -->	
			<div>
				<h2>test</h2>
				<input type="submit" name="submit" value="Znajdź termin wizyty">
			</div>	
					
		</form>
		
	</div>
	
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#datepicker").datepicker({
				startDate: new Date(),
				endDate: '+31d',
	            todayHighlight: true,
		        showOtherMonths: true,
		        changeMonth: true,
		        changeYear: true,
		        orientation: "button"
				}).datepicker('update',new Date())
		});

	</script>			
	
	
	
	
	<?php
	
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
		//$data1 = date('yyyy-mm-dd', strtotime($_POST['Data']));
		
		
		// pobieramy wybraną przez pacjenta datę($data) i lekarza($selected)
		if(isset($_POST['submit'])){
			if(!empty($_POST['Lekarze'])) {
				// zaznaczony lekarz
				$selected = $_POST['Lekarze'];
				//echo '<p>Wybrany lekarz: '.$selected.'</p>';
				// zaznaczona data
				$data = $_POST['Data'];
				//echo '<p>Wybrana data: '.$data.'</p>';
			
			} else {
				echo 'Wybierz specjalistę.';
			}			
		
			// informacje
			
			echo "<div class='main'>";
			$specjalista = $polaczenie->query("SELECT * FROM lekarze WHERE id_lekarza='$selected' ");
			while($rows = $specjalista->fetch_assoc()){
				echo "<h2>".$rows['imie']." ".$rows['nazwisko']."</h2>";
				echo "<h3>".$rows['specjalnosc']."</h3>";
			}
			echo "<h2>Dzień '$data'</h2></div>";
			
			
			// aktualizacja bazy danych - usunięcie wizyt z minionych dni
			$now = new DateTime();
			$now = $now->format('m/d/Y'); 
			$nieaktualne = $polaczenie->query("SELECT * FROM wizyty");					
			while($rows = $nieaktualne->fetch_assoc()) {
				$kiedy = $rows['data']; // sprawdzamy datę każdej wizyty
				if(strtotime($kiedy)<strtotime($now)) $polaczenie->query("DELETE FROM wizyty WHERE data='$kiedy' "); // i usuwamy nieaktualne
			} 
				
			
			
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
			/*
			echo "</br>Zajete godziny: </br>";
			
			for($i=0;$i<sizeof($zajete);$i++){
				echo "Godz. ".$zajete[$i]."</br>";
			}
			*/
				

			
			$dostepne_godziny = array();
			for($i=0;$i<sizeof($wszystkie_godziny);$i++){
				if(czyWolna($zajete,$wszystkie_godziny[$i])==1) array_push($dostepne_godziny, $wszystkie_godziny[$i]); // 
				
			}
			
			echo "<div class='main'><form>";
			for($i=0;$i<sizeof($dostepne_godziny);$i++){
				echo "<input type='radio' name='godz' id='godz' value=". $dostepne_godziny[$i] ."/>&nbsp;&nbsp;" . $dostepne_godziny[$i] . "&nbsp;&nbsp;&nbsp;&nbsp;";
				//if($i!=0 && $i%2==0) echo "</br>";
			}
			echo "</form></div>";

			/*
			echo "</br>Dostepne godziny: </br>";
			for($i=0;$i<sizeof($dostepne_godziny);$i++){
				echo "Godz. ".$dostepne_godziny[$i]."</br>";
			}
			
			echo "</br>Zajete godziny: </br>";
			
			for($i=0;$i<sizeof($zajete);$i++){
				echo "Godz. ".$zajete[$i]."</br>";
			}
			*/
			
			
			
			
			
			
			
			
			
			
			
			//$polaczenie->query("INSERT INTO wizyty VALUES ('$selected', '$id_pacjenta', '$data', '$godzina')");
		}
		

	
			
			
		$polaczenie->close();
		
		function czyWolna($zajete,$sprawdz){
			for($i=0;$i<sizeof($zajete);$i++){
				if($zajete[$i]==$sprawdz) return 0;
			} return 1; // tej godziny nie znaleziono wśród zajętych - jest wolna - może zostać wyświetlona jako jedna z proponowanych
		}
	?>
	
	
	


</body>
</html>