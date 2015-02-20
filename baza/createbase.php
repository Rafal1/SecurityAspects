<?php
$pol = mysql_connect("mysql.cba.pl", "rafador", "komputer") or die("ERROR 1" . mysql_error());
$baza = mysql_select_db("prakktyki_cba_pl", $pol) or die ("ERROR 2" . mysql_error());

//W OGOLE TEGO NIE UZYWAM
//$p = "CREATE DATABASE newsy";
//$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());

$p = "CREATE TABLE news (d DATETIME, user VARCHAR(12), tytul VARCHAR(25), tresc VARCHAR(250))";
$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());

$p = "CREATE TABLE log (d INT, ip VARCHAR(15), tries INT, capt INT, ban INT)";
$w = mysql_query($p) or die("[ERROR 1]Wykonanie zapytania nie powiod³o siê: " . mysql_error());

mysql_close($pol);
?>