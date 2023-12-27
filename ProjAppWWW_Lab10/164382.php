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
			<li> <a href="?idp=-5">Kontakt</a> </li>
		</ul>
	</nav>
</header>

<?php
include 'cfg.php';
include 'showpage.php';
include 'admin/admin.php';
include 'php/contact.php';

//
// Pobieramy aktualną wartość idp, oraz w oparciu o nią wyświetlamy stronę z bazy danych, lub przechodzimy do stron statycznych/administracyjnych
//

if (isset($_GET['idp'])) {
	$_GET['idp'] = htmlspecialchars($_GET['idp']);
	if ($_GET['idp'] == -1) {
		echo LoginAdmin();											// Login do strony administracyjnej oraz jej wyświetlenie
	}
	else if ($_GET['idp'] == -2) {
		echo CreatePage();											// Login do strony administracyjnej oraz utworzenie nowej strony w bazie danych
	}
	else if ($_GET['idp'] == -3) {
		echo EditPage();											// Login do strony administracyjnej oraz edycja istniejącej strony w bazie danych
	}
	else if ($_GET['idp'] == -4) {
		echo DeletePage();											// Login do strony administracyjnej oraz usunięcie wybranej strony z bazy danych
	}
	else if ($_GET['idp'] == -5) {
		echo "<h1> Kontakt </h1>";
		echo WyslijMailKontakt("164382@student.uwm.edu.pl"); 		// Wyświetlenie formularza do kontaktu email'em
		echo "<br></br>";
		echo "<a href='?idp=-6'>Odzyskanie hasla</a>";
	}
	else if ($_GET['idp'] == -6) {
		echo "<h1> Odzyskanie hasla </h1>";
		echo PrzypomnijHaslo("164382@student.uwm.edu.pl"); 			// Wyświetlenie promptu na email, w celu odzyskania hasła
		echo "<br></br>";
		echo "<a href='?idp=-5'>Kontakt</a>";
	}
	else if ($_GET['idp'] == -7) {
		echo PokazKategorie(); 										// Login do strony administracyjnej, oraz wyświetlenie tabeli Kategorii
	}
	else if ($_GET['idp'] == -8) {
		echo AddCategory();											// Login do strony administracyjnej oraz utworzenie nowej kategorii w bazie danych
	}
	else if ($_GET['idp'] == -9) {
		echo EditCategory();											// Login do strony administracyjnej oraz edycja istniejącej kategorii w bazie danych
	}
	else if ($_GET['idp'] == -10) {
		echo DeleteCategory();											// Login do strony administracyjnej oraz usunięcie wybranej kategorii z bazy danych
	}
	else {
		echo PokazPodstrone($_GET['idp']);							// Pobranie strony w oparciu o id z bazy danych, oraz jej wyświetlenie
	}
}
else {
	$_GET['idp']=1;
	echo PokazPodstrone($_GET['idp']);								// Pobranie danych z strony id=1 oraz wyświetlenie jej (strona główna)
}

if ($_GET['idp'] == 1) {											// Dla strony głównej zawsze zostanie wyświetlona informacja o autorze i wersji strony.
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