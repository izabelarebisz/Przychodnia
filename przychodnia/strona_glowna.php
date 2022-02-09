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

	
	
	<div class="main">
	
	
	
		<!-- kalendarz -->		
		<form action="" method="POST">
			<h2>Data</h2>
			<div id="datepicker" class="input-group date" data-date-formate="yyyy-mm-dd">
				
					<input name="Data" class="form-control" type="text" />		
				
				<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				
			</div>
		
				
				
			<!-- wybór lekarza -->	
			<select name="Lekarze">
				<?php
				require_once "polaczenie.php";
				$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

				$lekarze = $polaczenie->query("SELECT * FROM lekarze");	
					// uzupełniamy opcje kolejnymi imionami i nazwiskami lekarzy
					while($rows = $lekarze->fetch_assoc()) {
						$id = $rows['id_lekarza'];
						$imie = $rows['imie'];
						$nazwisko = $rows['nazwisko'];
						echo "<option value='$id' style='background:lightblue;'>dr $imie $nazwisko</option>";
					}
					
					$polaczenie->close();
				?> 

			</select>	
			
			<!-- znajdź termin wizyty -->	
			<div>
				<h2>test</h2>
				<input type="submit" name="submit" value="Znajdź termin wizyty">
			</div>		
		</form>
		
	</div>
	
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#datepicker").datepicker({
				startDate: new Date(),
				endDate: '+31d',
	            todayHighlight: true,
		        showOtherMonths: true,
		        changeMonth: true,
		        changeYear: true,
		        orientation: "button"
				}).datepicker('update',new Date())
		});

	</script>			
	
	
	
	
	<?php
	
		require_once "polaczenie.php";
		$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
		//$data1 = date('yyyy-mm-dd', strtotime($_POST['Data']));
		
		
		// pobieramy wybraną przez pacjenta datę($data) i lekarza($selected)
		if(isset($_POST['submit'])){
			if(!empty($_POST['Lekarze'])) {
				// zaznaczony lekarz
				$selected = $_POST['Lekarze'];
				echo '<p>Wybrany lekarz: '.$selected.'</p>';
				// zaznaczona data
				$data = $_POST['Data'];
				echo '<p>Wybrana data: '.$data.'</p>';
			
			} else {
				echo 'Please select the value.';
			}
			
			$godzina = "9:30";
			$id_pacjenta = 2;
			
			$polaczenie->query("INSERT INTO wizyty VALUES ('$selected', '$id_pacjenta', '$data', '$godzina')");
		}
		

	
		$polaczenie->close();
	?>
	
	
	


</body>
</html>