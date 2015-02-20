  <?php
  $pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
  $baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());
  require_once('recaptcha/recaptchalib.php');
  $privatekey = "6LfYOOISAAAAACNTiQhWuietvJrncdw8WXZjaO7k";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
	$ip=$_SERVER['REMOTE_ADDR'];
	$p = "SELECT * FROM log WHERE ip='".$ip."'"; 
	$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());
	$row = mysql_fetch_assoc($w);
	$new=$row['capt']+1; //inkrementacja nie dziala
	$p = "UPDATE log SET capt=".$new." WHERE ip='".$ip."'"; 
	$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());
	url("http://prakktyki.cba.pl/index.php");	//ban z index.php
  } else {
	  // Your code here to handle a successful verification
		$ip=$_SERVER['REMOTE_ADDR'];
		$p = "SELECT * FROM log WHERE ip='".$ip."'"; 
		$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());
		$row = mysql_fetch_assoc($w);
		$p = "UPDATE log SET tries=0, capt=0 WHERE ip='".$ip."'"; 
		$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());
		url("http://prakktyki.cba.pl/index.php");
  }
  mysql_close($pol);
  
  function url($url){
		while (ob_get_status())  //opro¿nienei bufora outputu przed przekierowaniem
			{
				ob_end_clean();
			}
			header( "Location: $url" );
	}
  ?>