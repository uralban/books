<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Welcome, customer</title>
</head>
<style>
    table, td {
		border: solid 1px black;
	}
	td {
		padding: 5px;
	}
	.customerHideDiv {
		display: none;
		position: absolute;
		background-color: #bbbbbb;
		top: 30%;
		left: 30%;
		padding: 20px 20px;
	}
	.longInput{
		width: 70%;
	}
	span.alertMsg{
		color: red;
	}
</style>
<body>
<form method='post'>
Выберите автора: 
<select name="selectAuthor" id="selectAuthor">
  <option value="all" selected="selected">ВСЕ</option>
  
</select>
или жанр: 
<select name="selectGenre" id="selectGenre">
  <option value="all" selected="selected">ВСЕ</option>
  
</select>
<input type="submit" value="Подобрать">
</form>
<hr>
<div id="resultDiv"></div>
<div class="customerHideDiv">
<table>
  <thead>
    <tr>
	  <td>Название</td>
	  <td>Авторы</td>
	  <td>Жанр</td>
	  <td>Цена</td>
	  <td>Описание</td>
	</tr>
  </thead>
  <tbody>
<!--<tr id="fullInfo"></tr>-->
  </tbody>
</table>
<br>
<form method='post'>
  ФИО
  <input name="customerName" type="text" class="longInput"><br><br>
  Адрес
  <input name="customerAddress" type="text" class="longInput"><br><br>
  Количество
  <select name="orderQuantity">
    <?php
	    for ($i=1; $i<=20; $i++){
			echo "<option value=\"".$i."\">".$i."</option>";
		}
	?>
  </select><br><br>
  <input type="submit" value="Заказать">
  <button id="closeButton">Отмена</button><br><br>
  <span class="alertMsg"></span>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>
$(function(){
	$.ajax({
		url: "readData.php",
		type: "POST",
		success: function(msg){
			msg = msg.split('$$$');
			msg[0] = msg[0].split('|');
			msg[1] = msg[1].split('|');
			for (var i=0; i<msg[0].length; i++){
				var newOption = "<option value=\""+msg[0][i]+"\">"+msg[0][i]+"</option>";
				$("#selectAuthor").append(newOption);
			}
			for (var i=0; i<msg[1].length; i++){
				var newOption = "<option value=\""+msg[1][i]+"\">"+msg[1][i]+"</option>";
				$("#selectGenre").append(newOption);
			}
		}
	});
	$("body>form").on("submit", function(){
		$.ajax({
			url: "formHandler.php",
			type: 'POST',
			data: $("body>form").serialize(),
			success: function(msg){
				$("#resultDiv").html(msg);
				$("td>button").on("click", function(){
					var sendDataArr = $(this).parent().parent().attr("id").split('_');
					var sendData = 'id='+sendDataArr[1];
					$.ajax({
						url: "orderInfo.php",
						type: 'POST',
						data: sendData,
						success: function(answer){
							answer = answer.split('_');
							answer[1] = answer[1].split('|');
							var answerAuthors = '';
							for (var i=0; i<answer[1].length-1; i++) {
								(i == 0) ? answerAuthors = answer[1][i] : answerAuthors += ', ' + answer[1][i];
							}
							var ourAnswer = "<tr id=\""+answer[5]+"\"><td>"+answer[0]+"</td><td>"+answerAuthors+"</td><td>"+answer[2]+"</td><td>"+answer[3]+"</td><td>"+answer[4]+"</td></tr>";
							$("div.customerHideDiv>table>tbody").html(ourAnswer);
							$("div.customerHideDiv").css("display", "initial");
						}
					});
				});
			}
		});
		return false;
	});
	$("div.customerHideDiv>form").on("submit", function(){
		var idOrderArr = $("div.customerHideDiv>table>tbody>tr").attr('id');
		$.ajax({
			url: 'orderHandler.php',
			type: 'post',
			data: $("div.customerHideDiv>form").serialize()+"&id="+idOrderArr,
			success: function(answer){
				if (answer != ''){
					$("span.alertMsg").html(answer);
				} else {
					$("span.alertMsg").html('');
					$("div.customerHideDiv").css("display", "none");
				}
			}
		});
		return false;
	});
	
	$("#closeButton").on("click", function() {
		$("div.customerHideDiv").css("display", "none");
		return false;
	});
});

</script>
</body>
</html>