<?php 
	session_start();
	// Jesli uzytkwnik bedzie na stronie forma za dlugo i od?ie?, to zostanie przedawniona sesja i on przekierowany do login.php	
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
		
		<title>Ochrona Danych - Rafał Zawadzki</title>
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
					<p><a href="index.php">Strona Główna</a></p>
					<hr />
 
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					
				</div> 
				<div class="content">

				<h1>Przekroczyłeś limit liczby logowań. Zostales zbanowany i przerzucę Cię za 5 sekund na google.com</h1>
				<?php 
				header('Refresh: 5; URL=http://www.google.com');
				//strona stworzona na wypadek napisania jakiejs innej obslugi uzytkownikow zbanowanych, na razie prosty redirect
				//if(sleep(2)==0){
				// $url = 'http://www.google.com'; 
							// while (ob_get_status())  
								// {
									// ob_end_clean();
								// }
								// header( "Location: $url" );
				//	}
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