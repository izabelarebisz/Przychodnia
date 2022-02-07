<?php

	session_start();
	
	if ((isset($_SESSION['zalogowano'])) && ($_SESSION['zalogowano']==true))
		// po odświeżeniu strony jeśli użytkownik jest zalogowany to nie zostanie wylogowany
	{
		header('Location: strona_glowna.php');
		exit(); // przejdź od razu, nie wykonuj tego pliku bo użytkownik jest już zalogowany
	}

?> 

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Przychodnia</title>
</head>

<body>
	
	<form action="zaloguj.php" method="post">
	
		Login: <br /> <input type="text" name="login" /> <br />
		Hasło: <br /> <input type="password" name="haslo" /> <br /><br />
		<input type="submit" value="Zaloguj się" />

	</form>
	<form action="zarejestruj.php" method="post">
		<input type="submit" value="Zarejestruj się" />
	</form>
 	
<?php
	if(isset($_SESSION['niezalogowano'])) echo $_SESSION['niezalogowano']; // jeżeli zmienna $_SESSION['niezalogowano'] jest już ustawiona
?> 

</body>
</html>