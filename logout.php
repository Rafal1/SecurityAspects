<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head> 
		<meta  http-equiv="CONTENT-TYPE" content="text/css; charset=iso-8859-2"> 
		<link rel="stylesheet" type="text/css" href="../style.css">
		<meta http-equiv="Content-Language" content="pl">
		<meta name="description" content="Strona do cel??dukacyjnych">
		<meta name="keywords" content="student, test, edukacja">
		
		<title>Ochrona Danych - Rafał Zawadzki</title>
	
	</head>
			<body>
			<div class="external"> 
				<div class="header"> 
					<h1 class="tit">Strona HTML</h1>
				</div>			
				<div class="sidemenu">  
					<p><a href="index.php">Strona Główna</a></p>
					<hr>
					
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					
				</div> 
				<div class="content">
				<?php
				if(isset($_SESSION["logged"])){
						if (isset($_SESSION["LAST_ACTIVITY"]) && (time() - $_SESSION["LAST_ACTIVITY"] < 1800)) {	
							if(isset($_SESSION['ip']) && $_SESSION['ip']===$_SERVER['REMOTE_ADDR']) {
								session_unset();
								if(session_destroy()) {
									echo "<h2>Pomyślnie wylogowano</h2>";
								} else {
									echo "<h2>Błąd przy wylogowywaniu</h2>";
								}
							} else {
								echo "<p>Próbujesz zamknąć sesję kogoś innego! Próba oszustwa!</p>";
								echo "<img src=\"are.png\"></img>";
							} 
						} else {
							session_unset(); 
							session_destroy();
							echo "<h2>Sesja wygasła (proces równoważny wylogowaniu)</h2>";
						}
				} else{
					session_unset(); 
					session_destroy();
					echo "<h2>Nie byłeś zalogowany</h2>";
				}
				
				echo "<div><a href=\"http://www.prakktyki.cba.pl/loginform/form_full_alone.php\">Powrót do formularza (nie da sie bez zalogowania - link do testów)</a></div> ";	
				echo "<div><a href=\"http://www.prakktyki.cba.pl\">Powrót do ekranu logowania</a></div>";	
				?>
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