<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$baza = 'moja_strona';
	$login = 'test@admin.com';
	$pass = 'admin';
	
	$mysqli = new mysqli ($dbhost, $dbuser, $dbpass, $baza);
	$GLOBALS['mysqli'] = $mysqli;
	$link = mysqli_connect($dbhost,$dbuser,$dbpass);
	if (!$link) echo '<b>Połączenie zostało przerwane.</b>';
	if (!mysqli_select_db($link, $baza)) echo 'Nie wybrano bazy';
?>
