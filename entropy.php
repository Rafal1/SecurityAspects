<?php
function entropy($chunk) {
	$tablica=str_split($chunk);
	$amount=count($tablica);
	$stat = array();
	for($i=0;$i<$amount;$i++){
		if(array_key_exists($tablica[$i],$stat)){
			$stat[$tablica[$i]] += 1;
		} else {
			$stat[$tablica[$i]] = 1;	
		}	
	}
	$SUM=0;
	$keys = array_keys($stat,true);
	//print_r($keys); //	print_r($stat); //tablice asocjacyjna drukuje
	$howKeys=count($keys);
	for($i=0;$i<$howKeys;$i++){
		$probability=$stat[$keys[$i]]/$amount;
		$su=log($probability,2);
		$SUM += -($probability*$su);
	}
	return $SUM;
}
?>