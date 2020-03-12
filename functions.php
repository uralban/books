<?php
    include 'config.php';
	
	function readDb($nameDb, $id, $item, $searchFromId = false){
		$db = new PDO (MYSQL_DB,LOGIN,PASS);
		$answerList = [];
		$i = 0;
		if ($id == 'all') {
			$myQuery = 'SELECT * FROM '.$nameDb;
		} else if ($id == 'find'){
			if ($nameDb == 'autors') {
				$columnName = 'autor';
			} else if ($nameDb == 'book'){
				($searchFromId) ? $columnName = 'id' : $columnName = 'name';
			} else if ($nameDb == 'book_autors'){
				$columnName = 'autors_id';
			} else if ($nameDb == 'book_genre'){
				$columnName = 'genre_id';
			} else {
				$columnName = $nameDb;
			}
			$myQuery = "SELECT * FROM ".$nameDb." WHERE ".$columnName."='".$item."'";
		} else {
			$myQuery = "SELECT * FROM ".$nameDb." AS a, book_".$nameDb." AS b WHERE b.book_id=".$id." AND a.id=b.".$nameDb."_id";
		}
		foreach ($db->query($myQuery) as $row){
			if ($nameDb == 'autors') {
				$answerList[0][$i] = $row['autor'];
				$answerList[1][$i] = $row['id'];
			} else if ($nameDb == 'genre'){
				$answerList[0][$i] = $row[$nameDb];
				$answerList[1][$i] = $row['id'];
			} else if ($nameDb == 'book_autors'){
				$answerList[0][$i] = $row['autors_id'];
				$answerList[1][$i] = $row['book_id'];
			} else if ($nameDb == 'book_genre'){
				$answerList[0][$i] = $row['genre_id'];
				$answerList[1][$i] = $row['book_id'];
			} else if ($nameDb == 'book') {
				$answerList[$i][0] = $row['name'];
				$answerList[$i][1] = $row['description'];
				$answerList[$i][2] = $row['cost'];
				$answerList[$i][3] = $row['id'];
			}
			$i++;
		}
		return $answerList;
	}
	
	function writeDb($nameDb, $values){		
		$db = new PDO (MYSQL_DB,LOGIN,PASS);
		if ($nameDb == 'autors') {
			$columnNames = 'autor';
		} else if($nameDb == 'genre'){
			$columnNames = $nameDb;
		} else if($nameDb == 'book'){
			$columnNames = 'name, description, cost';
		} else if($nameDb == 'book_autors'){
			$columnNames = 'book_id, autors_id';
		} else if($nameDb == 'book_genre'){
			$columnNames = 'book_id, genre_id';
		} 
		$myQuery = "INSERT INTO ".$nameDb." (".$columnNames.") VALUES(".$values.")";	
		$query = $db->prepare($myQuery);
		$query->execute();
	}
	
	function delDb($nameDb, $id){
		$db = new PDO (MYSQL_DB,LOGIN,PASS);
		if ($nameDb == 'book_autors' || $nameDb == 'book_genre'){
			$myQuery = "DELETE FROM ".$nameDb." WHERE book_id = '".$id."'";
		} else {
			$myQuery = "DELETE FROM ".$nameDb." WHERE id = '".$id."'";
		}
		$data = $db->prepare($myQuery);		
	    $data->execute();		
	}
	
	function updateDb($nameDb, $dataOld, $dataNew, $dataColumn){
		$db = new PDO (MYSQL_DB,LOGIN,PASS);
		if ($nameDb == 'autors'){
			$columnName = 'autor';
		} else if ($nameDb == 'genre'){
			$columnName = 'genre';
		} else if ($nameDb == 'book'){
			$columnName = 'name';
		}
		if ($dataColumn != '') {
			$columnName = $dataColumn;
		}
		($nameDb == 'book') ? $myQuery = "UPDATE ".$nameDb." SET ".$columnName."='".$dataNew."' WHERE ".$columnName."='".$dataOld."'" : $myQuery = "UPDATE ".$nameDb." SET ".$columnName."=".$dataNew." WHERE ".$columnName."='".$dataOld."'";		
		$data = $db->prepare($myQuery);
		$data->execute();
		
	}
	
	function controlButtonFunction($table, $itemRu){
		$answerForm = "<form method=\"POST\">".$itemRu." <input type=\"text\" name=\"".$table."AddInput\"><br>";
		$answerList = readDb($table, 'all', '');		
		if($table == 'book') {			
			$autorsList = readDb('autors', 'all', '');			
			$answerForm .= "Количество авторов: <select id=\"quantityAutors\" name=\"quantityAutors\">";
			for ($j=1; $j<=count($autorsList[0]); $j++){
				$answerForm .= "<option value = \"".$j."\">".$j."</option>";
			}
			$answerForm .= "</select>";			
			$answerForm .= selectAutorsGenreAdd($autorsList[0], 'autor', 1);			
			$answerForm .= "<br>";
			
			$genreList = readDb('genre', 'all', '');
			$answerForm .= "Количество жанров: <select id=\"quantityGenre\" name=\"quantityGenre\">";
			for ($j=1; $j<=count($genreList[0]); $j++){
				$answerForm .= "<option value = \"".$j."\">".$j."</option>";
			}
			$answerForm .= "</select>";
			$answerForm .= selectAutorsGenreAdd($genreList[0], 'genre', 1);	
			$answerForm .= "<br>";
			
			$answerForm .= "Цена <input type=\"text\" name=\"cost\" value=\"\"><br>";
			$answerForm .= "Описание <textarea rows=\"3\" name=\"descr\"></textarea><br>";
			
			$answerTable = "<table><thead><tr><td>Название</td><td>Авторы</td><td>Жанры</td><td>Цена</td><td>Описание</td>
			<td>Управление</td></tr></thead><tbody>";
			for ($i=0; $i<count($answerList); $i++) {
				$answerTable .= "<tr><td>".$answerList[$i][0]."</td><td>";
				$autorsTableList = '';
				$answerAutors = readDb('autors', $answerList[$i][3], '');
				for ($l=0; $l<count($answerAutors[0]); $l++){
					($l < count($answerAutors[0])-1) ? $autorsTableList .= $answerAutors[0][$l].', ' : $autorsTableList .= $answerAutors[0][$l];
				}
				$answerTable .= $autorsTableList."</td><td>";
				$genreTableList = '';
				$answerGenre = readDb('genre', $answerList[$i][3], '');
				for ($l=0; $l<count($answerGenre[0]); $l++){
					($l < count($answerGenre[0])-1) ? $genreTableList .= $answerGenre[0][$l].', ' : $genreTableList .= $answerGenre[0][$l];
				}
				$answerTable .= $genreTableList."</td><td>".$answerList[$i][2]."</td><td>".$answerList[$i][1]."</td>";
				$answerTable .= "<td><button class=\"".$table."Del\">Удалить</button><button class=\"".$table."Change\">Изменить</button></td></tr>";
				
				$answerHideDiv = "<form method=\"post\">Название <input type=\"text\" name=\"nameHideInput\"><br>Количество авторов: <select id=\"quantityHideAutors\" name=\"quantityHideAutors\">";
				for ($j=1; $j<=count($autorsList[0]); $j++){
				    $answerHideDiv .= "<option value = \"".$j."\">".$j."</option>";
			    }
				$answerHideDiv .= "</select>";			
			    $answerHideDiv .= selectAutorsGenreAdd($autorsList[0], 'autorHide', 1);			
			    $answerHideDiv .= "<br>";
				$answerHideDiv .= "Количество жанров: <select id=\"quantityHideGenre\" name=\"quantityHideGenre\">";
			    for ($j=1; $j<=count($genreList[0]); $j++){
				    $answerHideDiv .= "<option value = \"".$j."\">".$j."</option>";
			    }
			    $answerHideDiv .= "</select>";
			    $answerHideDiv .= selectAutorsGenreAdd($genreList[0], 'genreHide', 1);	
			    $answerHideDiv .= "<br>";
				$answerHideDiv .= "Цена <input type=\"text\" name=\"costHide\" value=\"\"><br>";
			    $answerHideDiv .= "Описание <textarea rows=\"3\" name=\"descrHide\"></textarea><br>";
			}			
		} else {
			$answerTable = "<table><thead><tr><td>".$itemRu."</td><td>Управление</td></tr></thead><tbody>";
			for ($i=0; $i<count($answerList[0]); $i++){
				$answerTable .= "<tr><td>".$answerList[0][$i]."</td><td><button class=\"".$table."Del\">Удалить</button><button class=\"".$table."Change\">Изменить</button></td></tr>";
			}
			$answerTable .= "</tbody></table>";
			
			$answerHideDiv = "<form method=\"post\">".$itemRu." <input type=\"text\" name=\"".$table."HideInput\"><br>";
		}
		$answerForm .= "<button class=\"submitButton\">Добавить</button></form>";
		$answerHideDiv .= "<button class=\"changeEanable\">Изменить</button><button class=\"changeAbort\">Отмена</button></form>";
		
		$answer = $answerForm.'|'.$answerTable.'|'.$answerHideDiv;
	    return $answer;
	}
	
	function selectAutorsGenreAdd($itemsList, $itemType, $i){
		$answer = "<select name=\"".$itemType.$i."\" class=".$itemType.">";
		for($k=0; $k<count($itemsList); $k++){
			$answer .= "<option value=\"".$itemsList[$k]."\">".$itemsList[$k]."</option>";
		}
		$answer .= "</select>";
	    return $answer;
	}
	
	function checkForm($data, $type, $action){
		if (!is_array($data)){
			$data = explode('|', $data);
			$check = checkData($data[0], $type);
			if (strlen($check)>5) {
				return $check;
			} else {
				$data[0] = "'".$data[0]."'";
				($action == 'write') ? writeDb($type, $data[0]) : updateDb($type, $data[1], $data[0], '');
				return 'Значение успешно добавлено';
			}
		} else {
			if (count($data) == 6) {
				$oldData = readDb('book', 'find', $data[5]);				
			}			
			$allertMsg = 'error';
			$check = checkData($data[0], $type);
			if (strlen($check)>5) {
				$allertMsg = $check;
			}
			if (!is_numeric($data[1])){
				(strlen($data[1])>1) ? $allertMsg .= '_Цена должна быть числом' : $allertMsg .= '_Необходимо назначить цену';
			}
			if (strlen($data[2])<10) {
				(strlen($data[2])>1) ? $allertMsg .= '_Описание должно быть длиннее' : $allertMsg .= '_Необходимо добавить описание';
			}
			if (strlen($allertMsg)>5){
				return $allertMsg;
			} else {
				$data[1] = round($data[1], 2);
				$dataToAdd = "'".$data[0]."', '".$data[2]."', '".$data[1]."'";
				if ($action == 'write'){
					writeDb($type, $dataToAdd);
				} else {
					updateDb($type, $oldData[0][0], $data[0], '');					
					updateDb($type, $oldData[0][2], $data[1], 'cost');
					updateDb($type, $oldData[0][1], $data[2], 'description');		
				}				
				$answer = readDb('book', 'find', $data[0]);
				$currentBookId = $answer[0][3];
				if ($action != 'write'){
					delDb('book_autors', $currentBookId);
					delDb('book_genre', $currentBookId);
				}
				for($g=0; $g<count($data[3]); $g++){
					$answer = readDb('autors', 'find', $data[3][$g]);
					$currentAutorId = $answer[1][0];
					$dataToAdd = "'".$currentBookId."', '".$currentAutorId."'";
					writeDb('book_autors', $dataToAdd);
				}
				for($g=0; $g<count($data[4]); $g++){
					$answer = readDb('genre', 'find', $data[4][$g]);
					$currentGenreId = $answer[1][0];
					$dataToAdd = "'".$currentBookId."', '".$currentGenreId."'";
					writeDb('book_genre', $dataToAdd);
				}
				return 'Книга успешно добавлена';
			}			
		}	
	}
	
	function checkData($data, $type){
		$allertMsg = 'error';
		if (strlen($data)<3){
			($type == 'book') ? $allertMsg .= '_Название должно быть длиннее' : $allertMsg .= '_Значение должно быть длиннее';
		}
		$checkAnswer = readDb($type, 'find', $data);
		if (count($checkAnswer)>=1){
			($type == 'book') ? $allertMsg .= '_Название должно быть уникальным' : $allertMsg .= '_Значение уже есть в базе';
		}
		return $allertMsg;
	}
	
	function arrayUnique($myArray){
		$newArray = [];
		for($i=0; $i<count($myArray); $i++){
			$marker = false;
			if (count($newArray)<1) {
				$newArray[0] = $myArray[$i];
			} else {
				for($j=0; $j<count($newArray); $j++){
					if ($myArray[$i] == $newArray[$j]){
						$marker = true;
						break;
					}
				}
				if (!$marker) {
					array_push($newArray, $myArray[$i]);
				}
			}
		}
		return $newArray;
	}
	
	function readAutorsGenre($bookName){
		$dataComplete = '';
		$answer = readDb('book', 'find', $bookName);
		$bookId = $answer[0][3];
		$answer = readDb('autors', $bookId, '');
		for ($i=0; $i<count($answer[0]); $i++){
			($i<count($answer[0])-1) ? $dataComplete .= $answer[0][$i].', ' : $dataComplete .= $answer[0][$i].'|';
		}
		$answer = readDb('genre', $bookId, '');
		for ($i=0; $i<count($answer[0]); $i++){
			($i<count($answer[0])-1) ? $dataComplete .= $answer[0][$i].', ' : $dataComplete .= $answer[0][$i];
		}
		return $dataComplete;
	}
	
?>