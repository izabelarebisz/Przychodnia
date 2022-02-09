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
	<h2>Zaloguj się</h2>
		<form action="zaloguj.php" method="post">
			<div class="input-box">
				<input type="text" name="login" autocomplete="off" required />
				<label for="">Login</label>
			</div>
			<div class="input-box">
				<input type="password" name="haslo" autocomplete="off" required />
				<label for="">Hasło</label>
			</div>
			<input type="submit" value="Zaloguj się" />

		</form>
		<form action="zarejestruj.php" method="post">
			<input type="submit" value="Zarejestruj się" />
		</form>
	</div>
 	
<?php
	if(isset($_SESSION['niezalogowano'])) echo $_SESSION['niezalogowano']; // jeżeli zmienna $_SESSION['niezalogowano'] jest już ustawiona
?> 

</body>
</html>