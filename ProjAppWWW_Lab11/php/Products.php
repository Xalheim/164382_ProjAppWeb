<?php
//
// funkcja 'PokazProdukty' Jest odpowiedzialna za wyświetlenie formularza loginu do panelu administracyjnego, sprawdzenie danych poprzez CheckLoginCred, oraz wyświetlenie tabeli z Produktami.
//

function PokazProdukty() {
	$status_login = CheckLogin();												// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Panel Produktów </h3>";
		echo "<a href='?idp=-1'>Powrót do Panelu Admina</a><br></br>";
		echo "<a href='?idp=-7'>Wciśnij aby przejść do tabeli Kategorii</a><br></br>";
		echo ListaProduktow();
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
// funkcja 'ListaProduktow' wyświetla tabelę z elementów w product_list
//

function ListaProduktow() {
    $query = "SELECT id, title, amount FROM product_list ORDER BY id ASC LIMIT 100";	// Wybierz 100 elementów z category_list
    $result = mysqli_query($GLOBALS['mysqli'], $query);									// wykonaj zapytanie
	
	echo '<table>
		<tr style="color: coral">
			<th>ID</th>
			<th>Nazwa</th>
			<th>Ilość</th>
		</tr>';
    while($row = mysqli_fetch_array($result)) {											// wyświetl każdą z wartości
        echo'<tr>
				<td style="color: gold">'.$row['id'].'</td>
				<td style="color: #FFFFFF">'.$row['title'].'</td>
				<td style="color: #FFFFFF">'.$row['amount'].'</td>
			</tr>';
    }
	echo '</table><br></br>';
	
	echo '<a href="?idp=-12">Create New Product</a><br></br>';
	echo '<a href="?idp=-13">Edit Product</a><br></br>';
	echo '<a href="?idp=-14">Delete Product</a><br></br>';
}

//
//	funkcja 'AddProduct' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do utworzenia nowego produktu w bazie danych.
//

function AddProduct() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {																// Czy pomyślnie się zalogowano?
		echo "<h3> Strona dodawania Produktu</h3>";
		if (isset($_POST['create_title']) && isset($_POST['create_desc']) && isset($_POST['create_creationdate']) && isset($_POST['create_moddate']) && isset($_POST['create_expdate']) && isset($_POST['create_netto']) && isset($_POST['create_vat']) &&
					isset($_POST['create_amount']) && isset($_POST['create_avail']) && isset($_POST['create_cat']) && isset($_POST['create_gab']) && isset($_POST['create_pic'])) {																					// czy zostały podane wszystkie dane?
			$_POST['create_id'] = htmlspecialchars($_POST['create_id']);
			$_POST['create_mother'] = htmlspecialchars($_POST['create_mother']);
			$query = "INSERT INTO product_list (title, description, creation_date, modification_date, expiration_date, price_netto, vat, amount, availability, category, gabaryt, picture)
					  VALUES ('".$_POST['create_title']."', '".$_POST['create_desc']."', '".$_POST['create_creationdate']."', '".$_POST['create_moddate']."', '".$_POST['create_expdate']."', '".$_POST['create_netto']."', '".$_POST['create_vat']."',
							  '".$_POST['create_amount']."', '".$_POST['create_avail']."', '".$_POST['create_cat']."', '".$_POST['create_gab']."', '".$_POST['create_pic']."') LIMIT 1";																			// Wprowadź nowe dane do tablicy product_list
			mysqli_query($GLOBALS['mysqli'], $query);																												// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/index.php?idp=-11");																						// Powrót do Panelu Admina Produktów
			die();
		}
		else {
			echo DodajProdukt();												// Pobierz dane do Dodania
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'EditProduct' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do edycji produktu w bazie danych.
//

function EditProduct() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Strona edycji Produktu </h3>";
		if (isset($_POST['edit_id']) && isset($_POST['edit_title']) && isset($_POST['edit_desc']) && isset($_POST['edit_creationdate']) && isset($_POST['edit_moddate']) && isset($_POST['edit_expdate']) && isset($_POST['edit_netto']) && isset($_POST['edit_vat']) &&
					isset($_POST['edit_amount']) && isset($_POST['edit_avail']) && isset($_POST['edit_cat']) && isset($_POST['edit_gab']) && isset($_POST['edit_pic'])) {																							// czy zostały podane wszystkie dane?
			$query = "UPDATE product_list SET title = '".$_POST['edit_title']."', description = '".$_POST['edit_desc']."', creation_date = '".$_POST['edit_creationdate']."', modification_date = '".$_POST['edit_moddate']."', expiration_date = '".$_POST['edit_expdate']."',
							  price_netto = '".$_POST['edit_netto']."', vat = '".$_POST['edit_vat']."', amount = '".$_POST['edit_amount']."', availability = '".$_POST['edit_avail']."', category = '".$_POST['edit_cat']."', gabaryt = '".$_POST['edit_gab']."',
							  picture= '".$_POST['edit_pic']."'
					  WHERE product_list.id = '".$_POST['edit_id']."' LIMIT 1";			// Edytuj dane w product_list tabeli o odpowiednim id
			mysqli_query($GLOBALS['mysqli'], $query);							// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/index.php?idp=-11");	// Powrót do Panelu Admina Produktów
			die();
		}
		else {
			echo EdytujProdukt();												// Pobierz dane do edycji
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'DeleteProduct' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania id strony.
//  ID będzie wykorzystane do usunięcia produktu z bazy danych.
//

function DeleteProduct() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Strona usuwania Produktu </h3>";
		if (isset($_POST['delete_id'])) {
			$id_delete = htmlspecialchars($_POST['delete_id']);
			$query = "DELETE FROM product_list WHERE id='".$id_delete."' LIMIT 1";		// Usuń dla ID
			mysqli_query($GLOBALS['mysqli'], $query);									// Wykonaj query 
			header("Location: http://localhost/moj_projekt/index.php?idp=-11");	// Powrót do Panelu Admina Produktów
			die();
		}
		else {
			echo UsunProdukt();												// Pobierz ID do usunięcia
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
// funkcja 'DodajProdukt' jest odpowiedzialna za wyświetlenie formularza, który pobierze wszystkie wymagane wartości, a następnie przekazanie go do funkcji wyższej
//


function DodajProdukt() {
    $wynik='
    <div class="tworzenieproduktu">
        <div class="tworzenieproduktu">
            <form method="post" name="ProductCreationForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="tworzenieproduktu">
                    <tr><td class="log4_t">[title]</td><td><input type="text" name="create_title" class="tworzenieproduktu" /></td></tr>
					<tr><td class="log4_t">[description]</td><td><input type="text" name="create_desc" class="tworzenieproduktu" /></td></tr>
                    <tr><td class="log4_t">[creation_date]</td><td><input type="text" name="create_creationdate" class="tworzenieproduktu" /></td></tr>
                    <tr><td class="log4_t">[modification_date]</td><td><input type="text" name="create_moddate" class="tworzenieproduktu" /></td></tr>
					<tr><td class="log4_t">[expiration_date]</td><td><input type="text" name="create_expdate" class="tworzenieproduktu" /></td></tr>
		            <tr><td class="log4_t">[price_netto]</td><td><input type="text" name="create_netto" class="tworzenieproduktu" /></td></tr>
                    <tr><td class="log4_t">[vat]</td><td><input type="text" name="create_vat" class="tworzenieproduktu" /></td></tr>
					<tr><td class="log4_t">[amount]</td><td><input type="text" name="create_amount" class="tworzenieproduktu" /></td></tr>
					<tr><td class="log4_t">[availability]</td><td><input type="text" name="create_avail" class="tworzenieproduktu" /></td></tr>
                    <tr><td class="log4_t">[category]</td><td><input type="text" name="create_cat" class="tworzenieproduktu" /></td></tr>
					<tr><td class="log4_t">[gabaryt]</td><td><input type="text" name="create_gab" class="tworzenieproduktu" /></td></tr>
                    <tr><td class="log4_t">[picture]</td><td><input type="text" name="create_pic" class="tworzenieproduktu" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="tworzenieproduktu" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
	return $wynik;
}

//
// funkcja 'EdytujProdukt' jest odpowiedzialna za wyświetlenie formularza, który pobierze wszystkie wymagane wartości, a następnie przekazanie go do funkcji wyższej
//

function EdytujProdukt() {
    $wynik = '
    <div class="edycjaproduktu">
        <div class="edycjaproduktu">
            <form method="post" name="ProductEditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="edycjaproduktu">
				    <tr><td class="log4_t">[id]</td><td><input type="text" name="edit_id" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[title]</td><td><input type="text" name="edit_title" class="edycjaproduktu" /></td></tr>
					<tr><td class="log4_t">[description]</td><td><input type="text" name="edit_desc" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[creation_date]</td><td><input type="text" name="edit_creationdate" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[modification_date]</td><td><input type="text" name="edit_moddate" class="edycjaproduktu" /></td></tr>
					<tr><td class="log4_t">[expiration_date]</td><td><input type="text" name="edit_expdate" class="edycjaproduktu" /></td></tr>
		            <tr><td class="log4_t">[price_netto]</td><td><input type="text" name="edit_netto" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[vat]</td><td><input type="text" name="edit_vat" class="edycjaproduktu" /></td></tr>
					<tr><td class="log4_t">[amount]</td><td><input type="text" name="edit_amount" class="edycjaproduktu" /></td></tr>
					<tr><td class="log4_t">[availability]</td><td><input type="text" name="edit_avail" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[category]</td><td><input type="text" name="edit_cat" class="edycjaproduktu" /></td></tr>
					<tr><td class="log4_t">[gabaryt]</td><td><input type="text" name="edit_gab" class="edycjaproduktu" /></td></tr>
                    <tr><td class="log4_t">[picture]</td><td><input type="text" name="edit_pic" class="edycjaproduktu" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="edycjaproduktu" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

//
// funkcja 'UsunProdukt' jest odpowiedzialna za usunięcie tabeli w bazie danych produktów o podanym id
//

function UsunProdukt() {
    $wynik = '
    <div class="usuniecieproduktu">
        <div class="usuniecieproduktu">
            <form method="post" name="ProductDeleteForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="usuniecieproduktu">
                    <tr><td class="log4_t">[id]</td><td><input type="text" name="delete_id" class="usuniecieproduktu" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="usuniecieproduktu" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

?>