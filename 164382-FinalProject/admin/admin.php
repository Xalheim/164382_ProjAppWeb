<?php

include 'cfg.php';

class Admin {
	//
	// funkcja 'FormularzLogowania' jest odpowiedzialna za wyświetlenie formularza, który pobierze login oraz hasło, a następnie przekazanie go do funkcji wyższej
	//

	function FormularzLogowania() {
		$wynik = '
		<div class="logowanie">
			<h3 class="heading">Panel CMS:</h3>
			<div class="logowanie">
				<form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
					<table class="logowanie">
						<tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
						<tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
						<tr><td> </td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
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
			$status_login = $this->CheckLoginCred('test@admin.com', 'admin');				// Sprawdzenie danych logowania
		}
		else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {		// Czy formularz przekazał login i hasło?
			$status_login = $this->CheckLoginCred('test@admin.com', 'admin');				// Sprawdzenie danych logowania
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
		$status_login = $this->CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

		if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
			echo "<h3> Lista Stron </h3>";
			echo "<a href='?idp=-7'>Wciśnij aby przejść do tabeli Kategorii</a><br></br>";
			echo "<a href='?idp=-11'>Wciśnij aby przejść do tabeli Produktów</a><br></br>";
			echo $this->ListaPodstron();
		}
		else {
			echo $this->FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
		}
	}

	//
	//	funkcja 'CreatePage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
	//  Dane będą wykorzystane do utworzenia nowej strony w bazie danych.
	//

	function CreatePage() {
		$status_login = $this->CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

		if ($status_login == 1) {																// Czy pomyślnie się zalogowano?
			echo "<h3> Strona dodawania</h3>";
			if (isset($_POST['create_title']) && isset($_POST['create_content']) && isset($_POST['create_active']) && isset($_POST['create_alias'])) {		// czy zostały podane wszystkie dane?
				$query = "INSERT INTO page_list (page_title, page_content, status, alias)
						  VALUES ('".$_POST['create_title']."', '".$_POST['create_content']."', '".$_POST['create_active']."', '".$_POST['create_alias']."') LIMIT 1";	// Wprowadź nowe dane do tablicy page_list
				mysqli_query($GLOBALS['mysqli'], $query);																												// Wykonaj query do bazy danych
				header("Location: http://localhost/moj_projekt/index.php?idp=-1");																						// Powrót do Panelu Admina
				die();
			}
			else {
				echo $this->DodajNowaPodstrone();											// Pobierz dane do Dodania
			}
		}
		else {
			echo $this->FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
		}
	}

	//
	//	funkcja 'EditPage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania danych.
	//  Dane będą wykorzystane do edycji strony w bazie danych.
	//

	function EditPage() {
		$status_login = $this->CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

		if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
			echo "<h3> Strona edycji </h3>";
			if (isset($_POST['edit_title']) && isset($_POST['edit_content']) && isset($_POST['edit_active']) && isset($_POST['edit_alias'])) {
				$query = "UPDATE page_list SET page_title = '".$_POST['edit_title']."', page_content = '".$_POST['edit_content']."', status = '".$_POST['edit_active']."', alias = '".$_POST['edit_alias']."'
						  WHERE page_list.id = '".$_GET['ide']."' LIMIT 1";			// Edytuj dane w page_list tabeli o odpowiednim id
				mysqli_query($GLOBALS['mysqli'], $query);							// Wykonaj query do bazy danych
				header("Location: http://localhost/moj_projekt/index.php?idp=-1");	// Powrót do Panelu Admina
				die();
			}
			else {
				echo $this->EdytujPodstrone();												// Pobierz dane do edycji
			}
		}
		else {
			echo $this->FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
		}
	}

	//
	//	funkcja 'DeletePage' wykona sprawdzenie logowania, a następnie wyświetli formularz do podania id strony.
	//  ID będzie wykorzystane do usunięcia strony z bazy danych.
	//

	function DeletePage() {
		$status_login = $this->CheckLogin();															// Sprawdz czy podano dane logowania, oraz ich poprawność

		if ($status_login == 1) {													// Czy pomyślnie się zalogowano?
			echo $this->UsunPodstrone();													// Usun tabelę z page_list o odpowiednim id
			header("Location: http://localhost/moj_projekt/index.php?idp=-1");		// Powrót do Panelu Admina
			die();
		}
		else {
			echo $this->FormularzLogowania();												// Dane się nie zgadzają lub nie zostały podane, wyświetla ponownie formularz logowania.
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
						<tr><td class="log4_t">[zawartosc]</td><td><input type="textarea" style = "width: 500px; height: 900px;" name="edit_content" class="edycjapodstrony" /></td></tr>
						<tr><td class="log4_t">[aktywnosc]</td><td><input type="text" name="edit_active" class="edycjapodstrony" /></td></tr>
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
						<tr><td class="log4_t">[zawartosc]</td><td><input type="textarea" style = "width: 500px; height: 900px;" name="create_content" class="tworzeniepodstrony" /></td></tr>
						<tr><td class="log4_t">[aktywnosc]</td><td><input type="text" name="create_active" class="tworzeniepodstrony" /></td></tr>
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

}

?>
