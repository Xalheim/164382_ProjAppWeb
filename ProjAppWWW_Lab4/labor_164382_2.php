<?php
	$nr_indeksu = '164382';
	$nrGrupy = '2';
	echo 'Marcel Hinc '.$nr_indeksu.' grupa '.$nrGrupy.' ISI<br/><br/>';
		
	echo 'Zastosowanie metody include() <br/>';
	include 'Filetwo.php';
	echo '<br/>Zastosowanie metody include_once()<br/>';
	include_once 'Filethree.php';
	
	echo '<br/><br/>';
	echo 'Zastosowanie warunków if, else, elseif<br/>';
	
	$zmienna = 0;
	if ($zmienna == 0)
		echo 'Liczba to 0';
	elseif ($zmienna == 1)
		echo 'Liczba to 1';
	else
		echo 'Liczba nie jest ani 0 ani 1';
	
	echo '<br/><br/>';
	echo 'Zastosowanie warunku switch dla tej samej zmiennej<br/>';
	
	switch ($zmienna) {
		case 0:
			echo 'Liczba to 0 przy switch';
			break;
		case 1:
			echo 'Liczba to 1 przy switch';
			break;
		default:
			echo 'Liczba nie jest ani 0 ani 1 przy switch';
	}
	
	echo '<br/><br/>';
	echo 'Zastosowanie pętli while<br/>';
	$i = 1;
	while ($i <= 6):
		echo $i;
		$i++;
	endwhile;
	
	echo '<br/><br/>';
	echo 'Zastosowanie pętli for<br/>';
	for ($i = 1; $i <= 8; $i++) {
		echo $i;
	}
	
	$_GET["name"] = 'Marcel';
	$_POST["name"] = 'MarcelPost';

	
	echo '<br/><br/>';
	echo 'Wykorzystanie Get, Post, Session<br/>';
	echo 'Imie podane w http to: ' . htmlspecialchars($_GET["name"]);
	echo '<br/>';
	echo 'Nazwa podana w poscie to: ' . htmlspecialchars($_POST["name"]);
	echo '<br/>';
	session_start();
	$_SESSION["newsession"]=1;
	echo 'Sesja została aktywowana i nadana wartości 1';
?>