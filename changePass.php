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
					<p><a href="../../index.php">Strona Główna</a></p>
					<hr />
 
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					
				</div> 
				<div class="content">
					<?php 
						$sesLogin=$_SESSION['login'];
						if(isset($_SESSION["logged"])) { //jesli uzytkownik  wejdzie na strone formularza -> automatyczne przekierowanie do do strony logowania
							if($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) { //jesli uzytkownik oszukuje np wykradl komus sesje i spreparowal zmienna pomocnicza logged
								echo "<p>Wchodzisz do formularza u?waj? czyjej?sesji! Pr??oszustwa!</p>";
								echo "<img src=\"../are.png\"></img>";
								if(sleep(5) == 0) {
									redirectlogout();
								}	
							}
						} else { 
							redirectlogout();
						}
							
						function redirectlogout() { // jesli ktos byl zalogowany... i spodziewa sie z fromularza z danymi po przejsciu do ekranu logowania, a tu sesja wygas? to zostanie przekierowany do ponownego zalogowania sie, a WSZELKIE dane beda usuniete
							$url = 'http://www.prakktyki.cba.pl/logout.php'; 
							while (ob_get_status())  //opro?ienei bufora outputu przed przekierowaniem
								{
									ob_end_clean();
								}
								header( "Location: $url" );
						}							

					if(isset($_REQUEST['old']) && 
						isset($_REQUEST['new']) && 
						isset($_REQUEST['new2']) && 
						$_REQUEST['new']===$_REQUEST['new2'] &&
						checkLength($_REQUEST['new'],32) &&
						checkLength($_REQUEST['new2'],32) &&
						checkLength($_REQUEST['old'],32)
						){
						
						$pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
						$baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());
						
						$correctpasswordlogin = ""; 
						$lineNumber=0;
						$handle=fopen("lock/users.txt","r");
						while($names=fscanf($handle,"%s\t%s\t%s\n")){
							list($userName, $hashPass,$salt) = $names;
							$lineNumber++;
							if(isset($sesLogin) && $userName===$sesLogin){
									$correctpasswordlogin = $hashPass; 
									$passwordSalt=$salt;
									break;
							}
						}
						
						$hashOfPassword=explode('$',crypt($_REQUEST['old'],$passwordSalt)); //pattern zrobic!
						$hashed_password = $hashOfPassword[3];
						
						if($correctpasswordlogin === $hashed_password) { 

							$N='9';
							$salt='$6$rounds=<'.$N.'>$';
							$rand=generateRandomString();
							$SALT=$salt.$rand;
							fclose($handle); //zamknac po wczytaniu hasha
							delLineFromFile("lock/users.txt", $lineNumber);
							$s=explode('$',crypt($_REQUEST['new'],$SALT));
							$handle=fopen("lock/users.txt","a");
							$tresc=sprintf("%s\t%s\t%s\n",$sesLogin, $s[3],$SALT);
							if(fwrite($handle,$tresc)==false){
								echo "Blad w zapisie do pliku";
							}
							include("entropy.php");
							$e=entropy($_REQUEST['new']);
							$_SESSION['EntropyValue']=$e;
							ok("Hasło Zmienione, jego entropia wynosi: ".$e);
						} else {
							err("Podałeś niepoprawne dane");	
						}	
						fclose($handle);
						mysql_close($pol);
					} else if(isset($_REQUEST['filled'])){
						err("Podałeś niepoprawne dane");
					}					
					
					function generateRandomString() {
						$length = 16;
						return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,.:;{}[]_-+=/\!@#%^&*()"), 0, $length);
					}
					
					function err($s) {
							echo "<h2 class=\"makecolorred\">", $s,"<br></h2>"; //bledne dane w sesji - obsluga
					}		
							
					function ok($s) {
							echo "<h2 class=\"makecolorgreen\">", $s,"<br></h2>"; 		
						}
						
					function checkLength($s,$ml){
						$x=strlen($s);
						if($x<=$ml){
							return true;
						} else {
							return false;
						}
					}
					
					function delLineFromFile($fileName, $lineNum){
// check the file exists 
  if(!is_writable($fileName))
    {
    // print an error
    print "The file $fileName is not writable";
    // exit the function
    exit;
    }
  else
      {
    // read the file into an array    
    $arr = file($fileName);
    }

  // the line to delete is the line number minus 1, because arrays begin at zero
  $lineToDelete = $lineNum-1;
 
  // check if the line to delete is greater than the length of the file
  if($lineToDelete > sizeof($arr))
    {
      // print an error
    print "You have chosen a line number, <b>[$lineNum]</b>,  higher than the length of the file.";
    // exit the function
    exit;
    }

  //remove the line
  unset($arr["$lineToDelete"]);

  // open the file for reading
  if (!$fp = fopen($fileName, 'w+'))
    {
    // print an error
        print "Cannot open file ($fileName)";
      // exit the function
        exit;
        }
  
  // if $fp is valid
  if($fp)
    {
        // write the array to the file
        foreach($arr as $line) { fwrite($fp,$line); }

        // close the file
        fclose($fp);
        }

//echo "done";
}
					function showForm(){
						echo "<form method=\"POST\" action=\"\">";
						echo	"<input type=\"hidden\" name=\"filled\" value=\"1\" />";
						echo 	"Stare hasło:<br>";
						echo 	"<input type=\"password\" name=\"old\" />";
						echo 	"<br>";
						echo 	"Nowe hasło:<br>";
						echo	"<input type=\"password\" name=\"new\" />";
						echo 	"<br>";
						echo 	"Powtórz nowe hasło:<br>";
						echo	"<input type=\"password\" name=\"new2\" />";
						echo	"<p>";
						echo	"<input type=\"submit\" value=\"Wyślij\" />";
						echo 	"</form>";
					}
					?>
					<h1>Zmiana hasła</h1>  <!--htmlspecialchars($sesLogin) -->
					<h2>Zalogowano jako: <?php echo  $sesLogin ?></h2>
					<h3>Entropia twojego hasła:  <?php echo $_SESSION['EntropyValue']?> 
					<p />
					<?php
					showForm();
					?>
					<p><a href="loginform/form_full_alone.php">Powrót do panela</a></p>
					<p><a href="logout.php">Wyloguj</a></p>
				
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