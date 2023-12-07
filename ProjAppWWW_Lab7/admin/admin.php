<?php

include 'cfg.php';

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

function CheckLoginCred($login, $pass) {
    if ($login == $_POST['login_email'] && $pass == $_POST['login_pass']) {
		$_SESSION['login_email'] = $_POST['login_email'];
		$_SESSION['login_pass'] = $_POST['login_pass'];
        return 1;
    }
    else {
		echo "Logowanie sie nie powiodlo.";
        return 0;
    }
}

function LoginAdmin() {
	if (isset($_SESSION['login_email']) && isset($_SESSION['login_pass'])) {
		$_POST['login_email'] = $_SESSION['login_email'];
		$_POST['login_pass'] = $_SESSION['login_pass'];
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else {
		$status_login = 0;
	}

	if ($status_login == 1) {
		echo "<h1> Lista Stron </h1>";
		echo ListaPodstron();
	}
	else {
		echo FormularzLogowania();
	}
}

function CreatePage() {
	if (isset($_SESSION['login_email']) && isset($_SESSION['login_pass'])) {
		$_POST['login_email'] = $_SESSION['login_email'];
		$_POST['login_pass'] = $_SESSION['login_pass'];
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else {
		$status_login = 0;
	}

	if ($status_login == 1) {
		echo "<h1> Strona dodawania</h1>";
		if (isset($_POST['create_title']) && isset($_POST['create_content']) && isset($_POST['create_active']) && isset($_POST['create_alias'])) {
			echo $_POST['create_title'];
			echo " ";
			echo $_POST['create_content'];
			echo " ";
			echo $_POST['create_active'];
			echo " ";
			echo $_POST['create_alias'];
			$query = "INSERT INTO page_list (page_title, page_content, status, alias)
					  VALUES ('".$_POST['create_title']."', '".$_POST['create_content']."', '".$_POST['create_active']."', '".$_POST['create_alias']."') LIMIT 1";
			mysqli_query($GLOBALS['mysqli'], $query);
			header("Location: http://localhost/moj_projekt/164382.php?idp=-1");
			die();
		}
		else {
			echo DodajNowaPodstrone();
		}
	}
	else {
		echo FormularzLogowania();
	}
}

function EditPage() {
	if (isset($_SESSION['login_email']) && isset($_SESSION['login_pass'])) {
		$_POST['login_email'] = $_SESSION['login_email'];
		$_POST['login_pass'] = $_SESSION['login_pass'];
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else {
		$status_login = 0;
	}

	if ($status_login == 1) {
		echo "<h1> Strona edycji </h1>";
		if (isset($_POST['edit_title']) && isset($_POST['edit_content']) && isset($_POST['edit_active']) && isset($_POST['edit_alias'])) {
			echo $_POST['edit_title'];
			echo " ";
			echo $_POST['edit_content'];
			echo " ";
			echo $_POST['edit_active'];
			echo " ";
			echo $_POST['edit_alias'];
			$query = "UPDATE page_list SET page_title = '".$_POST['edit_title']."', page_content = '".$_POST['edit_content']."', status = '".$_POST['edit_active']."', alias = '".$_POST['edit_alias']."'
					  WHERE page_list.id = '".$_GET['ide']."' LIMIT 1";
			mysqli_query($GLOBALS['mysqli'], $query);
			header("Location: http://localhost/moj_projekt/164382.php?idp=-1");
			die();
		}
		else {
			echo EdytujPodstrone();
		}
	}
	else {
		echo FormularzLogowania();
	}
}

function DeletePage() {
	if (isset($_SESSION['login_email']) && isset($_SESSION['login_pass'])) {
		$_POST['login_email'] = $_SESSION['login_email'];
		$_POST['login_pass'] = $_SESSION['login_pass'];
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
		$status_login = CheckLoginCred('test@admin.com', 'admin');
	}
	else {
		$status_login = 0;
	}

	if ($status_login == 1) {
		echo UsunPodstrone();
		header("Location: http://localhost/moj_projekt/164382.php?idp=-1");
		die();
	}
	else {
		echo FormularzLogowania();
	}
}

function ListaPodstron() {
    $query = "SELECT id, page_title FROM page_list ORDER BY id ASC LIMIT 100";
    $result = mysqli_query($GLOBALS['mysqli'], $query);

	echo '<table>
			<tr style="color: coral">
				<th>ID Strony</th>
				<th>Tytuł Strony</th>
				<th>Edytuj</th>
				<th>Usuń</th>
			</tr>';
    while($row = mysqli_fetch_array($result)) {
        echo'<tr>
				<td style="color: gold">'.$row['id'].'</td>
				<td style="color: #FFFFFF">'.$row['page_title'].'</td>
				<td><a href="?idp=-3&ide='.$row['id'].'">Edit</a></td>
				<td><a href="?idp=-4&idd='.$row['id'].'">Delete</a></td>
			</tr>';
    }
	echo '</table>';
	echo '<a href="?idp=-2">Create New Page</a>';
}

function EdytujPodstrone() {
    $wynik = '
    <div class="edycjapodstrony">
        <div class="edycjapodstrony">
            <form method="post" name="PageEditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="edycjapodstrony">
                    <tr><td class="log4_t">[title]</td><td><input type="text" name="edit_title" class="edycjapodstrony" /></td></tr>
                    <tr><td class="log4_t">[zawartosc]</td><td><input type="textarea" name="edit_content" class="edycjapodstrony" /></td></tr>
					<tr><td class="log4_t">[aktywnosc]</td><td><input type="checkbox" name="edit_active" class="edycjapodstrony" /></td></tr>
					<tr><td class="log4_t">[aktywnosc]</td><td><input type="textarea" name="edit_alias" class="edycjapodstrony" /></td></tr>
                    <tr><td>$nbsp; </td><td><input type="submit" name="x1_submit" class="edycjapodstrony" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

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
                    <tr><td>$nbsp; </td><td><input type="submit" name="x1_submit" class="tworzeniepodstrony" value="zatwierdz" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
	return $wynik;
}

function UsunPodstrone() {
	$query = "DELETE FROM page_list WHERE id='".$_GET['idd']."' LIMIT 1";
	mysqli_query($GLOBALS['mysqli'], $query);
}

?>