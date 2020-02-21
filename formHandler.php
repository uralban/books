<?php
    $totalAnswer = '<table><thead><tr><td>Название</td><td>Авторы</td><td>Жанр</td><td></td></thead><tbody>';
    $db = new PDO ('mysql:host=localhost;dbname=books','root','');
	$searchCheck = false;
	foreach ($db->query('SELECT * FROM list') as $row) {
		$authorsListDbArr = explode('|',$row['author']);
		$authorsListArr = '';
		if($_POST['selectAuthor'] == 'all' && $_POST['selectGenre'] == 'all') {
			for($j=0; $j<(count($authorsListDbArr)-1); $j++){
				($j == (count($authorsListDbArr)-2)) ? $authorsListArr .= $authorsListDbArr[$j] : $authorsListArr .= $authorsListDbArr[$j].', ';					
			}
			$totalAnswer .= "<tr id=\"tr_".$row['id_list']."\"><td>".$row['title']."</td><td>".$authorsListArr."</td><td>".$row['genre']."</td><td><button>Подробнее</button></td>";
			$searchCheck = true;
		} else if($_POST['selectAuthor'] == 'all' && $_POST['selectGenre'] == $row['genre']) {
			for($j=0; $j<(count($authorsListDbArr)-1); $j++){
				($j == (count($authorsListDbArr)-2)) ? $authorsListArr .= $authorsListDbArr[$j] : $authorsListArr .= $authorsListDbArr[$j].', ';					
			}
			$totalAnswer .= "<tr id=\"tr_".$row['id_list']."\"><td>".$row['title']."</td><td>".$authorsListArr."</td><td>".$row['genre']."</td><td><button>Подробнее</button></td>";
			$searchCheck = true;			
		} else {
		    for($i=0; $i<(count($authorsListDbArr)-1); $i++){
			    if (($_POST['selectAuthor'] == $authorsListDbArr[$i] && $_POST['selectGenre'] == 'all') || ($_POST['selectAuthor'] == $authorsListDbArr[$i] && $_POST['selectGenre'] == $row['genre'])){
				
				    for($j=0; $j<(count($authorsListDbArr)-1); $j++){
					    ($j == (count($authorsListDbArr)-2)) ? $authorsListArr .= $authorsListDbArr[$j] : $authorsListArr .= $authorsListDbArr[$j].', ';					
				    }
								
				    $totalAnswer .= "<tr id=\"tr_".$row['id_list']."\"><td>".$row['title']."</td><td>".$authorsListArr."</td><td>".$row['genre']."</td><td><button>Подробнее</button></td>";
				    $searchCheck = true;
				    break;
			    }
		    }
		}
	}
	($searchCheck) ? $totalAnswer .= "</tbody></table>" : $totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
	
	echo $totalAnswer;
?>