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
					var sendDataArr = $(this).parent().parent().children();
					var sendData = 'name='+$(sendDataArr[0]).html();
					$.ajax({
						url: "orderInfo.php",
						type: 'POST',
						data: sendData,
						success: function(answer){
							var newTr = '<tr><td>'+$(sendDataArr[0]).html()+'</td><td>'+$(sendDataArr[1]).html()+'</td><td>'+$(sendDataArr[2]).html()+'</td><td>'+answer+'</td></tr>';
							$("div.customerHideDiv>table>tbody").html(newTr);
							$("div.customerHideDiv").css("display", "initial");
						}
					});
				});
			}
		});
		return false;
	});
	$("div.customerHideDiv>form").on("submit", function(){
		var currentInfo = $("div.customerHideDiv>table>tbody>tr").children();
		var moreData = "&bookName="+$(currentInfo[0]).html()+"&autors="+$(currentInfo[1]).html()+"&genre="+$(currentInfo[2]).html()+"&cost="+$(currentInfo[3]).html()+"&descr="+$(currentInfo[4]).html();
		$.ajax({
			url: 'orderHandler.php',
			type: 'post',
			data: $("div.customerHideDiv>form").serialize()+moreData,
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