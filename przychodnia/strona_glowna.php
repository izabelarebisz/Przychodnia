<?php
	session_start();
	
	if(isset($_SESSION['niezalogowano'])){
		header('Location: index.php');
		exit();
	}	
		
	echo "Witaj ".$_SESSION['uzytkownik'].'!</br>';	
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
	
	  }
	  .box .input-box{
		position: relative;
	  }
	  .margin{
		  margin-top: 30px;
	  }
	  .buttonMenu{
		  margin-left: 5px;
		  margin-top: 5px;
		background: #a48adb;
		border-bottom: 1px solid #fff;
		box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
		line-height: 0.5;
	  }
	  
	  </style>
</head>

<body>
	
	<div class="main">

	
		<!-- kalendarz -->		
		<form action="" method="POST"> 
			<div class="box">
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
							echo "<option value='$id'>dr $imie $nazwisko</option>";
						}
						
						$polaczenie->close();
					?> 

				</select>	
				
				<!-- znajdź termin wizyty -->	
				<div class="margin">
					
					<input type="submit" name="submit" value="Znajdź termin wizyty"/>
					
				</div>	
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
	
		
		// aktualizacja bazy danych - usunięcie wizyt z minionych dni
			$now = new DateTime();
			$now = $now->format('m/d/Y'); 
			$nieaktualne = $polaczenie->query("SELECT * FROM wizyty");					
			while($rows = $nieaktualne->fetch_assoc()) {
				$kiedy = $rows['data']; // sprawdzamy datę każdej wizyty
				if(strtotime($kiedy)<strtotime($now)) $polaczenie->query("DELETE FROM wizyty WHERE data='$kiedy' "); // i usuwamy nieaktualne
			} 
			
			
		// pobieramy wybraną przez pacjenta datę($data) i lekarza($selected)
		if(isset($_POST['submit'])){
			if(!empty($_POST['Lekarze'])) {
				// zaznaczony lekarz
				$selected = $_POST['Lekarze'];
				//echo '<p>Wybrany lekarz: '.$selected.'</p>';
				// zaznaczona data
				$data = $_POST['Data'];
				//echo '<p>Wybrana data: '.$data.'</p>';
				
				$_SESSION['data'] = $data;
				$_SESSION['selected'] = $selected;
				
				
				//echo $selected;
				$dane = $polaczenie->query("SELECT * FROM lekarze WHERE id_lekarza='$selected'");					
				while($rows = $dane->fetch_assoc()) {			
					$_SESSION['kto'] = $rows['imie']." ".$rows['nazwisko'];
					$_SESSION['spec'] = $rows['specjalnosc'];
				}
				
				$pacjent = $_SESSION['id_pacjenta'];
				$czyWizyta = $polaczenie->query("SELECT * FROM wizyty WHERE id_lekarza='$selected' AND id_pacjenta='$pacjent'");
				$ile = $czyWizyta->num_rows;
				
				$polaczenie->close();
				
				if($ile>0) {
					echo 'Jesteś już umówiony do wybranego lekarza. Nie można zarezerwować więcej terminów.';
				} else{
					header('Location: godzina.php');
					
				}
			
			} else {
				echo 'Wybierz specjalistę.';
				$polaczenie->close();
			}			
		}
				
		
		
		function czyWolna($zajete,$sprawdz){
			for($i=0;$i<sizeof($zajete);$i++){
				if($zajete[$i]==$sprawdz) return 0;
			} return 1; // tej godziny nie znaleziono wśród zajętych - jest wolna - może zostać wyświetlona jako jedna z proponowanych
		}
	?>
	

</body>
</html>