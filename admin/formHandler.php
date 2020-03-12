<?php
    include '../functions.php';
	if (array_key_exists('autorsAddInput', $_POST)){
		$dataToCheck = trim($_POST['autorsAddInput']);
		$dataType = 'autors';
		$actionType = 'write';
	} else if (array_key_exists('genreAddInput', $_POST)) {
		$dataToCheck = trim($_POST['genreAddInput']);
		$dataType = 'genre';
		$actionType = 'write';
	} else if (array_key_exists('bookAddInput', $_POST)) {
		$dataToCheck[0] = trim($_POST['bookAddInput']);
		$dataToCheck[1] = trim($_POST['cost']);
		$dataToCheck[2] = trim($_POST['descr']);
		$autorsLength = $_POST['quantityAutors'];
		$counter = 0;
		for($n=1; $n<=$autorsLength; $n++){
			$currentInputName = 'autor'.$n;
			$dataToCheck[3][$counter] = $_POST[$currentInputName];
			$counter++;
		}
		$dataToCheck[3] = arrayUnique($dataToCheck[3]);
		$genreLength = $_POST['quantityGenre'];
		$counter = 0;
		for($n=1; $n<=$genreLength; $n++){
			$currentInputName = 'genre'.$n;
			$dataToCheck[4][$counter] = $_POST[$currentInputName];
			$counter++;
		}
		$dataToCheck[4] = arrayUnique($dataToCheck[4]);
		$dataType = 'book';
		$actionType = 'write';
	} else if(array_key_exists('autorsHideInput', $_POST)){
		$dataToCheck = trim($_POST['autorsHideInput']).'|'.$_POST['marker'];
		$dataType = 'autors';
		$actionType = 'update';
	} else if(array_key_exists('genreHideInput', $_POST)){
		$dataToCheck = trim($_POST['genreHideInput']).'|'.$_POST['marker'];
		$dataType = 'genre';
		$actionType = 'update';
	}  else if(array_key_exists('nameHideInput', $_POST)){
		$dataToCheck[0] = trim($_POST['nameHideInput']);
		$dataToCheck[1] = trim($_POST['costHide']);
		$dataToCheck[2] = trim($_POST['descrHide']);
		$autorsLength = $_POST['quantityHideAutors'];
		$counter = 0;
		for($n=1; $n<=$autorsLength; $n++){
			$currentInputName = 'autorHide'.$n;
			$dataToCheck[3][$counter] = $_POST[$currentInputName];
			$counter++;
		}
		$dataToCheck[3] = arrayUnique($dataToCheck[3]);
		$genreLength = $_POST['quantityHideGenre'];
		$counter = 0;
		for($n=1; $n<=$genreLength; $n++){
			$currentInputName = 'genreHide'.$n;
			$dataToCheck[4][$counter] = $_POST[$currentInputName];
			$counter++;
		}
		$dataToCheck[4] = arrayUnique($dataToCheck[4]);
		$dataToCheck[5] = $_POST['marker'];
		
		$dataType = 'book';
		$actionType = 'update';	
	} 
	$answer = checkForm($dataToCheck, $dataType, $actionType);
	echo $answer;
	