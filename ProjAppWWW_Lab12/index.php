<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
			<li> <a class="active" href="index.php">Strona Główna</a> </li>
			<li> <a href="?idp=2">Internet</a> </li>
			<li> <a href="?idp=3">Gry</a> </li>
			<li> <a href="?idp=4">Artwork</a> </li>
			<li> <a href="?idp=5">Rysowanie</a> </li>
			<li> <a href="?idp=6">Kodowanie</a> </li>
			<li> <a href="?idp=7">Filmy</a> </li>
			<li> <a href="?idp=-1">Admin</a> </li>
			<li> <a href="?idp=-15">Sklep</a> </li>
			<li> <a href="?idp=-5">Kontakt</a> </li>
		</ul>
	</nav>
</header>

<?php
include 'cfg.php';
include 'showpage.php';
include 'admin/admin.php';
include 'php/contact.php';
include 'php/Categories.php';
include 'php/Products.php';
include 'php/Store.php';

//
// Pobieramy aktualną wartość idp, oraz w oparciu o nią wyświetlamy stronę z bazy danych, lub przechodzimy do stron statycznych/administracyjnych
//

if (isset($_GET['idp'])) {
	$idPage = htmlspecialchars($_GET['idp']);
	switch ($idPage) {
	case -1:
		echo LoginAdmin();											// Login do strony administracyjnej oraz jej wyświetlenie
		break;
	case -2:
		echo CreatePage();											// Login do strony administracyjnej oraz utworzenie nowej strony w bazie danych
		break;
		
	case -3:
		echo EditPage();											// Login do strony administracyjnej oraz edycja istniejącej strony w bazie danych
		break;
		
	case -4:
		echo DeletePage();											// Login do strony administracyjnej oraz usunięcie wybranej strony z bazy danych
		break;
		
	case -5:
		echo "<h2> Kontakt </h2>";
		echo WyslijMailKontakt("164382@student.uwm.edu.pl"); 		// Wyświetlenie formularza do kontaktu email'em
		echo "<br></br>";
		echo "<a href='?idp=-6'>Odzyskanie hasla</a>";
		break;
		
	case -6:
		echo "<h2> Odzyskanie hasla </h2>";
		echo PrzypomnijHaslo("164382@student.uwm.edu.pl"); 			// Wyświetlenie promptu na email, w celu odzyskania hasła
		echo "<br></br>";
		echo "<a href='?idp=-5'>Kontakt</a>";
		break;
		
	case -7:
		echo PokazKategorie(); 										// Login do strony administracyjnej, oraz wyświetlenie tabeli Kategorii
		break;
		
	case -8:
		echo AddCategory();											// Login do strony administracyjnej oraz utworzenie nowej kategorii w bazie danych
		break;
		
	case -9:
		echo EditCategory();										// Login do strony administracyjnej oraz edycja istniejącej kategorii w bazie danych
		break;
		
	case -10:
		echo DeleteCategory();										// Login do strony administracyjnej oraz usunięcie wybranej kategorii z bazy danych
		break;
		
	case -11:
		echo PokazProdukty(); 										// Login do strony administracyjnej, oraz wyświetlenie tabeli Produktów
		break;
		
	case -12:
		echo AddProduct();											// Login do strony administracyjnej oraz utworzenie nowego produktu w bazie danych
		break;
		
	case -13:
		echo EditProduct();											// Login do strony administracyjnej oraz edycja istniejącego produktu w bazie danych
		break;
		
	case -14:
		echo DeleteProduct();										// Login do strony administracyjnej oraz usunięcie wybranego produktu z bazy danych
		break;
		
	case -15:
		echo StorePage();											// Wyświetlenie produktów oraz możliwość dodania do koszyka i zakupu
		break;
		
	case -16:
		echo ShowCart();											// Wyświetlenie produktów oraz możliwość dodania do koszyka i zakupu
		break;
		
	default:
		echo PokazPodstrone($idPage);								// Pobranie danych z strony id=1 oraz wyświetlenie jej (strona główna)
	}
}
else {
	$_GET['idp'] = 1;
	echo PokazPodstrone(1);
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