<?php
	
	$receiveAjaxDataArr = explode('_',$_POST['name']);
	
	if($receiveAjaxDataArr[0] == 'del'){
		$db = new PDO ('mysql:host=localhost;dbname=books','root','');
	    $data = $db->prepare('DELETE FROM list WHERE id_list = :id');
	    $data->bindParam(':id', $receiveAjaxDataArr[1]);
	    $data->execute();
		echo $_POST['name'];
		
	} else {
		$db = new PDO ('mysql:host=localhost;dbname=books','root','');
		$data = $db->prepare('SELECT * FROM list WHERE id_list = :id');
		$data->bindParam(':id', $receiveAjaxDataArr[1]);
		$data->execute();
		foreach ($data as $row){
			echo $row['title'].'_'.$row['author'].'_'.$row['genre'].'_'.$row['cost'].'_'.$row['descr'];
		}
	}
?>