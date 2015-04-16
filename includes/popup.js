var popupStatus = 0;
function loadPopup(character_id){
	if(popupStatus==0){
		$("#character_info").load("character_profile.php?&char="+character_id).fadeIn("slow");
		centerPopup();
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		popupStatus = 1;
	}
}
function disablePopup(){
	if(popupStatus==1){
		$("#character_info").fadeOut("slow");
		$("#backgroundPopup").fadeOut("slow");
		$("#character_profile").remove();
		$("#character_info").remove();
		popupStatus = 0;
	}
}
function centerPopup(){
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#character_info").height();
	var popupWidth = $("#character_info").width();
	$("#character_info").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2-50,
		"left": windowWidth/2-popupWidth/2
	});
	$("#backgroundPopup").css({
		"height": windowHeight
	});	
}