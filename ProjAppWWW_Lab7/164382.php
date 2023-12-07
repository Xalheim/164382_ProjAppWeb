<?php
session_start();
?>

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
			<li> <a href="?idp=-1">Admin</a> </li>
		</ul>
	</nav>
</header>

<?php
include 'cfg.php';
include 'showpage.php';
include 'admin/admin.php';


if (isset($_GET['idp'])) {
	if ($_GET['idp'] == -1) {
		echo LoginAdmin();
	}
	else if ($_GET['idp'] == -2) {
		echo CreatePage();
	}
	else if ($_GET['idp'] == -3) {
		echo EditPage();
	}
	else if ($_GET['idp'] == -4) {
		echo DeletePage();
	}
	else {
		echo PokazPodstrone($_GET['idp']);
	}
}
else {
	$_GET['idp']=1;
	echo PokazPodstrone($_GET['idp']);
}

if ($_GET['idp'] == 1) {
	$_GET["user"] = 'Marcel Hinc';
	$nr_indeksu = '164382';
	$nrGrupy = 'ISI 2';
	echo '<br /><br />Autor: '.htmlspecialchars($_GET["user"]).'; id '.$nr_indeksu.'; grupa '.$nrGrupy.' <br />';
	include './php/Version.php';
	echo '<br /><br />';
}

?>

</body>
</html>