<?php
	include 'functions.php';
	
	$dataComplete = '';
	$answer = readDb('autors', 'all', '');
	for ($i=0; $i<count($answer[0]); $i++){
		($i<count($answer[0])-1) ? $dataComplete .= $answer[0][$i].'|' : $dataComplete .= $answer[0][$i].'$$$';
	}
	$answer = readDb('genre', 'all', '');
	for ($i=0; $i<count($answer[0]); $i++){
		($i<count($answer[0])-1) ? $dataComplete .= $answer[0][$i].'|' : $dataComplete .= $answer[0][$i];
	}
	echo $dataComplete;	
?>