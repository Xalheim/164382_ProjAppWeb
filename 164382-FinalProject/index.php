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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
		$Admin = new Admin();
		echo $Admin->LoginAdmin();									// Login do strony administracyjnej oraz jej wyświetlenie
		break;
	case -2:
		$Admin = new Admin();
		echo $Admin->CreatePage();											// Login do strony administracyjnej oraz utworzenie nowej strony w bazie danych
		break;
		
	case -3:
		$Admin = new Admin();
		echo $Admin->EditPage();											// Login do strony administracyjnej oraz edycja istniejącej strony w bazie danych
		break;
		
	case -4:
		$Admin = new Admin();
		echo $Admin->DeletePage();											// Login do strony administracyjnej oraz usunięcie wybranej strony z bazy danych
		break;
		
	case -5:
		$Contact = new Contact();
		echo "<h2> Kontakt </h2>";
		echo $Contact->WyslijMailKontakt("164382@student.uwm.edu.pl"); 		// Wyświetlenie formularza do kontaktu email'em
		echo "<br></br>";
		echo "<a href='?idp=-6'>Odzyskanie hasla</a>";
		break;
		
	case -6:
		$Contact = new Contact();
		echo "<h2> Odzyskanie hasla </h2>";
		echo $Contact->PrzypomnijHaslo("164382@student.uwm.edu.pl"); 			// Wyświetlenie promptu na email, w celu odzyskania hasła
		echo "<br></br>";
		echo "<a href='?idp=-5'>Kontakt</a>";
		break;
		
	case -7:
		$Category = new Category();
		echo $Category->PokazKategorie(); 										// Login do strony administracyjnej, oraz wyświetlenie tabeli Kategorii
		break;
		
	case -8:
		$Category = new Category();
		echo $Category->AddCategory();											// Login do strony administracyjnej oraz utworzenie nowej kategorii w bazie danych
		break;
		
	case -9:
		$Category = new Category();
		echo $Category->EditCategory();										// Login do strony administracyjnej oraz edycja istniejącej kategorii w bazie danych
		break;
		
	case -10:
		$Category = new Category();
		echo $Category->DeleteCategory();										// Login do strony administracyjnej oraz usunięcie wybranej kategorii z bazy danych
		break;
		
	case -11:
		$Product = new Product();
		echo $Product->PokazProdukty(); 										// Login do strony administracyjnej, oraz wyświetlenie tabeli Produktów
		break;
		
	case -12:
		$Product = new Product();
		echo $Product->AddProduct();											// Login do strony administracyjnej oraz utworzenie nowego produktu w bazie danych
		break;
		
	case -13:
		$Product = new Product();
		echo $Product->EditProduct();											// Login do strony administracyjnej oraz edycja istniejącego produktu w bazie danych
		break;
		
	case -14:
		$Product = new Product();
		echo $Product->DeleteProduct();										// Login do strony administracyjnej oraz usunięcie wybranego produktu z bazy danych
		break;
		
	case -15:
		$Store = new Store();
		echo $Store->StorePage();											// Wyświetlenie produktów oraz możliwość dodania do koszyka i zakupu
		break;
		
	case -16:
		$Store = new Store();
		echo $Store->ShowCart();											// Wyświetlenie produktów oraz możliwość dodania do koszyka i zakupu
		break;
		
	default:
		$Pages = new Pages();
		echo $Pages->PokazPodstrone($idPage);								// Pobranie danych z strony id=1 oraz wyświetlenie jej (strona główna)
	}
}
else {
	$Pages = new Pages();
	$_GET['idp'] = 1;
	echo $Pages->PokazPodstrone(1);
}

if ($_GET['idp'] == 1) {											// Dla strony głównej zawsze zostanie wyświetlona informacja o autorze i wersji strony.
	$_GET["user"] = 'Marcel Hinc';
	$nr_indeksu = '164382';
	$nrGrupy = 'ISI 2';
	echo '<br /><br />Autor: '.htmlspecialchars($_GET["user"]).'; id '.$nr_indeksu.'; grupa '.$nrGrupy.' <br />';
	include './php/Version.php';
	$Version = new Version();
	echo $Version->DisplayVersion();
	echo '<br /><br />';
}

?>

</body>
</html>