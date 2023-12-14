<?php

function WyslijMailKontakt($odbiorca) {
	if(empty($_POST['email']) || empty($_POST['title']) || empty($_POST['content'])) {
		echo '[niewypelnione_pole]';
		echo PokazKontakt();
	}
	
	else {
		$mail['sender']			= $_POST['email'];
		$mail['subject']		= $_POST['title'];
		$mail['body']			= $_POST['content'];
		$mail['recipient']		= $odbiorca;
		
		$header  = "From: Forumularz kontaktowy <".$mail['sender'].">\n";
		$header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset-utf-8\nContent-Transfer-Encoding: ";
		$header .= "X-Sender: <".$mail['sender'].">\n";
		$header .= "X-Mailer: prapwww mail 1.2\n";
		$header .= "X-Priority: 3\n";
		$header .= "Return-Path: <".$mail['sender'].">\n";
		
		mail($mail['recipient'],$mail['subject'],$mail['body'],$header);
		
		echo '[wiadomosc_wyslana]';
	}
}

function PrzypomnijHaslo($odbiorca) {
	if(empty($_POST['email_recov'])) {
		echo '[niewypelnione_pole]';
		echo PokazKontaktHaslo();
	}
	
	else {
		$mail['sender']			= $_POST['email_recov'];
		$mail['subject']		= "Password Recovery";
		$mail['body']			= "Password = Admin";
		$mail['recipient']		= $odbiorca;
		
		$header  = "From: Forumularz kontaktowy <".$mail['sender'].">\n";
		$header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset-utf-8\nContent-Transfer-Encoding: ";
		$header .= "X-Sender: <".$mail['sender'].">\n";
		$header .= "X-Mailer: prapwww mail 1.2\n";
		$header .= "X-Priority: 3\n";
		$header .= "Return-Path: <".$mail['sender'].">\n";
		
		mail($mail['recipient'],$mail['subject'],$mail['body'],$header);
		
		echo '[haslo_wyslane]';
	}
}

function PokazKontakt() {
    $wynik = '
    <div class="form_email">
        <div class="form_email">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="form_email">
                    <tr><td class="log4_t">Email: </td><td><input type="text" name="email" class="form_email" /></td></tr>
                    <tr><td class="log4_t">Tytuł: </td><td><input type="text" name="title" class="form_email" /></td></tr>
					<tr><td class="log4_t">Zawartość: </td><td><input type="textarea" name="content" class="form_email" /></td></tr>
                    <tr><td></td><td><input type="submit" name="x1_submit" class="form_email" value="zaloguj" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

function PokazKontaktHaslo() {
    $wynik = '
    <div class="form_passrecov">
        <div class="form_passrecov">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="form_passrecov">
                    <tr><td class="log4_t">Email: </td><td><input type="text" name="email_recov" class="form_passrecov" /></td></tr>
                    <tr><td></td><td><input type="submit" name="x1_submit" class="form_passrecov" value="zaloguj" /></td></tr>
                </table>
            </form>
         </div>
    </div>
    ';
    return $wynik;
}

?>	
