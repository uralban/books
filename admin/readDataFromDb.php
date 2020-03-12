<?php
    include '../functions.php';	
	
	$receiveAjaxData = $_POST['id'];
    if ($receiveAjaxData == 'addAutor'){
		$answer = controlButtonFunction('autors', 'Автор');	    		
    } else if ($receiveAjaxData == 'addGenre'){
		$answer = controlButtonFunction('genre', 'Жанр');
	} else if ($receiveAjaxData == 'addBook'){
		$answer = controlButtonFunction('book', 'Название');
	} else if ($_POST['action'] == 'readAutorsGenre'){
		$answer = readAutorsGenre($_POST['id']);
	}
	echo $answer;
?>