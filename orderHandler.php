<?php
		
	$answer = '';
	if (strlen(trim($_POST['customerName']))<5) {
		$answer = 'Укажите полное имя';
	}
	if (strlen(trim($_POST['customerAddress']))<5){
		($answer != '') ? $answer .= "<br>Адрес должен быть длиннее" : $answer = "Адрес должен быть длиннее";
	}
	
	$db = new PDO ('mysql:host=localhost;dbname=books','root','');
	$data = $db->prepare('SELECT * FROM list WHERE id_list = :id');
		$data->bindParam(':id', $_POST['id']);
		$data->execute();
		foreach ($data as $row){
			$mailText = '<p>Заказ: '.$row['title'].', '.$row['author'].', '.$row['genre'].', '.$row['cost'].', '.$row['descr'].', ид книги - '.$_POST['id'].'</p><p>Количество: '.$_POST['orderQuantity'].'</p><p>Покупатель: '.trim($_POST['customerName']).'</p><p>Доставить по адресу: '.trim($_POST['customerAddress']).'</p>';
		}
	
	$adminMail = 'admin@localhost.com';
	$mailSubject = 'New order ' . date("Y-m-d H:i:s");
	$headers  = "Content-type: text/html; charset=utf-8 \r\n";
	$headers .= "From: <postmaster@localhost>\r\n"; 
	
	mail($adminMail, $mailSubject, $mailText, $headers);
	
    echo $answer;
?>