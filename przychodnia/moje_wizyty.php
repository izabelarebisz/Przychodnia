<?php
	session_start();
	
	if(isset($_SESSION['niezalogowano'])){
		header('Location: index.php');
		exit();
	}
	
		
	echo "Witaj ".$_SESSION['uzytkownik'].'!</br>';	
	echo "<a href='strona_glowna.php'>Strona główna</a></br>";
	echo "<a href='moje_wizyty.php'>Moje wizyty</a></br>";
	echo "<a href='moje_informacje.php'>Moje informacje</a></br>";
	echo "<a href='logout.php'>Wyloguj się</a>";
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Przychodnia</title>

	<style type-"text/css">
		.main{
			margin-left: 40px;
		}


	</style>
	  
</head>

<body>
	
	<div class="main">
		
	
		
		
	</div>
	
	
	
	<?php
	
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
		
		// aktualizacja bazy danych - usunięcie wizyt z minionych dni
			$now = new DateTime();
			$now = $now->format('m/d/Y'); 
			$nieaktualne = $polaczenie->query("SELECT * FROM wizyty");					
			while($rows = $nieaktualne->fetch_assoc()) {
				$kiedy = $rows['data']; // sprawdzamy datę każdej wizyty
				if(strtotime($kiedy)<strtotime($now)) $polaczenie->query("DELETE FROM wizyty WHERE data='$kiedy' "); // i usuwamy nieaktualne
			} 
			
			
		// pobieramy wybraną przez pacjenta datę($data) i lekarza($selected)
		if(isset($_POST['submit'])){
			if(!empty($_POST['Lekarze'])) {
				// zaznaczony lekarz
				$selected = $_POST['Lekarze'];
				//echo '<p>Wybrany lekarz: '.$selected.'</p>';
				// zaznaczona data
				$data = $_POST['Data'];
				//echo '<p>Wybrana data: '.$data.'</p>';
				
				$_SESSION['data'] = $data;
				$_SESSION['selected'] = $selected;
				
				echo $selected;
				$dane = $polaczenie->query("SELECT * FROM lekarze WHERE id_lekarza='$selected'");					
				while($rows = $dane->fetch_assoc()) {
					$_SESSION['kto'] = $rows['imie']." ".$rows['nazwisko'];
					$_SESSION['spec'] = $rows['specjalnosc'];
				}
	
	
				$polaczenie->close();
				header('Location: godzina.php');
			
			} else {
				echo 'Wybierz specjalistę.';
				$polaczenie->close();
			}			
		}
				
		
		
		function czyWolna($zajete,$sprawdz){
			for($i=0;$i<sizeof($zajete);$i++){
				if($zajete[$i]==$sprawdz) return 0;
			} return 1; // tej godziny nie znaleziono wśród zajętych - jest wolna - może zostać wyświetlona jako jedna z proponowanych
		}
	?>
	

</body>
</html>