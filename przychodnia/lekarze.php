<?php
	require_once "polaczenie.php";
	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	
	$lekarze = $polaczenie->query("SELECT id, imie FROM lekarze");

	while($rows = $lekarze->fetch_assoc()) {
		$id = $rows['id'];
		$imie = $rows['imie'];
		//$nazwisko = $rows['nazwisko'];
		echo "<option value='$id'>$imie </option>";
	}
?> 

