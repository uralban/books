<?php
    $msgAlert = '';
	$msgComplete = '';
	$authors = '';
    if (count($_POST)>0){
		$title = trim($_POST['title']);
		if (strlen($title)<3){
			$msgAlert .= 'Необходимо внести название книги <br>';
		}
		for($i=0; $i<$_POST['quantity']; $i++){
			$author[$i] = trim($_POST['author'.($i+1)]);
			if ( strlen($author[$i])<3){
				$n = $i+1;
				$msgAlert .= "Необходимо добавить $n автора <br>";
			}
			$authors .= $author[$i].'|';
		}
		$genre = trim($_POST['genre']);
		if (strlen($genre)<3){
			$msgAlert .= 'Необходимо добавить жанр <br>';
		}
		$cost = trim($_POST['cost']);
		if (strlen($cost)<1){
			$msgAlert .= 'Необходимо назначить цену <br>';
		} elseif (!is_numeric($cost)){
			$msgAlert .= 'Цена должна быть числовой <br>';
		} else {
			$cost = round($cost, 2);
		}
		$descr = trim($_POST['descr']);
		if (strlen($descr)<1){
			$msgAlert .= 'Необходимо добавить описание<br>';
		} elseif (strlen($descr)<10) {
			$msgAlert .= 'Описание должно быть длиннее<br>';
		}
		if ($msgAlert == ''){
			$msgComplete = 'Книга успешно добавлена';
						
			$db = new PDO ('mysql:host=localhost;dbname=books','root','');
			$query = $db->prepare("INSERT INTO list (title, author, genre, descr, cost) VALUES(:title, :author, :genre, :descr, :cost)");
			$values = [
			    'title' => $title,
				'author' => $authors,
				'genre' => $genre,
				'cost' => $cost,
				'descr' => $descr
			];
			$query->execute($values);
			$title = '';
			$genre = '';
			$cost = '';
			$descr = '';
			$author[0] = '';
		}
    } else {
		$title = '';
		$authors = '';
		$genre = '';
		$cost = '';
		$descr = '';
		$author[0] = '';
	}	
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Welcome, admin</title>
  <style>
    input {
		margin: 5px 0;
	}
	.alert{
		color: red;
	}
	.complete{
		color: green;
	}
	table, td {
		border: solid 1px black;
	}
	td {
		padding: 5px;
	}
	thead {
		text-transform: uppercase;
		font-weight: bold;
	}
	.hideDiv {
		display: none;
		position: absolute;
		background-color: grey;
		top: 40%;
		left: 40%;
		padding: 20px 20px;
	}
	.hideDiv>form>button{
		margin: 10px 10px 0 0;
	}
  </style>
</head>
<body>
  <form method="post">
    Название
	<input type="text" name="title" value="<?php echo $title;?>"><br>
	Количество авторов:
	<select name="quantity">
	    <?php
		    if (count($_POST)>0 && $msgAlert != ''){
				for($i = 1; $i < 5; $i++){
					if ($i == $_POST['quantity']){
						echo "<option selected value = \"$i\">$i</option>";
					} else {
						echo "<option value = \"$i\">$i</option>";
					}
			    }
			} else {
		        for($i = 1; $i < 5; $i++){
					if ($i == 1) {
						echo "<option selected=\"selected\" value = \"$i\">$i</option>";
					} else {
						echo "<option value = \"$i\">$i</option>";
					}
			    }
			}
		?>
	</select>
	
	Автор
	<input type="text" name="author1" value="<?php echo $author[0];?>">
	<span id="authorsInput">
	<?php
	    if (count($_POST)>0 && $msgAlert != ''){
			for($i=1; $i<$_POST['quantity']; $i++){
				$n = $i+1;
				echo "<input type=\"text\" name=\"author$n\" value=\"$author[$i]\"> ";
			}
		}
	?>
	</span>	
	<br>
	Жанр
	<input type="text" name="genre" value="<?php echo $genre;?>"><br>
	Цена
	<input type="text" name="cost" value="<?php echo $cost;?>"><br>
	Описание
	<textarea rows="3" name="descr"><?php echo $descr;?></textarea><br>
	<input type="submit" value="Добавить">
	<span id="ajaxMsg" class="complete"><?php echo $msgComplete;?></span><br>
</form>
<span class="alert"><?php echo $msgAlert;?></span>

<hr>
<table>
  <thead>
    <tr>
	  <td>ID</td>
	  <td>Название</td>
	  <td>Авторы</td>
	  <td>Жанр</td>
	  <td>Цена</td>
	  <td>Описание</td>
	  <td>Управление</td>
	</tr>
  </thead>
  <tbody>
<?php
    $db = new PDO ('mysql:host=localhost;dbname=books','root','');
	foreach ($db->query('SELECT * FROM list') as $row) {
		$authorsList = '';
		$authorsListArr = explode('|',$row['author']);
		$currId = $row['id_list'];
		echo "<tr id=\"tr".$row['id_list']."\"><td>".$row['id_list']."</td>";
		echo "<td>".$row['title']."</td>";
		for ($i=0; $i<count($authorsListArr); $i++){
			$authorsList .= $authorsListArr[$i];
			if ($i < (count($authorsListArr)-2)){
				$authorsList .=', ';
			}
		}
		echo "<td>".$authorsList."</td>";
		echo "<td>".$row['genre']."</td>";
		echo "<td>".$row['cost']."</td>";
		echo "<td>".$row['descr']."</td>";
		echo "<td><button id=\"del_$currId\">Удалить</button><button id=\"edit_$currId\">Изменить</button></td></tr>";
	}
?>
  </tbody>
</table>
<div class="hideDiv">
<form action="redirect.php" method="post">
    Название
	<input type="text" name="titleHide" value=""><br>
	Количество авторов:
	<select name="quantityHide">
	    <?php
		    for($i = 1; $i < 5; $i++){
				echo "<option value = \"$i\">$i</option>";
			}
		?>
	</select><br>
	
	Автор
	<input type="text" name="authorHide1" value="">
	<span id="authorsInputHide"></span>	
	<br>
	Жанр
	<input type="text" name="genreHide" value=""><br>
	Цена
	<input type="text" name="costHide" value=""><br>
	Описание
	<textarea rows="3" name="descrHide"></textarea><br>
	<span id="hideMsgAlert" class="alert"></span>
	<button id="changeEanable">Изменить</button><button id="changeAbort">Отмена</button>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="js/functions.js"></script>
<script>
    var currentId;
    $(function(){
		$("body>form>select").on('change', function(){
			var optCount = this.value;
			$("#authorsInput").html("");
			for (var j=1; j<optCount; j++){
				var temp = j+1;
				$("#authorsInput").append("<input type=\"text\""+"name=\"author"+temp+"\" value=\"\"> ");
			}
		});
		$("div.hideDiv>form>select").on("change", function(){
			var optCount = this.value;
			var tempValue = Array(4);
			for (var i=0; i<4; i++) {
				var tempQuery = "#authorsInputHide>input:eq("+i+")";
				if ($(tempQuery).attr("value") != undefined){
					tempValue[i] = $(tempQuery).attr("value");
				} else {
					tempValue[i] ='';
				}
			}
			$("#authorsInputHide").html("");
			for (var j=1; j<optCount; j++){
				var temp = j+1;
				var k = j-1;
				$("#authorsInputHide").append("<br><input type=\"text\""+"name=\"authorHide"+temp+"\" value=\""+tempValue[k]+"\"> ");
			}
		});
		$("td>button").on("click", function(){
			var dataToSend = "name="+this.id;
			var splitId = this.id.split('_');
			if (splitId[0] == 'edit'){
				$("div.hideDiv").css("display", "initial");
			}
			currentId = splitId[1];
			$.ajax({
				url: "actions.php",
				type: "POST",
				data: dataToSend,
				success: function(msg){
					var splitMsg = msg.split('_');
					if (splitMsg.length == 2){
						$("#ajaxMsg").text("Запись №"+splitMsg[1]+" успешно удалена");
						splitMsg[1] = "#tr"+splitMsg[1];
						$(splitMsg[1]).remove();
					} else {
						hideDivFunc(splitMsg, msg);
					}
				}
			});
			return false;
		});
		$("#changeAbort").on('click', function(){
			$("input.toRemove").prev().remove();
			$("input.toRemove").remove();
			$("div.hideDiv>form>select>option").removeAttr("selected");
			$("div.hideDiv").css("display", "none");
			return false;
		});
		$("#changeEanable").on('click', function(){
			var dataToSend = $("div.hideDiv>form");
			var dataToSendArr = dataToSend.serializeArray();
			var alertMsg = '';
			$("#hideMsgAlert").html(alertMsg);
			for (var i=0; i<dataToSendArr.length; i++) {
				if (dataToSendArr[i].name == 'titleHide' && dataToSendArr[i].value.trim().length < 3) {
					alertMsg += "Название должно быть длиннее<br>";
				} else if (dataToSendArr[i].name == 'titleHide' && dataToSendArr[i].value.trim().length > 2) {
					var tempValue = dataToSendArr[i].value.trim();
				} else if (dataToSendArr[i].name == 'quantityHide') {
					for (var j=1; j<=dataToSendArr[i].value; j++){
						for (var k=0; k<dataToSendArr.length; k++) {
							var temp = "authorHide"+j;
							if (dataToSendArr[k].name == temp && dataToSendArr[k].value.trim().length < 3) {
								alertMsg += "Имя "+j+" автора должно быть длиннее<br>";
							}
						}
					}
				} else if (dataToSendArr[i].name == 'genreHide' && dataToSendArr[i].value.trim().length < 3) {
					alertMsg += "Жанр должен быть длиннее<br>";
				} else if (dataToSendArr[i].name == 'costHide' && dataToSendArr[i].value.trim().length < 1) {
					alertMsg += "Необходимо добавить цену<br>";
				} else if (dataToSendArr[i].name == 'costHide' && !isFinite(dataToSendArr[i].value.trim())) {
					alertMsg += "Цена должна быть числом<br>";
				} else if (dataToSendArr[i].name == 'descrHide' && dataToSendArr[i].value.trim().length < 10) {
					alertMsg += "Описание должно быть длиннее<br>";
				}
			} 
			if (alertMsg != '') {
				$("#hideMsgAlert").html(alertMsg);
				return false;
			} else {
				var goodDataToSend = dataToSend.serialize()+"&id="+currentId;
				$.ajax({
				    type: "POST",
					async: false,
				    url: "dbchange.php",
				    data: goodDataToSend,
				    success: function(msg){
						var receiveData = msg.split('-|-');
						var temp = "#tr"+receiveData[0];
						receiveData[2] = receiveData[2].split('|');
						var tempAuthors='';
						for (var j=0; j<(receiveData[2].length-1); j++) {
							(j == (receiveData[2].length-2)) ? tempAuthors += receiveData[2][j] : tempAuthors += receiveData[2][j]+', ';							
						}
						var tempHtml = "<td>"+receiveData[0]+"</td><td>"+receiveData[1]+"</td><td>"+tempAuthors+"</td><td>"+receiveData[3]+"</td><td>"+receiveData[4]+"</td><td>"+receiveData[5]+"</td>";
						tempHtml += "<td><button id=\"del_"+receiveData[0]+"\">Удалить</button>";
						tempHtml += "<button id=\"edit_"+receiveData[0]+"\">Изменить</button></td>";
						$(temp).html(tempHtml);
				    } 
			    });   
				$("input.toRemove").prev().remove();
			    $("input.toRemove").remove();
			    $("div.hideDiv>form>select>option").removeAttr("selected");
			    $("div.hideDiv").css("display", "none");
				return true;
			}
		});
	});
</script>
</body>
</html>
