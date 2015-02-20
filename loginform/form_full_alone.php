<?php 
	session_start();
	// Jesli uzytkwnik bedzie na stronie forma za dlugo i odwieży, to zostanie przedawniona sesja i on przekierowany do login.php	
	currentSES();
	
	function currentSES() {
		if (isset($_SESSION["LAST_ACTIVITY"]) && (time() - $_SESSION["LAST_ACTIVITY"] > 1800)) {
			redirectlogout();
			return false;
		} 
		return true;
	}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head> 
		<meta  http-equiv="CONTENT-TYPE" content="text/css; charset=iso-8859-2"> 
		<link rel="stylesheet" type="text/css" href="../../style.css">
		<meta http-equiv="Content-Language" content="pl">
		<meta name="description" content="Strona do cel??dukacyjnych">
		<meta name="keywords" content="student, test, edukacja">
		
		<title>Ochrona Danych - RafaĹ Zawadzki</title>
		<script src="validate_form.js" type="text/javascript"></script>
		<script type="text/javascript" src="overlib/overlib.js"></script>
	
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" >
		<script src="http://code.jquery.com/jquery-1.8.2.js" type="text/javascript" ></script> <!-- <script src="http://code.jquery.com/jquery-1.8.2.js" type="text/javascript" />  nie dziala w html-->
		<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js" type="text/javascript" ></script>
		<script src="jQuery.js" type="text/javascript" > </script>
	</head>
			<body>
			<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div> <!--  src="overlib/overlib.js" -->
			<div class="external"> 
				<div class="header"> 
					<h1 class="tit">Strona HTML</h1>
				</div>			
				<div class="sidemenu">  
					<p><a href="../../index.php">Strona GĹĂłwna</a></p>
					<hr />
 
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					
				</div> 
				<div class="content">
					<?php 
						
						if(isset($_SESSION["logged"])) { //jesli uzytkownik  wejdzie na strone formularza -> automatyczne przekierowanie do do strony logowania
							if($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) { //jesli uzytkownik oszukuje (np wykradl komus sesje i spreparowal zmienna pomocnicza logged
								echo "<p>Wchodzisz do formularza używając czyjej sesji! Próba oszustwa!</p>";
								echo "<img src=\"../are.png\"></img>";
								if(sleep(5) == 0) {
									redirectlogout();
								}	
							}
						} else {
							redirectlogout();
						}
							
						function redirectlogout() { // jesli ktos byl zalogowany... i spodziewa sie z fromularza z danymi po przejsciu do ekranu logowania, a tu sesja wygasła to zostanie przekierowany do ponownego zalogowania sie, a WSZELKIE dane beda usuniete
							$url = 'http://www.prakktyki.cba.pl/logout.php'; 
							while (ob_get_status())  //oprożnienei bufora outputu przed przekierowaniem
								{
									ob_end_clean();
								}
								header( "Location: $url" );
						}							

						function fill($param) {
							$obj = $_SESSION[$param];
							if(!empty($obj)) {
								return $obj;
							}
							return "";
						}
						
						function fill_radio($param) {
							$obj = $_SESSION["sex"];
							if(!empty($obj)) {
								if($obj === $param) {
									return "checked";
								}
							} 
							return "";
						}
					?>
					 
					<h1>Panel i wysyĹanie notki</h1> 
					<h2>Zalogowano jako: <?php echo htmlspecialchars($_SESSION["login"]) ?></h2>
					<h3>Entropia twojego hasĹa:  <?php echo $_SESSION['EntropyValue']?>. <BR>HasĹo powinno mieÄ wysokÄ entropiÄ (ponad 2.5). JeĹli chcesz je zmieniÄ <a href="../changePass.php">kliknij tutaj</a>.</h3>
					<h3><p><a href="/notatki.php">Opublikowane notatki</a></p></h3>
					<p />
					
					<form method="POST" action="/baza/process.php">
						<p>TytuĹ:<br>
						<input type="text" name="tytul" />
						</p>
						<p>TreĹÄ:<br>
						<textarea rows="5" cols="50"  name="tresc">
						</textarea>
						</p>
						<p>
						<input type="hidden" name="user" value="<?php echo $_SESSION["login"]?>" /> <!-- czy login nie zaiwera XSS i SQL Injection -> zabezpiecznie w process.php -->
						<input type="submit" value="WyĹlij" />
					</form>
					<p><a href="../logout.php">Wyloguj</a></p>
					
					
				</div>
				<div class="bottom">
					<div class="BottomElementsPosition">
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					</div>			
				</div>
			</div>		
			</body>
</html>