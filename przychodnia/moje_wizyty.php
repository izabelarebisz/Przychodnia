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
	
		
	</div>

	

</body>
</html>