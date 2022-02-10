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
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%,-50%);
			width: 400px;
			padding: 40px;
			background: rgba(0, 0, 0, 0.6);
			box-sizing: border-box;
			box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
			border-radius: 10px;
			color: #fff;
		}
		body{
			margin: 0;
			padding: 0;
			font-family: sans-serif;
			background-image: url(gradient.jpg);
			background-size: cover;
		  }
	</style>
	  
</head>

<body>
	
	<div class="main">
	<?php
	
		//$lekarz = $_SESSION['id_lekarza'];
		$pacjent = $_SESSION['id_pacjenta'];
		
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
		
		$wizyty = $polaczenie->query("SELECT * FROM wizyty WHERE id_pacjenta='$pacjent'");	// wszystkie wizyty użytkownika			
		
		while($rows = $wizyty->fetch_assoc()) {
			echo "Data: ".$rows['data']."</br>";
			echo "Godzina: ".$rows['godzina']."</br>";
			
			require_once "polaczenie.php";
			$polaczenie2 = @new mysqli($host,$db_user,$db_password,$db_name);
			$id_l = $rows['id_lekarza'];
			$lekarz = $polaczenie2->query("SELECT * FROM lekarze WHERE id_lekarza='$id_l'");	// znajdź lekarza w tabeli lekarze
			$lekarz = $lekarz->fetch_assoc();
			
			echo "Lekarz: ".$lekarz['imie']." ".$lekarz['nazwisko'].", specjalista ".$lekarz['specjalnosc']."</br>"."</br>";
			$polaczenie2->close();
		}
		
		$polaczenie->close();
		

	?>
	
		
	</div>

	

</body>
</html>