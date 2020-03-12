	$(function(){
		var curButt = $("#controlButtons>button");
		mainLoadFun(curButt[0]);
		
	    $("#controlButtons>button").on('click', function(){
			changeActiveButton(this);
			clearFunction('addForm');
			clearFunction('itemsList');
			clearFunction('hideDiv');
			$("#hideDiv").css('display', 'none');			
			mainLoadFun(this);			
	    });
	});