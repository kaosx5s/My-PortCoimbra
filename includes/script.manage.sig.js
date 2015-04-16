$(document).ready(function(){
	$("#add_new_character").hide();
	$("#submit_add_char").click(function(){
		var Error = false;
		var CharacterName = $("#char_name").val();
		if(CharacterName == ''){
			$("#error").after('<span class="error">You must enter a character name! </span>');
			Error = true;
		}
		var CharacterJob = $("#char_job").val();
		var CharacterLevel = $("#char_lvl").val();
		if(CharacterLevel == ''){
			$("#error").after('<span class="error">You must enter a character level! </span>');
			Error = true;
		}
		var CharacterPromotion = $("#char_pro").val();
		if(Error == false){
			$(this).hide();
			$("#add_new_character").before('<img src="http://dev.my.portcoimbra.com/img/loading.gif" alt="Loading" id="loading" />');
			$.post("sig_mgmt.php",{char_name: CharacterName, char_job: CharacterJob, char_lvl: CharacterLevel, char_pro: CharacterPromotion},
   				function(data){
					document.getElementById('loading').innerHTML = data;
					if(data=='<font color=\'green\'>Success!</font>'){
						$("#loading").remove();
						$("#add_new_character").slideUp("slow", function(){
							$("#simple_manage").fadeOut("slow").load("manage_familytable.php?&reload=1").fadeIn("slow");
							$("#signature").fadeOut("slow").load("/sig/sig.php").fadeIn("slow");
							$("#add_char")[0].reset();
							$("#submit_add_char").css("display", "block");
						});
					}
   				}
			 );
			return false;
		}
	});
});