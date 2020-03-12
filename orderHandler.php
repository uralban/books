<?php		
	include 'functions.php';
	
	$answer = '';
	
	if (strlen(trim($_POST['customerName']))<5) {
		$answer = 'Укажите полное имя';
	}
	if (strlen(trim($_POST['customerAddress']))<5){
		($answer != '') ? $answer .= "<br>Адрес должен быть длиннее" : $answer = "Адрес должен быть длиннее";
	}
	$mailText = "<p>Заказ: ".$_POST['bookName'].", ".$_POST['autors'].", ".$_POST['genre'].", ".$_POST['cost'].", ".$_POST['descr']."</p>";
	$mailText .="<p>Количество: ".$_POST['orderQuantity']."</p>";
	$mailText .="<p>Покупатель: ".trim($_POST['customerName'])."</p>";
	$mailText .="<p>Доставка по адресу: ".trim($_POST['customerAddress'])."</p>";
	
	$mailSubject = 'New order ' . date("Y-m-d H:i:s");
	
	if ($answer == ''){
		mail(ADMIN_MAIL, $mailSubject, $mailText, HEADERS);
	}
	
    echo $answer;
?>