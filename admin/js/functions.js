var hideDivFunc = function (splitMsg, msg){
	$("div.hideDiv>form>input:eq(0)").attr("value", splitMsg[0]);
	var splitAuthorsArr = splitMsg[1].split('|');
	$("div.hideDiv>form>input:eq(1)").attr("value", splitAuthorsArr[0]);
	splitAuthorsArr.length = splitAuthorsArr.length-1;
	var temp = splitAuthorsArr.length - 1;
	var queryVar = "div.hideDiv>form>select>option:eq("+temp+")";
	$(queryVar).attr("selected", "selected");
		
	for (var i=1; i<splitAuthorsArr.length; i++) {
		var n = i+1;
		temp = "<br><input type=\"text\" name=\"authorHide"+n+"\""+" value=\""+splitAuthorsArr[i]+"\" class=\"toRemove\">";
		$("#authorsInputHide").append(temp);
	}
	queryVar = "div.hideDiv>form>input:eq(2)";
	$(queryVar).attr("value", splitMsg[2]);
	queryVar = "div.hideDiv>form>input:eq(3)";
	$(queryVar).attr("value", splitMsg[3]);
	$("div.hideDiv>form>textarea").html(splitMsg[4]);
}