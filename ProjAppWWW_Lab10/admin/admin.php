<?php

include 'cfg.php';

//
// funkcja 'FormularzLogowania' jest odpowiedzialna za wyświetlenie formularza, który pobierze login oraz hasło, a następnie przekazanie go do funkcji wyższej
//

function FormularzLogowania() {
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="logowanie">
                    <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                    <tr><td>$nbsp; </td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';

    return $wynik;
}

//
// funkcja 'CheckLogin' sprawdza czy istnieje sesja/podano dane, a następnie wywołuje CheckLoginCred w celu sprawdzenia poprawności danych
//

function CheckLogin() {
	if (isset($_SESSION['login_email']) && isset($_SESSION['login_pass'])) {	// Czy sesja ma zapisane już login i hasło?
		$_POST['login_email'] = $_SESSION['login_email'];
		$_POST['login_pass'] = $_SESSION['login_pass'];
		$status_login = CheckLoginCred('test@admin.com', 'admin');				// Sprawdzenie danych logowania
	}
	else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {		// Czy formularz przekazał login i hasło?
		$status_login = CheckLoginCred('test@admin.com', 'admin');				// Sprawdzenie danych logowania
	}
	else {
		$status_login = 0;														// Nie ma danych logowania
	}
	return $status_login;
}

//
// funkcja 'CheckLoginCred' jest odpowiedzialna za sprawdzenie, czy podany login oraz hasło są zgodne https://www.w3schools.com/php/php_form_url_email.asp
// Dla hasła sprawdzić czy nie ma apostrofów oraz czy długość się zgadza
//

function CheckLoginCred($login, $pass) {
    if ($login == $_POST['login_email'] && $pass == $_POST['login_pass']) {
		$_SESSION['login_email'] = $_POST['login_email'];
		$_SESSION['login_pass'] = $_POST['login_pass'];
        return 1;													// pomyślne sprawdzenie, zwraca 1
    }
    else {
		echo "Logowanie sie nie powiodlo.";
        return 0;													// niepoprawne dane, zwraca 0
    }
}

//
// funkcja 'LoginAdmin' Jest odpowiedzialna za wyświetlenie formularza loginu do panelu administracyjnego, sprawdzenie danych poprzez CheckLoginCred, oraz wyświetlenie zawartości Panelu Admin.
//

function LoginAdmin() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h1> Lista Stron </h1>";
		echo "<a href='?idp=-7'>Wciśnij aby przejść do tabeli Kategorii</a><br></br>";
		echo ListaPodstron();
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'CreatePage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do utworzenia nowej strony w bazie danych.
//

function CreatePage() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {																// Czy pomyślnie się zalogowano?
		echo "<h1> Strona dodawania</h1>";
		if (isset($_POST['create_title']) && isset($_POST['create_content']) && isset($_POST['create_active']) && isset($_POST['create_alias'])) {		// czy zostały podane wszystkie dane?
			$query = "INSERT INTO page_list (page_title, page_content, status, alias)
					  VALUES ('".$_POST['create_title']."', '".$_POST['create_content']."', '".$_POST['create_active']."', '".$_POST['create_alias']."') LIMIT 1";	// Wprowadź nowe dane do tablicy page_list
			mysqli_query($GLOBALS['mysqli'], $query);																												// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/164382.php?idp=-1");																						// Powrót do Panelu Admina
			die();
		}
		else {
			echo DodajNowaPodstrone();											// Pobierz dane do Dodania
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'EditPage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
//  Dane będą wykorzystane do edycji strony w bazie danych.
//

function EditPage() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h1> Strona edycji </h1>";
		if (isset($_POST['edit_title']) && isset($_POST['edit_content']) && isset($_POST['edit_active']) && isset($_POST['edit_alias'])) {
			$query = "UPDATE page_list SET page_title = '".$_POST['edit_title']."', page_content = '".$_POST['edit_content']."', status = '".$_POST['edit_active']."', alias = '".$_POST['edit_alias']."'
					  WHERE page_list.id = '".$_GET['ide']."' LIMIT 1";			// Edytuj dane w page_list tabeli o odpowiednim id
			mysqli_query($GLOBALS['mysqli'], $query);							// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/164382.php?idp=-1");	// Powrót do Panelu Admina
			die();
		}
		else {
			echo EdytujPodstrone();												// Pobierz dane do edycji
		}
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
//	funkcja 'DeletePage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania id strony.
//  ID będzie wykorzystane do usunięcia strony z bazy danych.
//

function DeletePage() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo UsunPodstrone();													// Usun tabelę z page_list o odpowiednim id
		header("Location: http://localhost/moj_projekt/164382.php?idp=-1");		// Powrót do Panelu Admina
		die();
	}
	else {
		echo FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
	}
}

//
// funkcja 'ListaPodstron' wyświetla tabelę z elementów w page_list
//

function ListaPodstron() {
    $query = "SELECT id, page_title FROM page_list ORDER BY id ASC LIMIT 100";	// Wybierz 100 elementów z page_list
    $result = mysqli_query($GLOBALS['mysqli'], $query);							// wykonaj zapytanie

	echo '<table>
			<tr style="color: coral">
				<th>ID Strony</th>
				<th>Tytuł Strony</th>
				<th>Edytuj</th>
				<th>Usuń</th>
			</tr>';
    while($row = mysqli_fetch_array($result)) {									// wyświetl każdą z wartości oraz guziki do edycji/usuwania
        echo'<tr>
				<td style="color: gold">'.$row['id'].'</td>
				<td style="color: #FFFFFF">'.$row['page_title'].'</td>
				<td><a href="?idp=-3&ide='.$row['id'].'">Edit</a></td>
				<td><a href="?idp=-4&idd='.$row['id'].'">Delete</a></td>
			</tr>';
    }
	echo '</table>';
	echo '<a href="?idp=-2">Create New Page</a>';								// Przejdź do zakładki tworzenia strony
}

//
// funkcja 'EdytujPodstrone' jest odpowiedzialna za wyświetlenie formularza, który pobierze tytuł, zawartość strony, aktywność oraz alias, a następnie przekazanie go do funkcji wyższej
//

function EdytujPodstrone() {
    $wynik = '
    <div class="edycjapodstrony">
        <div class="edycjapodstrony">
            <form method="post" name="PageEditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="edycjapodstrony">
                    <tr><td class="log4_t">[title]</td><td><input type="text" name="edit_title" class="edycjapodstrony" /></td></tr>
                    <tr><td class="log4_t">[zawartosc]</td><td><input type="textarea" name="edit_content" class="edycjapodstrony" /></td></tr>
					<tr><td class="log4_t">[aktywnosc]</td><td><input type="checkbox" name="edit_active" class="edycjapodstrony" /></td></tr>
					<tr><td class="log4_t">[alias]</td><td><input type="textarea" name="edit_alias" class="edycjapodstrony" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="edycjapodstrony" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

//
// funkcja 'DodajNowaPodstrone' jest odpowiedzialna za wyświetlenie formularza, który pobierze tytuł, zawartość strony, aktywność oraz alias, a następnie przekazanie go do funkcji wyższej
//

function DodajNowaPodstrone() {
    $wynik='
    <div class="tworzeniepodstrony">
        <div class="tworzeniepodstrony">
            <form method="post" name="PageCreationForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="tworzeniepodstrony">
                    <tr><td class="log4_t">[title]</td><td><input type="text" name="create_title" class="tworzeniepodstrony" /></td></tr>
                    <tr><td class="log4_t">[zawartosc]</td><td><input type="textarea" name="create_content" class="tworzeniepodstrony" /></td></tr>
					<tr><td class="log4_t">[aktywnosc]</td><td><input type="checkbox" name="create_active" class="tworzeniepodstrony" /></td></tr>
					<tr><td class="log4_t">[alias]</td><td><input type="text" name="create_alias" class="tworzeniepodstrony" /></td></tr>
                    <tr><td> </td><td><input type="submit" name="x1_submit" class="tworzeniepodstrony" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
	return $wynik;
}

//
// funkcja 'UsunPodstrone' jest odpowiedzialna za usunięcie tabeli w bazie danych o podanym id
//

function UsunPodstrone() {
	$query = "DELETE FROM page_list WHERE id='".$_GET['idd']."' LIMIT 1";		// Pobierz ID oraz usuń
	mysqli_query($GLOBALS['mysqli'], $query);									// Wykonaj query 
}



//
// funkcja 'PokazKategorie' Jest odpowiedzialna za wyświetlenie formularza loginu do panelu administracyjnego, sprawdzenie danych poprzez CheckLoginCred, oraz wyświetlenie tabeli z Kategoriami.
//

function PokazKategorie() {
	$status_login = CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

	if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
		echo "<h1> Panel Kategorii </h1>";
		echo "<a href='?idp=-1'>Wciśnij aby przejść do tabeli page_list</a><br></br><br></br>";
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
    while($row = mysqli_fetch_array($result)) {											// wyświetl każdą z wartości
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
		echo "<h1> Strona dodawania kategorii</h1>";
		if (isset($_POST['create_id']) && isset($_POST['create_mother']) && isset($_POST['create_name'])) {		// czy zostały podane wszystkie dane?
			$_POST['create_id'] = htmlspecialchars($_POST['create_id']);
			$_POST['create_mother'] = htmlspecialchars($_POST['create_mother']);
			$query = "INSERT INTO category_list (id, matka, nazwa)
					  VALUES ('".$_POST['create_id']."', '".$_POST['create_mother']."', '".$_POST['create_name']."') LIMIT 1";	// Wprowadź nowe dane do tablicy category_list
			mysqli_query($GLOBALS['mysqli'], $query);																												// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/164382.php?idp=-7");																						// Powrót do Panelu Admina Kategorii
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
		echo "<h1> Strona edycji kategorii </h1>";
		if (isset($_POST['edit_id']) && isset($_POST['edit_mother']) && isset($_POST['edit_name'])) {
			$_POST['edit_id'] = htmlspecialchars($_POST['edit_id']);
			$_POST['edit_mother'] = htmlspecialchars($_POST['edit_mother']);
			$query = "UPDATE category_list SET id = '".$_POST['edit_id']."', matka = '".$_POST['edit_mother']."', nazwa = '".$_POST['edit_name']."'
					  WHERE category_list.id = '".$_POST['edit_id']."' LIMIT 1";			// Edytuj dane w category_list tabeli o odpowiednim id
			mysqli_query($GLOBALS['mysqli'], $query);							// Wykonaj query do bazy danych
			header("Location: http://localhost/moj_projekt/164382.php?idp=-7");	// Powrót do Panelu Admina Kategorii
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
		echo "<h1> Strona usuwania kategorii </h1>";
		if (isset($_POST['delete_id'])) {
			$id_delete = htmlspecialchars($_POST['delete_id']);
			$query = "DELETE FROM category_list WHERE id='".$id_delete."' LIMIT 1";		// Usuń dla ID
			mysqli_query($GLOBALS['mysqli'], $query);									// Wykonaj query 
			header("Location: http://localhost/moj_projekt/164382.php?idp=-7");	// Powrót do Panelu Admina
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
// funkcja 'DodajKategorie' jest odpowiedzialna za wyświetlenie formularza, który pobierze id, matkę oraz nazwę, a następnie przekazanie go do funkcji wyższej
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
// funkcja 'EdytujKategorie' jest odpowiedzialna za wyświetlenie formularza, który pobierze id, matkę oraz nazwę, a następnie przekazanie go do funkcji wyższej
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