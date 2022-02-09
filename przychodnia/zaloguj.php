<?php
	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['haslo']))){ // jeśli nie istnieją zmienne login i hasło
		header('Location: index.php');
		exit();
	}
	
	require_once "polaczenie.php";
	
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
	if($polaczenie->connect_errno!=0){
		echo "Error: ".$polaczenie->connect_errno ;
	} else {
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		// zabezpieczenie przed wstrzykiwaniem sql
		$login = htmlentities($login,ENT_QUOTES,"UTF-8");
		//$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
		
		//$zapytanie = "SELECT * FROM pacjenci WHERE login='$login' AND haslo='$haslo'";
		if ($wynik = @$polaczenie->query(sprintf("SELECT * FROM pacjenci WHERE login='%s'",
		mysqli_real_escape_string($polaczenie,$login)))){
			$ile_zalogowanych = $wynik->num_rows;
			if($ile_zalogowanych==1) { //1 użytkownik się zalogował - login i hasło zostały znalezione
				$wiersz = $wynik->fetch_assoc();
				if(password_verify($haslo,$wiersz['haslo'])){ // hasło zahashowane
					$SESSION['zalogowano'] = true;
		
					$_SESSION['id_pacjenta'] = $wiersz['id_pacjenta'];
					$_SESSION['uzytkownik'] = $wiersz['imie']." ".$wiersz['nazwisko'];
					$_SESSION['pesel'] = $wiersz['pesel'];
					
					unset($_SESSION['niezalogowano']); // usuń zmienną sesji 'niezalogowano' jeśli udało się zalogować
					$wynik->free_result();
					header('Location: strona_glowna.php');
				} else {
					$_SESSION['niezalogowano'] = '<span style="color:red">Nieprawidłowe dane logowania.</span>';
					header('Location: index.php'); //wracamy do okna logowania	
				}
			
			} else {
				$_SESSION['niezalogowano'] = '<span style="color:red">Nieprawidłowe dane logowania.</span>';
				header('Location: index.php'); //wracamy do okna logowania
				
			}
			
	}
		$polaczenie->close();
	}



?>