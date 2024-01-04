<?php
//
// funkcja 'PokazKategorie' Jest odpowiedzialna za wyświetlenie formularza loginu do panelu administracyjnego, sprawdzenie danych poprzez CheckLoginCred, oraz wyświetlenie tabeli z Kategoriami.
//

function PokazKategorie() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Panel Kategorii </h3>";
		echo "<a href='?idp=-1'>Powrót do Panelu Admina</a><br></br>";
		echo "<a href='?idp=-11'>Wciśnij aby przejść do tabeli Produktów</a><br></br>";
		echo ListaKategorii();
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
// funkcja 'ListaKategorii' wyświetla tabelę z elementów w category_list
//

function ListaKategorii() {
    $query = "SELECT id, matka, nazwa FROM category_list ORDER BY id ASC LIMIT 100";	// Wybierz 100 elementów z category_list
    $result = mysqli_query($GLOBALS['mysqli'], $query);									// wykonaj zapytanie
	
	echo '<table>
		<tr style="color: coral">
			<th>ID</th>
			<th>Matka</th>
			<th>Nazwa</th>
		</tr>';
    while($row = mysqli_fetch_array($result)) {									// wyświetl każdą z wartości oraz guziki do edycji/usuwania
        echo'<tr>
				<td style="color: gold">'.$row['id'].'</td>
				<td style="color: #FFFFFF">'.$row['matka'].'</td>
				<td style="color: #FFFFFF">'.$row['nazwa'].'</td>
			</tr>';
    }
	echo '</table><br></br>';
	
    $result = mysqli_query($GLOBALS['mysqli'], $query);									// wykonaj zapytanie ponownie
	
	$names = array();
	$parents = array();
	$children = array();

    while($row = mysqli_fetch_array($result)) {
		$names[$row['id']] = $row['nazwa'];
		if ($row['matka'] == 0) {
			$parents[] = $row['id'];
		}
		else {
			if (!array_key_exists($row['matka'], $children)) {
				
			}
			$children[$row['matka']][] = $row['id'];
		}
    }
	
	foreach ($parents as $parent_id) {
		echo $names[$parent_id];
		if (array_key_exists($parent_id, $children)) {
			foreach($children[$parent_id] as $child_id) {
				echo "<br></br>|> ".$names[$child_id];
			}
		}
		echo "<br></br>";
	}
	echo '</table><br></br>';
	echo '<a href="?idp=-8">Create New Category</a><br></br>';
	echo '<a href="?idp=-9">Edit Category</a><br></br>';
	echo '<a href="?idp=-10">Delete Category</a><br></br>';
}

//
//	funkcja 'AddCategory' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do utworzenia nowej kategorii w bazie danych.
//

function AddCategory() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {																// Czy pomyślnie się zalogowano?
		echo "<h3> Strona dodawania kategorii</h3>";
		if (isset($_POST['create_id']) && isset($_POST['create_mother']) && isset($_POST['create_name'])) {		// czy zostały podane wszystkie dane?
			$_POST['create_id'] = htmlspecialchars($_POST['create_id']);
			$_POST['create_mother'] = htmlspecialchars($_POST['create_mother']);
			$query = "INSERT INTO category_list (id, matka, nazwa)
					  VALUES ('".$_POST['create_id']."', '".$_POST['create_mother']."', '".$_POST['create_name']."') LIMIT 1";	// Wprowadź nowe dane do tablicy category_list
			mysqli_query($GLOBALS['mysqli'], $query);																												// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/index.php?idp=-7");																						// Powrót do Panelu Admina Kategorii
			die();
		}
		else {
			echo DodajKategorie();												// Pobierz dane do Dodania
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'EditCategory' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do edycji kategorii w bazie danych.
//

function EditCategory() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Strona edycji kategorii </h3>";
		if (isset($_POST['edit_id']) && isset($_POST['edit_mother']) && isset($_POST['edit_name'])) {
			$_POST['edit_id'] = htmlspecialchars($_POST['edit_id']);
			$_POST['edit_mother'] = htmlspecialchars($_POST['edit_mother']);
			$query = "UPDATE category_list SET id = '".$_POST['edit_id']."', matka = '".$_POST['edit_mother']."', nazwa = '".$_POST['edit_name']."'
					  WHERE category_list.id = '".$_POST['edit_id']."' LIMIT 1";			// Edytuj dane w category_list tabeli o odpowiednim id
			mysqli_query($GLOBALS['mysqli'], $query);							// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/index.php?idp=-7");	// Powrót do Panelu Admina Kategorii
			die();
		}
		else {
			echo EdytujKategorie();												// Pobierz dane do edycji
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'DeleteCategory' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania id strony.
//  ID będzie wykorzystane do usunięcia kategorii z bazy danych.
//

function DeleteCategory() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h3> Strona usuwania kategorii </h3>";
		if (isset($_POST['delete_id'])) {
			$id_delete = htmlspecialchars($_POST['delete_id']);
			$query = "DELETE FROM category_list WHERE id='".$id_delete."' LIMIT 1";		// Usuń dla ID
			mysqli_query($GLOBALS['mysqli'], $query);									// Wykonaj query 
			header("Location: http://localhost/moj_projekt/index.php?idp=-7");	// Powrót do Panelu Admina
			die();
		}
		else {
			echo UsunKategorie();												// Pobierz ID do usunięcia
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
// funkcja 'DodajKategorie' jest odpowiedzialna za wyświetlenie formularza, który pobierze matkę oraz , a następnie przekazanie go do funkcji wyższej
//


function DodajKategorie() {
    $wynik='
    <div class="tworzeniekategorii">
        <div class="tworzeniekategorii">
            <form method="post" name="CategoryCreationForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="tworzeniekategorii">
                    <tr><td class="log4_t">[id]</td><td><input type="text" name="create_id" class="tworzeniekategorii" /></td></tr>
                    <tr><td class="log4_t">[matka]</td><td><input type="text" name="create_mother" class="tworzeniekategorii" /></td></tr>
					<tr><td class="log4_t">[nazwa]</td><td><input type="text" name="create_name" class="tworzeniekategorii" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="tworzeniekategorii" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
	return $wynik;
}

//
// funkcja 'EdytujKategorie' jest odpowiedzialna za wyświetlenie formularza, który pobierze tytuł, zawartość kategorii, aktywność oraz alias, a następnie przekazanie go do funkcji wyższej
//

function EdytujKategorie() {
    $wynik = '
    <div class="edycjakategorii">
        <div class="edycjakategorii">
            <form method="post" name="CategoryEditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="edycjakategorii">
                    <tr><td class="log4_t">[id]</td><td><input type="text" name="edit_id" class="edycjakategorii" /></td></tr>
                    <tr><td class="log4_t">[matka]</td><td><input type="text" name="edit_mother" class="edycjakategorii" /></td></tr>
					<tr><td class="log4_t">[nazwa]</td><td><input type="text" name="edit_name" class="edycjakategorii" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="edycjakategorii" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

//
// funkcja 'UsunKategorie' jest odpowiedzialna za usunięcie tabeli w bazie danych kategorii o podanym id
//

function UsunKategorie() {
    $wynik = '
    <div class="usunieciekategorii">
        <div class="usunieciekategorii">
            <form method="post" name="CategoryDeleteForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="usunieciekategorii">
                    <tr><td class="log4_t">[id]</td><td><input type="text" name="delete_id" class="usunieciekategorii" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="usunieciekategorii" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

?>