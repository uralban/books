<?php
    
	$hideAuthors = '';
	for ($i=1; $i<=$_POST['quantityHide']; $i++) {
	    $tempA = 'authorHide'.$i;
		$hideAuthors .= $_POST[$tempA].'|';
	} 
    $_POST['costHide'] = round($_POST['costHide'], 2);
	
	
    $db = new PDO ('mysql:host=localhost;dbname=books','root','');
	$data = $db->prepare('UPDATE list SET title = :title, author = :author, genre = :genre, descr = :descr, cost = :cost WHERE id_list = :id');
	$data->execute(array(
        ':id' => $_POST['id'],
        ':title' => $_POST['titleHide'],
		':author' => $hideAuthors,
		':genre' => $_POST['genreHide'],
		':descr' => $_POST['descrHide'],
		':cost' => $_POST['costHide'],
    ));
	
	$backData = $_POST['id']."-|-".$_POST['titleHide']."-|-".$hideAuthors."-|-".$_POST['genreHide']."-|-".$_POST['costHide']."-|-".$_POST['descrHide'];
	
	echo $backData;
?>