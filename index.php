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
		
		<title>Ochrona Danych - RafaĹ Zawadzki</title>
	
	</head>
			<body>
			<div class="external"> 
				<div class="header"> 
					<h1 class="tit">Strona HTML</h1>
				</div>			
				<div class="sidemenu">  
					<p><a href="index.php">Strona GĹĂłwna</a></p>
					<hr>
					
					<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88" style="border-bottom-style : none; border-left-style : none; border-right-style : none; border-top-style : none;"></a>
					
					<a  href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a>
					
				</div> 
				<div class="content">
					<?php
						
						$result=tries();
						if($result){
							check();
						}	
						
						function showCap(){
							echo "<p><form method=\"post\" action=\"verifyCap.php\">";
									  require_once('recaptcha/recaptchalib.php');
									  $publickey = "6LfYOOISAAAAAG9MdNObFu9eVJVihTjjbFCCqlh2"; // you got this from the signup page
									  echo recaptcha_get_html($publickey);
							echo "<div><input type=\"submit\" /></div>";
							echo "</form></p>";	
						}
												
						function tries(){ //globalny system banujacy
							$pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
							$baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());
							
							$ip=$_SERVER['REMOTE_ADDR'];
							$p = "SELECT * FROM log WHERE ip='".$ip."'"; 
							$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
							$row = mysql_fetch_assoc($w);
							
							
							if($row != NULL && $row['ban'] == 1) { //ban
								$banTime=time()-$row['d'];
								$banMax=60;		//60*60*24; dla demonstracji
								if($banTime>=$banMax){
									$p = "UPDATE log SET ban=0, d=0, tries=0, capt=0 WHERE ip='".$ip."'"; 
									$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
									mysql_close($pol);
									return true;
								}
								
								mysql_close($pol);
								$url = 'http://www.prakktyki.cba.pl/block.php'; 
								while (ob_get_status())  //oprożnienei bufora outputu przed przekierowaniem
								{
									ob_end_clean();
								}
								header( "Location: $url" );
								return false;
							} 
							if($row != NULL && $row['tries']==5 && $row['capt'] < 2){
									//$capDisplay=true;
									showCap();//wyswietlenie captcha != check wartosci... wiec
									mysql_close($pol);
									return false; //aby nie zrobilo chech danych
							} else if($row != NULL && $row['tries']==5 && $row['capt'] == 2){
								$date=time(); 
								$p = "UPDATE log SET ban=1, d='$date' WHERE ip='".$ip."'"; 
								$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
								mysql_close($pol);
								url("http://prakktyki.cba.pl/index.php");
								return false;
							}
							mysql_close($pol);
							return true;	
						}
						
						 function url($url){
							while (ob_get_status())  //oprożnienei bufora outputu przed przekierowaniem
								{
									ob_end_clean();
								}
								header( "Location: $url" );
						}
						
						function checkTries(){ //lokalny (w sytuacji zlych danych)
							$pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
							$baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());
							
							$ip=$_SERVER['REMOTE_ADDR'];
							$p = "SELECT * FROM log WHERE ip='".$ip."'"; 
							$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
							$row = mysql_fetch_assoc($w);
							if($row != NULL){  
								if($row['tries']==4){ //liczba logowan - 1
									$new=$row['tries']+1;
									$p = "UPDATE log SET tries=".$new." WHERE ip='".$ip."'"; 
									$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
									mysql_close($pol);
									$url = 'http://www.prakktyki.cba.pl/index.php'; //na podstawie liczby prob - pojawi sie captcha 
									while (ob_get_status())  
									{
										ob_end_clean();
									}
									header( "Location: $url" );
									return;
								} else{
									$new=$row['tries']+1;
									sleep(2*$new); //opnienie logowania (kazdej proby)
									$p = "UPDATE log SET tries=".$new." WHERE ip='".$ip."'"; 
									$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
									mysql_close($pol);
									return;
								}
							} else { 
								//$date=date('Y-m-d H:i:s');
								$p = "INSERT INTO log (ip,tries,capt,ban) VALUES('$ip', '1','0','0')";
								$w = mysql_query($p) or die("[ERROR 4]}Wykonanie zapytania nie powiodło? się?" . mysql_error());
								mysql_close($pol);
								return;
							}
							mysql_close($pol);
						}
									
						function check() {	
							if(isset($_REQUEST["login"]) && isset($_REQUEST["passwordlogin"])) {
								if(strlen($_REQUEST["login"]) > 12 || strlen($_REQUEST["passwordlogin"]) > 32){ 
								checkTries();
								err("BĹÄdne dane logowania");
								return false;
								}
								
								$_SESSION["login"] = $_REQUEST["login"];
								$_SESSION["passwordlogin"] = $_REQUEST["passwordlogin"];
							}
							
							//!!!
							$userExtinct=false;
							$correctlogin = "";
							$correctpasswordlogin = ""; 
							$handle=fopen("lock/users.txt","r");
							while($names=fscanf($handle,"%s\t%s\t%s\t%s\n")){
								list($userName, $hashPass, $salt,$mail)=$names;
								if(isset($_SESSION["login"]) && $userName===$_SESSION["login"]){
										$userExtinct=true;
										$correctlogin = $userName;
										$correctpasswordlogin = $hashPass; 
										$passwordSalt=$salt;
										break;
								}
							}
							fclose($handle);
							if(isset($_SESSION["login"]) && $userExtinct==false){ //poprawne dane moga byc empty string!
								checkTries();
								err("BĹÄdne dane logowania");
								return false;
							}
							
							//nie sprawdzam loginu bo przy sprawdzaniu jego jest return
							if(isset($_SESSION["passwordlogin"])) { 							
								$passwordlogin = $_SESSION["passwordlogin"];
								
								//mechanizm haslo -> hash
								$toCrypt=crypt($passwordlogin,$passwordSalt);					
								$hashOfPassword=explode('$',$toCrypt); 
								$toCrypt = preg_replace('/\$6\$rounds=<9>\$/',"",$toCrypt);
								$hashed_password=$toCrypt;

								
								include("entropy.php");
								//policzyc entropie bo to jedyne miejsce gdzie mam jawne haslo, mozna ja getem wyslac
								$_SESSION['EntropyValue']=entropy($passwordlogin);
								
								
								if($correctpasswordlogin === $hashed_password) { 
									$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
									session_regenerate_id();  
									if(currentSES()) { 
										$_SESSION["logged"] = 1; 
										
										$pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
										$baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());
										$ip=$_SERVER['REMOTE_ADDR'];
										$p = "SELECT * FROM log WHERE ip='".$ip."'"; 
										$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
										$row = mysql_fetch_assoc($w);
										$p = "UPDATE log SET tries=0, capt=0 WHERE ip='".$ip."'"; 
										$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiodło się: " . mysql_error());
		
										redirect();
									}	
									return true;
								} else {
										err("BĹÄdne dane logowania"); //chodzi o sam password 
										checkTries();
										return false;
								}		
							} else { //jesli nie był wczesniej na stronie 
								showformlogin(); 
							}
						}
						
						function err($s) {
							echo "<h2 class=\"makecolorred\">", $s,"<br></h2>"; //bledne dane w sesji - obsluga
							showformlogin(); 
							if(!session_destroy()) {
								echo "<h2>Nieoczekiwany błąd przy czyszczeniu niepoprawnych danych</h2>";
							}
						}
						
						function showformlogin() {
							if(isset($_REQUEST["hidden"])) {
								$_REQUEST["hidden"] = 0; }
							echo "<h1>Zaloguj siÄ:</h1>"; 
							echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"post\">";
							echo "<div><label>Login:   </label><input type=\"text\" name=\"login\" /></div>";
							echo "<div><input type=\"hidden\" name=\"hidden\" value=\"1\"/></div>";
							echo "<div><label>HasĹo:   </label><input type=\"password\" name=\"passwordlogin\" /></div>";
							echo "<div class=\"loginsubmitmargin\"><input  type=\"submit\" name=\"submit\" value=\"WyĹlij\" /></div>";
							echo "</form>"; 
							
							if(isset($_REQUEST["hidden"]) && $_REQUEST["hidden"] == 1){	//musi byc isset bo za 1 razem moze coś byc z formem nie w porządku i nie zapisac
								check();
							}
						}
						
						function redirect() {
							$url = 'http://www.prakktyki.cba.pl/loginform/form_full_alone.php'; 
							while (ob_get_status())  //oprożnienei bufora outputu przed przekierowaniem
								{
									ob_end_clean();
								}
								header( "Location: $url" );
						}
											
						function redirectlogout() { // jesli ktos byl zalogowany... i spodziewa sie z fromularza z danymi po przejsciu do ekranu logowania, a tu sesja wygasła to zostanie przekierowany do ponownego zalogowania sie, a WSZELKIE dane beda usuniete
							$url = 'http://www.prakktyki.cba.pl/logout.php'; 
							while (ob_get_status())  
								{
									ob_end_clean();
								}
								header( "Location: $url" );
						}	
						
						function currentSES() {
							if(isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) { //zabezpieczenie przed spreparowaniem ciasteczka (umieszczone tutaj ze wzgldedu na możliwosc obejrzenia forma przechodzac na login.php i koniecznoć prawdzenia sesji)
								redirectlogout();
								return false;
							}
							
							if (isset($_SESSION["LAST_ACTIVITY"]) && ((time() - $_SESSION["LAST_ACTIVITY"]) > 1800) && isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
								redirectlogout();
								return false;
							} 
							
							if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) { //zabezpieczenie przed spreparowaniem sesji wyżej
								//zaloguje sie -> wejdzie na login.php; bez tego otrzymal by nowa 30minutowa sesje, a teraz juz mu nie bedzie odnawialo, ma 1 pół godziny niezależnie od sposobu dojscia do formularza
								return true;
							}
							$_SESSION["LAST_ACTIVITY"] = time();
							return true;
						}
						
					?>
					<p><a href="recallPass.php">Odzyskaj hasĹo</a></p>
					<p><a href="notatki.php">Opublikowane notatki</a></p>
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