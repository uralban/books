function clearFunction(id){
	id ='#'+id;
	$(id).html('');
}

function changeActiveButton(thisButton){
	var controlButtons = $("#controlButtons>button");
	for(var i=0; i<controlButtons.length; i++){
		$(controlButtons[i]).removeClass('controlButtonUnactive');
	}
	$(thisButton).addClass('controlButtonUnactive');
}

function deleteDataEvents(dbname){
	var queryButton = "button."+dbname+"Del";
	$(queryButton).on('click', function(){
		if (dbname == 'autors'){
			var nameToSend = 'autor';
		} else if (dbname == 'genre') {
			var nameToSend = dbname;
		} else if (dbname == 'book') {
			var nameToSend = 'name';
		}
		var dataToSend = $(this).parent().parent().children();
		var sendData = nameToSend+'='+$(dataToSend[0]).html();
		$.ajax({
			url: 'deleteData.php',
			type: 'POST',
			data: sendData,
			success: function(msg){
				if (msg == 'error'){
					(nameToSend == 'autor') ? alert('Удалите сначала все книги этого автора') : alert('Удалите сначала все книги этого жанра');
				} else {
					var trList = $("table>tbody>tr");
					for (var j=0; j<trList.length; j++){
						var childList = $(trList[j]).children();
						if ($(childList[0]).html() == msg){
							$(trList[j]).remove();
						}
					}									
				}								
			}
		});
	    return false;
	});
}

function selectEvents(){
	$("#quantityAutors, #quantityHideAutors").on('change', function(){
		var currentSelect;
		($(this).attr('id') == "quantityAutors") ? currentSelect = $("select.autor") : currentSelect = $("select.autorHide");
		for (var t=1; t<currentSelect.length; t++){
			$(currentSelect[t]).remove();
		}
		var autorsCount = this.value;
		var newAttr, selectClone;
		for (var i=autorsCount; i>=2; i--){
			selectClone = $(currentSelect[0]).clone();
			($(this).attr('id') == "quantityAutors") ? newAttr = 'autor'+i : newAttr = 'autorHide'+i;
			$(selectClone).attr('name', newAttr);
			$(currentSelect[0]).after(selectClone);
		}
	});
	$("#quantityGenre, #quantityHideGenre").on('change', function(){
		var currentSelect;
		($(this).attr('id') == "quantityGenre") ? currentSelect = $("select.genre") : currentSelect = $("select.genreHide");
		for (var t=1; t<currentSelect.length; t++){
			$(currentSelect[t]).remove();
		}
		var genreCount = this.value;
		var newAttr, selectClone;
		for (var i=genreCount; i>=2; i--){
			selectClone = $(currentSelect[0]).clone();
			($(this).attr('id') == "quantityGenre") ? newAttr = 'genre'+i : newAttr = 'genreHide'+i;
			$(selectClone).attr('name', newAttr);
			$(currentSelect[0]).after(selectClone);
		}
	});
}

function changeDataEvents(dbname){
	var queryButton = "button."+dbname+"Change";	
	$(queryButton).on('click', function(){
		$("#hideDiv").css('display', 'initial');
		var currentMarker = $(this).parent().parent().children();
		$("button.changeEanable").off();
		changeButtonEvent(currentMarker[0]);
	});
}

function formReset(type){
	var myQuery = '';
	(type == '') ? myQuery = '#addForm>form' : myQuery = '#hideDiv>form';
	$(myQuery).trigger("reset");
	myQuery = 'select.autor'+type;
	var currentSelect = $(myQuery);
	for (var t=1; t<currentSelect.length; t++){
		$(currentSelect[t]).remove();
	}
	myQuery = 'select.genre'+type;
	currentSelect = $(myQuery);
	for (var t=1; t<currentSelect.length; t++){
		$(currentSelect[t]).remove();
	}	
}

function abortEvents(){
	$("button.changeAbort").on('click', function(){
		$("#hideDiv").css('display', 'none');
		formReset('Hide');
		return false;
	});	
}

function changeButtonEvent(marker){
	$("button.changeEanable").on('click', function(){
		var dataToSend = $(this).parent().serialize();
		dataToSend += "&marker="+$(marker).html();
		$.ajax({
			url: 'formHandler.php',
			type: 'POST',
			data: dataToSend,
			success: function(msg){
				msg = msg.split('_');
				if (msg[0] == 'error'){
					for (var y=1; y<msg.length; y++){
						alert(msg[y]);
					}
				} else {
					dataToSend = dataToSend.split('&');
					for (var g=0; g<dataToSend.length; g++){
						dataToSend[g] = dataToSend[g].split('=');
					}
					if(dataToSend.length > 2) {
						for(var p=0; p<dataToSend.length-1; p++){
							if (dataToSend[p][0] == 'nameHideInput') {
								var newBookName = decodeURIComponent(dataToSend[p][1]);
								$(marker).html(newBookName);
							} else if (dataToSend[p][0] == 'costHide'){
								$(marker).next().next().next().html(decodeURIComponent(dataToSend[p][1]));
							} else if (dataToSend[p][0] == 'descrHide'){
								$(marker).next().next().next().next().html(decodeURIComponent(dataToSend[p][1]));
							}
						}
						var newDataToSend = "action=readAutorsGenre&id="+newBookName;
						$.ajax({
							url: 'readDataFromDb.php',
							type: 'POST',
							data: newDataToSend,
							success: function(msg){
								msg = msg.split('|');
								$(marker).next().html(msg[0]);
								$(marker).next().next().html(msg[1]);
							}
						});
					} else {
						$(marker).html(decodeURIComponent(dataToSend[0][1]));
					}
				}				
			}			
		});
		$("#hideDiv").css('display', 'none');
		formReset('Hide');
		return false;
	});
}

function mainLoadFun(activeButton){
	var firstDataToSend = "id="+activeButton.id;
	$.ajax({
		url: 'readDataFromDb.php',
		type: 'POST',
		data: firstDataToSend,
		success: function(msg){
			var splitMsg = msg.split('|');
			$("#addForm").html(splitMsg[0]);
			$("#itemsList").html(splitMsg[1]);
			$("#hideDiv").html(splitMsg[2]);	
			$("button.submitButton").on('click', function(){
				$("#addForm>form>span").remove();
				var dataToSend = $("div#addForm>form");
				$.ajax({
					url: 'formHandler.php',
					method: 'POST',
					data: dataToSend.serialize(),
					success: function(msg){
						msg = msg.split('_');
						var alertMes = '';
						if (msg[0] == 'error'){
							alertMes = "<span class=\"alert\"> ";
							for (var i=1; i<msg.length; i++){
								(i<msg.length-1) ? alertMes += msg[i]+". " : alertMes += msg[i];
							}
							alertMes += "</span>";
						} else {
							alertMes = "<span class=\"complete\"> "+msg[0]+"</span>";
							var newTr = "<tr><td>";
							dataToSend = dataToSend.serialize().split('&');
							for (var g=0; g<dataToSend.length; g++){
								dataToSend[g] = dataToSend[g].split('=');
							}
							if (dataToSend.length < 2){
								newTr += decodeURIComponent(dataToSend[0][1])+"</td><td>";								
							} else {
								newTr += decodeURIComponent(dataToSend[0][1])+"</td><td>";
								$.ajax({
									url: 'readDataFromDb.php',
									type: 'POST',
									async: false,
									data: 'action=readAutorsGenre&id='+decodeURIComponent(dataToSend[0][1]),
									success: function(answer){
										answer = answer.split('|');
										newTr += answer[0]+'</td><td>'+answer[1];										
									}
								});
								newTr += '</td><td>'+decodeURIComponent(dataToSend[dataToSend.length-2][1]);
								newTr += '</td><td>'+decodeURIComponent(dataToSend[dataToSend.length-1][1])+'</td><td>';
							}
							var myMarker;
							if (dataToSend[0][0] == 'bookAddInput'){
								myMarker = 'book';
							} else if (dataToSend[0][0] == 'autorsAddInput'){
								myMarker = 'autors';
							} else {
								myMarker = 'genre';
							}
							newTr += "<button class=\""+myMarker+"Del\">Удалить</button><button class=\""+myMarker+"change\">Изменить</button>";
							
							newTr +='</td></tr>';
							var tableRows = $("#itemsList>table>tbody").children();
							var myId = tableRows.length-1;
							$("#itemsList>table>tbody").append(newTr);
							formReset('');
							deleteDataEvents('autors');
							deleteDataEvents('genre');
							deleteDataEvents('book');
							changeDataEvents('autors');
							changeDataEvents('genre');
							changeDataEvents('book');
							abortEvents();
						}
						$("button.submitButton").after(alertMes);
					}
				});
				return false;
			});
			deleteDataEvents('autors');
			deleteDataEvents('genre');
			deleteDataEvents('book');
			selectEvents();
			changeDataEvents('autors');
			changeDataEvents('genre');
			changeDataEvents('book');
			abortEvents();			
		}
	});
	return false;
}