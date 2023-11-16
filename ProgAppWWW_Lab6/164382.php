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
			<li> <a href="?idp=2">Internet</a> </li>
			<li> <a href="?idp=3">Gry</a> </li>
			<li> <a href="?idp=4">Artwork</a> </li>
			<li> <a href="?idp=5">Rysowanie</a> </li>
			<li> <a href="?idp=6">Kodowanie</a> </li>
			<li> <a href="?idp=7">Filmy</a> </li>
		</ul>
	</nav>
</header>
<?php
include 'cfg.php';
include 'showpage.php';
if (isset($_GET['idp'])) {
	echo PokazPodstrone($_GET['idp']);
}
else {
	echo PokazPodstrone(1);
}
?>
</body>
</html>