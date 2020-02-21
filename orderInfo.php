<?php
    $db = new PDO ('mysql:host=localhost;dbname=books','root','');
		$data = $db->prepare('SELECT * FROM list WHERE id_list = :id');
		$data->bindParam(':id', $_POST['id']);
		$data->execute();
		foreach ($data as $row){
			echo $row['title'].'_'.$row['author'].'_'.$row['genre'].'_'.$row['cost'].'_'.$row['descr'].'_'.$_POST['id'];
		}
?>