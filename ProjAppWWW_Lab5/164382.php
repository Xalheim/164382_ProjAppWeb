<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="pl" />
<meta name="Author" content="Marcel Hinc" />
<script src="js/kolorujtlo.js" type="text/javascript"></script>
<script src="js/timedate.js" type="text/javascript"></script>
<script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/styles.css">
<title> KomputeryProjekt </title>
<style> html {document.body.style.backgroundImage = './img/MainBlackWallpaper2.jpg';} </style>
</head>

<body onload="startClock()">

<header>
	<nav>
		<ul>
			<li> <a class="active" href="164382.php">Strona Główna</a> </li>
			<li> <a href="?idp=subpage1">Internet</a> </li>
			<li> <a href="?idp=subpage2">Gry</a> </li>
			<li> <a href="?idp=subpage3">Artwork</a> </li>
			<li> <a href="?idp=subpage4">Rysowanie</a> </li>
			<li> <a href="?idp=subpage5">Kodowanie</a> </li>
			<li> <a href="?idp=subpage6">Filmy</a> </li>
		</ul>
	</nav>
</header>

<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if($_GET['idp'] == '' && file_exists('html/glowna.html')) $strona = 'html/glowna.html';
if($_GET['idp'] == 'subpage1' && file_exists('html/Online.html')) $strona = 'html/Online.html';
if($_GET['idp'] == 'subpage2' && file_exists('html/Gaming.html')) $strona = 'html/Gaming.html';
if($_GET['idp'] == 'subpage3' && file_exists('html/Artwork.html')) $strona = 'html/Artwork.html';
if($_GET['idp'] == 'subpage4' && file_exists('html/Drawing.html')) $strona = 'html/Drawing.html';
if($_GET['idp'] == 'subpage5' && file_exists('html/Coding.html')) $strona = 'html/Coding.html';
if($_GET['idp'] == 'subpage6' && file_exists('html/Films.html')) $strona = 'html/Films.html';

include($strona);

?> 

</body>
</html>