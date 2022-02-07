<?php
	session_start();
	
	if(isset($_SESSION['niezalogowano'])){
		header('Location: index.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Przychodnia</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/datepicker.css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script> 
	<style type-"text/css">
		#datepicker{
			width: 200px;
			margin: 20px 20px 20px 20px;
		}
		#datepicker > span:hover{
			cursor: pointer;
		}
	</style>
</head>

<body>
	<h1 align="center">Data</h1>
	<div id="datepicker" class="input-group date" data-date-formate="yyyy-mm-dd">
		<input type="date" name="wybierz_date">
		<span class="input-group-addon"><i class="glyphicon glyphicon-callendar"></i></span>
	</div>
	<script type="text/javascript">
		$(function(){
			$("#datepicker").datepicker({
				autoclose: true,
			todayHighlight: true}
			).datepicker('update',new Date())
		});
	</script>
		
		
<?php
	echo "<p>Witaj ".$_SESSION['uzytkownik'].'![<a href="logout.php">Wyloguj się</a>]</p>';
	echo "<p>Pesel ".$_SESSION['pesel']."!";
?> 

</body>
</html>