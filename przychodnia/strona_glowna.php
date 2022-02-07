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
	<!-- kalendarz -->
	<div class="main">
		<h2>Data</h2>
		<div id="datepicker" class="input-group date" data-date-formate="yyyy-mm-dd">
			<input class="form-control" type="text" name="wybierz_date">
			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div>
	</div>
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#datepicker").datepicker({
				//autoclose: true,
	            todayHighlight: true,
		        showOtherMonths: true,
		        changeMonth: true,
		        changeYear: true,
		        orientation: "button"
				}).datepicker('update',new Date())
		});
	</script>			
		
		
			
	<!-- wybór lekarza -->	
	<?php
		
	require_once "polaczenie.php";
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

	$lekarze = $polaczenie->query("SELECT * FROM lekarze");
		
	?> 
	<div class="main">
		<select id="opcje" name="lekarze">
	</div>
	<?php
	
		while($rows = $lekarze->fetch_assoc()) {
			//$id = $rows['id'];
			$imie = $rows['imie'];
			$nazwisko = $rows['nazwisko'];
			echo "<option value='$imie' style='background:lightblue;'>dr $imie $nazwisko</option>";
		}
		
		$opcja = $_POST['opcje'];
		
		echo $opcja;
	?> 
	


</body>
</html>