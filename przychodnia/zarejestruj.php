<?php

	session_start();
	
	if(isset($_POST['haslo'])){
		$walidacja = true;
		
		// pesel
		$pesel=$_POST['pesel'];
		if(strlen($pesel)!=11) {
			$walidacja = false;
			$_SESSION['err_p']="Nieprawidłowa wartość pesel";
			
		}
		
		// hasło
		$haslo1 = $_POST['haslo'];
		$haslo2 = $_POST['haslo2'];
		
		if(strlen($haslo1)<5 || strlen($haslo1)>20){
			$walidacja = false;
			$_SESSION['err_h']="Hasło musi zawierać od 5 do 20 znaków";
		}
		
		if($haslo1!=$haslo2){
			$walidacja = false;
			$_SESSION['err_h']="Hasła muszą być identyczne";
		}
		
		$haslo = password_hash($haslo1,PASSWORD_DEFAULT);
		
		// puste pola
		$imie = $_POST['imie'];
		$nazwisko = $_POST['nazwisko'];
		$login = $_POST['login'];
		if(strlen($imie)==0 || strlen($nazwisko)==0 || strlen($login)==0){
			$walidacja = false;
			$_SESSION['err_o']="Wszystkie pola muszą zostać wypełnione";
		}
		
		// login
		require_once "polaczenie.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		

		try {
			$login = $_POST['login'];
			$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
			if($polaczenie->connect_errno!=0){  // nieudane połączenie
				throw new Exception(mysqli_connect_errno()); // łapie wyjątki, zwraca opis
			} else { // sprawdzamy czy login już istnieje
				$wynik = $polaczenie->query("SELECT imie FROM pacjenci WHERE login='$login'");
				$znalezione_loginy = $wynik->num_rows;
				if($znalezione_loginy>0) {
					$walidacja = false;
					$_SESSION['err_l']="Ten login już istnieje";			
				}				
				
				
				
				// walidacja się udała 
				if($walidacja==true) {
					if ($polaczenie->query("INSERT INTO pacjenci VALUES (NULL, '$imie', '$nazwisko', '$pesel', '$login', '$haslo')")) header('Location: zarejestrowano_pomyslnie.php');
					
				} else throw new Exception();
				
				$polaczenie->close();
			}
			
			
		} catch(Exception $e) {
			//echo "Błąd serwera";
			echo $e;
		}
		
		
		
		
	}

?> 

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Załóż konto</title>
	<style>
		.error {
			color: red;
			margin-top: 20px;
		}
		form{
			width: 300px;
			height: 300px;
			background-color: yellow;
			 align-items: center;
			 padding-top: 30px;
		     padding-right: 30px;
		     padding-bottom: 20px;
		     padding-left: 30px;
		}
	
	</style>
</head>

<body>
	
	<form method="post" >
		
		Imię: <input type="text" name="imie"/> <br/>
		Nazwisko: <input type="text" name="nazwisko"/> <br/>
		Pesel: <input type="text" name="pesel"/> <br/>
		Login: <input type="text" name="login"/> <br/>
		Hasło: <input type="password" name="haslo"/> <br/>
		Powtórz hasło: <input type="password" name="haslo2"/> <br/>
		
		<input type="submit" value="Zarejestruj się"/>
		
		<?php
			// pesel
			if(isset($_SESSION['err_p'])){
				echo '<div class="error">'.$_SESSION['err_p'].'</div>';
				unset($_SESSION['err_p']);
			}
			
			// login
			if(isset($_SESSION['err_l'])){
				echo '<div class="error">'.$_SESSION['err_l'].'</div>';
				unset($_SESSION['err_l']);
			}
			
			// hasło
			if(isset($_SESSION['err_h'])){
				echo '<div class="error">'.$_SESSION['err_h'].'</div>';
				unset($_SESSION['err_h']);
			}
			
			// ogólne
			if(isset($_SESSION['err_o'])){
				echo '<div class="error">'.$_SESSION['err_o'].'</div>';
				unset($_SESSION['err_o']);
			}
		
		?>
		
	</form>


</body>
</html>