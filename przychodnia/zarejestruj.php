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
					
				} else throw new Exception($polaczenie->error);
				
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
	<style media="screen">
	  body{
		margin: 0;
		padding: 0;
		font-family: sans-serif;
		background-image: url(gradient.jpg);
		background-size: cover;
	  }
	  .box{
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
	  }
	  .box h2{
		margin: 0 0 30px;
		padding: 0px;
		color: #fff;
		text-align: center;
	  }
	  .box .input-box{
		position: relative;
	  }
	  .box .input-box input{
		width: 100%;
		padding: 10px 0px;
		font-size: 16px;
		color: #fff;
		letter-spacing: 1px;
		margin-bottom: 30px;
		border: none;
		outline: none;
		background: transparent;
		border-bottom: 1px solid #fff;
	  }
	  .box .input-box label{
		position: absolute;
		top: 0;
		left: 0;
		letter-spacing: 1px;
		padding: 10px 0px;
		font-size: 16px;
		color: #fff;
		transition: .5s;
	  }
	  .box .input-box input:focus ~ label,
	  .box .input-box input:valid ~ label{
		top: -18px;
		left: 0px;
		color: #03a9f4;
		font-size: 12px;
	  }
	  .box input[type="submit"]{
		background: transparent;
		border: none;
		outline: none;
		color: #fff;
		background: #227be3;
		padding: 10px 20px;
		border-radius: 5px;
		cursor:pointer;
		margin-top: 20px;
	  }
	  .box input[type="submit"]:hover{
		background-color: #3067b9;
	  }

  </style>
</head>

<body>

	<div class="box">
	<h2>Zarejestruj się</h2>
		<form action="" method="post">
			<div class="input-box">
				<input type="text" name="imie" autocomplete="off" required />
				<label for="">Imię</label>
			</div>
			<div class="input-box">
				<input type="text" name="nazwisko" autocomplete="off" required />
				<label for="">Nazwisko</label>
			</div>
			<div class="input-box">
				<input type="text" name="pesel" autocomplete="off" required />
				<label for="">Pesel</label>
			</div>
			<div class="input-box">
				<input type="text" name="login" autocomplete="off" required />
				<label for="">Login</label>
			</div>
			<div class="input-box">
				<input type="password" name="haslo" autocomplete="off" required />
				<label for="">Hasło</label>
			</div>
			<div class="input-box">
				<input type="password" name="haslo2" autocomplete="off" required />
				<label for="">Powtórz hasło</label>
			</div>
			
			<form action="zarejestruj.php" method="post">
				<input type="submit" value="Zarejestruj się" />
			</form>
			
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
	</div>


</body>
</html>