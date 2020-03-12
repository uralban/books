<?php
    include 'functions.php';	
	
	$totalAnswer = '<table><thead><tr><td>Название</td><td>Авторы</td><td>Жанры</td><td></td></thead><tbody>';
	if($_POST['selectAuthor'] == 'all' && $_POST['selectGenre'] == 'all'){
		$answer = readDb('book', 'all', '');		
		for($i=0; $i<count($answer); $i++){
			$totalAnswer .= "<tr><td>".$answer[$i][0]."</td><td>";
			$answerData = readAutorsGenre($answer[$i][0]);
			$answerData = explode('|', $answerData);
			$totalAnswer .= $answerData[0]."</td><td>".$answerData[1];			
			$totalAnswer .= "</td><td><button>Подробнее</button></td></tr>";			
		}		
	} else if($_POST['selectAuthor'] != 'all' && $_POST['selectGenre'] == 'all'){
		$answer = readDB('autors', 'find', $_POST['selectAuthor']);		
		$booksId = readDb('book_autors', 'find', $answer[1][0]);
		if (count($booksId)<1){
			$totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
		} else {
			for($i=0; $i<count($booksId[1]); $i++){
				$bookAnswer = readDb('book', 'find', $booksId[1][$i], true);
				$totalAnswer .= "<tr><td>".$bookAnswer[0][0]."</td><td>";
				$answerData = readAutorsGenre($bookAnswer[0][0]);
			    $answerData = explode('|', $answerData);
			    $totalAnswer .= $answerData[0]."</td><td>".$answerData[1];			
			    $totalAnswer .= "</td><td><button>Подробнее</button></td></tr>";				
			}
		}
	} else if($_POST['selectAuthor'] == 'all' && $_POST['selectGenre'] != 'all'){
		$answer = readDB('genre', 'find', $_POST['selectGenre']);		
		$booksId = readDb('book_genre', 'find', $answer[1][0]);
		if (count($booksId)<1){
			$totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
		} else {
			for($i=0; $i<count($booksId[1]); $i++){
				$bookAnswer = readDb('book', 'find', $booksId[1][$i], true);
				$totalAnswer .= "<tr><td>".$bookAnswer[0][0]."</td><td>";
				$answerData = readAutorsGenre($bookAnswer[0][0]);
			    $answerData = explode('|', $answerData);
			    $totalAnswer .= $answerData[0]."</td><td>".$answerData[1];			
			    $totalAnswer .= "</td><td><button>Подробнее</button></td></tr>";				
			}
		}
	} else {
		$answerAutors = readDB('autors', 'find', $_POST['selectAuthor']);		
		$booksIdAutors = readDb('book_autors', 'find', $answerAutors[1][0]);
		if (count($booksIdAutors)<1){
			$totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
		} else {
			$answerGenre = readDB('genre', 'find', $_POST['selectGenre']);
			$booksIdGenre = readDb('book_genre', 'find', $answerGenre[1][0]);
			if (count($booksIdGenre)<1){
				$totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
			} else {
				for($m=0; $m<count($booksIdAutors[1]); $m++){
					for($n=0; $n<count($booksIdGenre[1]); $n++){
						if ($booksIdAutors[1][$m] == $booksIdGenre[1][$n]){
							$bookAnswer = readDb('book', 'find', $booksIdAutors[1][$m], true);
							$totalAnswer .= "<tr><td>".$bookAnswer[0][0]."</td><td>";
							$answerData = readAutorsGenre($bookAnswer[0][0]);
							$answerData = explode('|', $answerData);
							$totalAnswer .= $answerData[0]."</td><td>".$answerData[1];
							$totalAnswer .= "</td><td><button>Подробнее</button></td></tr>";
						}
					}
				}
			}				
		}
	}
	if(strlen($totalAnswer) < 110){
		$totalAnswer .= "<tr><td colspan=\"4\" style=\"color:red\">К сожалению, ничего не нашлось</td></tr></tbody></table>";
	}	
	echo $totalAnswer;
?>