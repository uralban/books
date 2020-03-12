<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Welcome, customer</title>
</head>
<link href="css/customer.css" rel="stylesheet" />
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
	  <td>Жанры</td>
	  <td>Цена</td>
	  <td>Описание</td>
	</tr>
  </thead>
  <tbody>
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
<script src="js/customer.js"></script>

</body>
</html>