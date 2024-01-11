<?php
//
// funkcja 'StorePage' wyświetla produkty w odpowiednich kategoriach, oraz umożliwia dodawanie ich do koszyka
//



function StorePage() {
	echo "<h2> Sklep </h2>";

	if (isset($_SESSION['count'])) {
		echo "<a href='?idp=-16'>Wciśnij aby przejść do Koszyka (Aktualna liczba produktów: ".$_SESSION['count'].")</a><br></br>";
	}
	else {
		echo "<a href='?idp=-16'>Wciśnij aby przejść do Koszyka</a><br></br>";
	}
	
    $query = "SELECT id, matka, nazwa FROM category_list ORDER BY id ASC LIMIT 100";	// Wybierz 100 elementów z category_list
    $result = mysqli_query($GLOBALS['mysqli'], $query);									// wykonaj zapytanie pierwsze
	
	$query_prod = "SELECT id, title, amount, category, picture, availability FROM product_list ORDER BY id ASC LIMIT 100";		// Wybierz 100 elementów z product_list
    $result_prod = mysqli_query($GLOBALS['mysqli'], $query_prod);					// wykonaj zapytanie drugie

	$names = array();																// przechowanie nazw kategorii
	$parents = array();																// kategorie matki
	$children = array();															// podkategorie
		
	$names_prod = array();															// nazwy produktow
	$pics_prod = array();															// zdjecia produktow
	$children_prod = array();														// przechowanie dzieci podkategorii (produktow)
	$amount_prod = array();
	$avail_prod = array();
	
    while($row = mysqli_fetch_array($result)) {
		$names[$row['id']] = $row['nazwa'];											// dodanie nazwy
		
		if ($row['matka'] == 0) {
			$parents[] = $row['id'];												// jesli matka = 0 dodaj id do rodzicow
		}
		
		else {
			$children[$row['matka']][] = $row['id'];								// jesli matka != 0 dodaj id do dzieci
			
			$result_prod = mysqli_query($GLOBALS['mysqli'], $query_prod);			// reset wartości dla pętli while
			while($row_prod = mysqli_fetch_array($result_prod)) {					// dodaj kazdy produkt do odpowiedniej podkategorii
				if ($row['id'] == $row_prod['category']) {							// czy id się zgadza z kategorią
					$names_prod[$row_prod['id']] = $row_prod['title'];					// przechowaj nazwe
					$amount_prod[$row_prod['id']] = $row_prod['amount'];				// przechowaj ilosc
					$pics_prod[$row_prod['id']] = $row_prod['picture'];					// przechowaj zdjecie
					$avail_prod[$row_prod['id']] = $row_prod['availability'];
					$children_prod[$row['id']][] = $row_prod['id'];						// dodaj produkt do listy dzieci podkategorii do pozniejszego wyswietlenia
				}
			}
		}
    }
	foreach ($parents as $parent_id) {
		echo "<br></br><div style='color:brown;font-size:60px;'>".$names[$parent_id]."</div>";													// wyswietl kategorie
		
		if (array_key_exists($parent_id, $children)) {
			
			foreach($children[$parent_id] as $child_id) {
				
				echo "<div style='color:cyan;font-size:40px;'>".$names[$child_id]."</div>";								// wyswietl podkategorie
				if (array_key_exists($child_id, $children_prod)) {
					
					foreach($children_prod[$child_id] as $product_id) {				// wyswietl dane produktu
						if ($avail_prod[$product_id] == 1) {
							echo '
							<div>
							<br></br>
							'.$names_prod[$product_id].'
							</div>
							<div class="imgbox">
							<img src='.$pics_prod[$product_id].' alt="CSP">
							</div>
							<div>
							ilosc = 
							'.$amount_prod[$product_id];
							
							if (isset($_SESSION['id_'.$product_id]['ilosc'])) {										// Czy ustawiono ilość?
								if ($_SESSION['id_'.$product_id]['ilosc'] == $amount_prod[$product_id]) {			// Czy ilość jest równa zawartości w magazynie?
									echo "<br>Brak większej ilości produktu";
								}
								else {
									echo '
									</div>
									<td><a href="?idp=-15&add_produkt='.$product_id.'">Add To Cart</a></td>
									';
								}
							}
							
							else {
								echo '
								</div>
								<td><a href="?idp=-15&add_produkt='.$product_id.'">Add To Cart</a></td>
								';
							}
						}
					}
				}
				echo "<br></br>";
			}
		}
		
		echo "<br></br>";
	}
	
	if (isset($_GET['add_produkt'])) {
		echo AddToCart($_GET['add_produkt']);
	}
	
}

//
// funkcja ShowCart wyświetla zawartość koszyka, daje opcje operacji na nim
//
	
function ShowCart() {
	echo "<h2> Koszyk </h2>";
	echo "<a href='?idp=-15'>Wciśnij aby przejść do Sklepu</a><br></br>";
	
	$final = 0;
	
	$query_clear = "SELECT id, title, price_netto, vat, amount FROM product_list ORDER BY id ASC LIMIT 100";
	$result_clear = mysqli_query($GLOBALS['mysqli'], $query_clear);
	while ($row = mysqli_fetch_array($result_clear)) {
		if (isset($_SESSION['id_'.$row['id']]['id_prod'])) {
			$total = ($row['price_netto'] + $row['price_netto'] * 0.01 * $row['vat']) * $_SESSION['id_'.$row['id']]['ilosc'];		// Wyświetl produkty w koszyku
			$final += $total;
			echo "Produkt: ".$row['title'];
			echo "<br>";
			echo "Ilość: ".$_SESSION['id_'.$row['id']]['ilosc'];
			echo "<br>";
			echo "Cena: ".$total;
			echo "<br>";
			

			if ($_SESSION['id_'.$row['id']]['ilosc'] == $row['amount']) {															// Czy ilość jest równa limitowi zawartości w magazynie
				echo "Brak większej ilości produktu<br>";
			}
			else {	
				echo '<td><a href="?idp=-16&add_one='.$_SESSION['id_'.$row['id']]['id_prod'].'">Add One</a></td><br>';
			}
			
			echo '<td><a href="?idp=-16&remove_one='.$_SESSION['id_'.$row['id']]['id_prod'].'">Remove One</a></td>';
			echo "<br></br>";
			
		}
	}

	
	if ($final == 0) {
		echo "<p style='font-size:30px;'>Koszyk Jest pusty</p>";
	}
	else {
		echo "<p style='font-size:30px;'></br>Pełna Kwota: ".$final."</p>";
	}
	echo '<br></br><td><a href="?idp=-16&checkout=1">Checkout Cart</a></td><br></br>';
	echo '<br></br><td><a href="?idp=-16&clear=1">Clear Cart</a></td>';
	
	if (isset($_GET['checkout'])) {																									// Wykonaj Checkout
		echo CheckoutCart();
	}
	if (isset($_GET['clear'])) {
		echo ClearCart();																											// Wyczyść koszyk oraz count
	}
	if (isset($_GET['add_one'])) {
		echo AddOne($_GET['add_one']);																								// Dodaj ilość 1 do produktu
	}
	if (isset($_GET['remove_one'])) {
		echo RemoveOne($_GET['remove_one']);																						// Usun 1 z produktu, ewentualnie cały produkt
	}
}


//
// funkcja AddToCart dodaje produkt do koszyka
//

function AddToCart($id_produktu) {

	if (isset($_SESSION['id_'.$id_produktu]['id_prod'])) {																			// Czy już istnieje produkt?
		$_SESSION['id_'.$id_produktu]['ilosc']++;
	}
	else {
		$_SESSION['id_'.$id_produktu]['id_prod'] = $id_produktu;
		$_SESSION['id_'.$id_produktu]['ilosc'] = 1;
	}
	
	if (!isset($_SESSION['count'])) {																								// Zwiększ ogólny count produktów w koszyku
		$_SESSION['count'] = 1;
	}
	else {
		$_SESSION['count']++;
	}
	
	unset($_GET['add_produkt']);																									// Reset Add Check
	header("Location: http://localhost/moj_projekt/index.php?idp=-15");																// Powrót do Panelu Admina Produktów
	die();
}

function AddOne($id_produktu) {

	if (isset($_SESSION['id_'.$id_produktu]['id_prod'])) {
		$_SESSION['id_'.$id_produktu]['ilosc']++;
	}
	else {
		$_SESSION['id_'.$id_produktu]['id_prod'] = $id_produktu;
		$_SESSION['id_'.$id_produktu]['ilosc'] = 1;
	}
	
	$_SESSION['count']++;
	
	unset($_GET['add_one']);																										// Reset Add Check
	header("Location: http://localhost/moj_projekt/index.php?idp=-16");																// Powrót do Panelu Admina Produktów
	die();
}

function RemoveOne($id_produktu) {

	if ($_SESSION['id_'.$id_produktu]['ilosc'] == 1) {																				// If last amount, remove entirely
		unset($_SESSION['id_'.$id_produktu]['id_prod']);
		unset($_SESSION['id_'.$id_produktu]['ilosc']);
		unset($_SESSION['id_'.$id_produktu]);
	}
	else {
		$_SESSION['id_'.$id_produktu]['ilosc']--;																					// Deduce by one
	}
	
	if ($_SESSION['count'] == 1) {
		unset($_SESSION['count']);
	}
	else {
		$_SESSION['count']--;																										// Deduce count by one
	}
	
	unset($_GET['remove_one']);																										// Reset Remove Check
	header("Location: http://localhost/moj_projekt/index.php?idp=-16");																// Powrót do Panelu Admina Produktów
	die();
}

function ClearCart() {																												// Wyczyść koszyk
	$query_clear = "SELECT id FROM product_list ORDER BY id ASC LIMIT 100";
    $result_clear = mysqli_query($GLOBALS['mysqli'], $query_clear);
	while ($row = mysqli_fetch_array($result_clear)) {
		unset($_SESSION['id_'.$row['id']]['id_prod']);
		unset($_SESSION['id_'.$row['id']]['ilosc']);
		unset($_SESSION['id_'.$row['id']]);
	}
	unset($_SESSION['count']);																																// Zresetuj count
	
	unset($_GET['clear']);																																	// Reset Clear Check
	header("Location: http://localhost/moj_projekt/index.php?idp=-16");																						// Powrót do Panelu Admina Produktów
	die();
}

function CheckoutCart() {																																	// Wyczyść koszyk oraz zmień bazę danych
	$query_clear = "SELECT id, amount FROM product_list ORDER BY id ASC LIMIT 100";
    $result_clear = mysqli_query($GLOBALS['mysqli'], $query_clear);
	while ($row = mysqli_fetch_array($result_clear)) {
		$amount = $row['amount']-$_SESSION['id_'.$row['id']]['ilosc'];																						// ilość produktu po zakupie
		if ($amount < 0) {																																	// Czy brakuje towaru po zakupie?
			$query_count = "UPDATE product_list SET amount = '".$amount."', availability = 0 WHERE product_list.id = '".$row['id']."' LIMIT 1";
		}
		else {
			$query_count = "UPDATE product_list SET amount = '".$amount."' WHERE product_list.id = '".$row['id']."' LIMIT 1";
		}
		mysqli_query($GLOBALS['mysqli'], $query_count);
		unset($_SESSION['id_'.$row['id']]['id_prod']);
		unset($_SESSION['id_'.$row['id']]['ilosc']);
		unset($_SESSION['id_'.$row['id']]);
	}
	unset($_SESSION['count']);																																// Zresetuj count
	
	unset($_GET['clear']);																																	// Reset Clear Check
	header("Location: http://localhost/moj_projekt/index.php?idp=-16");																						// Powrót do Panelu Admina Produktów
	die();
}
?>
