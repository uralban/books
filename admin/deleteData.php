<?php 
    include '../functions.php';
	
	if (array_key_exists('autor', $_POST)){
		$answer = readDb('autors', 'find', $_POST['autor']);
		$currentId = $answer[1][0];
		$answer = readDb('book_autors', 'find', $currentId);
		if (count($answer)>=1) {
			echo 'error';
		} else {
			delDb('autors', $currentId);
			echo $_POST['autor'];
		}
	} else if (array_key_exists('genre', $_POST)){
		$answer = readDb('genre', 'find', $_POST['genre']);
		$currentId = $answer[1][0];
		$answer = readDb('book_genre', 'find', $currentId);
		if (count($answer)>=1) {
			echo 'error';
		} else {
			delDb('genre', $currentId);
			echo $_POST['genre'];
		}
	} else if (array_key_exists('name', $_POST)){
		$answer = readDb('book', 'find', $_POST['name']);
		$currentId = $answer[0][3];
		delDb('book_autors', $currentId);
		delDb('book_genre', $currentId);
		delDb('book', $currentId);
		
		echo $_POST['name'];
	}
	
	
	
	
	