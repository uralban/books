<?php
    $authorsList = '';
    $genreList = '';
	$totalAnswer = '';
	
	$db = new PDO ('mysql:host=localhost;dbname=books','root','');
	
	foreach ($db->query('SELECT * FROM list') as $row) {		
		if ($genreList == ''){
			$genreList .= $row['genre'];
		} else {
			$genreListArr = explode('|', $genreList);
			for ($i=0; $i<count($genreListArr); $i++) {
				if ($row['genre'] == $genreListArr[$i]) {
					$marker = true;
					break;
				} else {
					$marker = false;
				}
			}
			if (!$marker) {
				$genreList .= '|'.$row['genre'];
			}
		}
		$authorsListDbArr = explode('|',$row['author']);		
		for ($i=0; $i<(count($authorsListDbArr)-1); $i++){
			if ($authorsList == ''){
				$authorsList .= $authorsListDbArr[$i];
			} else {
				$marker = false;
				$authorsListArr = explode('|', $authorsList);
				for ($j=0; $j<count($authorsListArr); $j++) {
				    if ($authorsListDbArr[$i] == $authorsListArr[$j]){
					    $marker = true;
					    break;
				    } else {
					    $marker = false;
				    }
				}
				if (!$marker) {
					$authorsList .= '|'.$authorsListDbArr[$i];
				}
			} 
		}
	}
	$totalAnswer = $authorsList . "$$$" . $genreList;
	
	echo $totalAnswer;
	
?>