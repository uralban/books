<?php
	include 'functions.php';
	
	$answer = readDb('book', 'find', $_POST['name']);
	
	echo $answer[0][2].'</td><td>'.$answer[0][1];
?>