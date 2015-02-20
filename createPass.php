<?php
$handle=fopen("lock/users.txt","wb");  //uwaga bo skasuje all dotychczasowych uzytkownikow
$N='9';
$salt='$6$rounds=<'.$N.'>$';

$user[0][0]="r";
$user[0][1]="rr";
$user[0][2]="tytus212@gmail.com";

$user[1][0]="Adam";
$user[1][1]="abel";
$user[1][2]="a@op.pl";

for ($i=0;$i<count($user);$i++){
		$rand=generateRandomString();
		$SALT=$salt.$rand;
		$s=explode('$',crypt($user[$i][1],$SALT));

		$tresc=sprintf("%s\t%s\t%s\t%s\n",$user[$i][0], $s[3],$SALT,$user[$i][2]);
		if(fwrite($handle,$tresc)==false){
			echo "Blad w zapisie do pliku";
		}
}
fclose($handle);
echo "Powinien sie wyswietliæ plik: &lt;USER&gt;\t&lt;PASSWORD_HASH&gt;\t&lt;SOL&gt;"."<BR>";
$handle2=fopen("lock/users.txt","r");
while(!feof($handle2)){
	$text=fgets($handle2);
	echo $text."<BR>";
}
fclose($handle2);

function generateRandomString() {
	$length = 16;
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,.:;{}[]_-+=/\!@#%^&*()"), 0, $length);
}

?>