$(document).ready(function(){
	$("#add_new_group").hide();
	$("#delete_group").hide();
	$("#submit_add_group").click(function(){
		var Error2 = false;
		var GroupName = $("#group_name").val();
		var filter = /([a-zA-Z0-9_\-])/;
		if(filter.test(GroupName.value)){
			$("#error2").after('<span class="error2">Group Name contains invalid characters!</span>');
			Error = true;
		}
		if(GroupName == ''){
			$("#error2").after('<span class="error2">You must enter a Group Name!</span>');
			Error2 = true;
		}
		if(GroupName.lenght > 16){
			$("#error2").after('<span class="error2">Group Name cannot be longer than 16 characters!</span>');
			Error2 = true;
		}
		if(Error2 == false){
			$(this).hide();
			$("#add_new_group").before('<br><img src="http://dev.my.portcoimbra.com/img/loading.gif" alt="Loading2" id="loading2" />');
			$.post("grp_mgmt.php",
   				{ group_name: GroupName },
   					function(data){
						document.getElementById('add_new_group').innerHTML = data;
							$("#loading2").remove();
							$("#add_new_group").slideUp("slow", function(){
							$("#delete_group").remove();
							$("#add_new_group").remove();
							$("#simple_manage_group").fadeOut("slow").load("manage_grouptable.php?&reload=1").fadeIn("slow");
						});
   					}
				 );
		}
		return false;
	});
	$("#submit_delete_group").click(function(){
		var character_id=$(this).attr("name");
		var GroupName = $("#group").val();
			$(this).hide();
			$("#delete_group").before('<br><img src="http://dev.my.portcoimbra.com/img/loading.gif" alt="Loading3" id="loading3" />');
			$.post("grp_mgmt.php",
   				{ group_del_name: GroupName },
   					function(data){
						document.getElementById('delete_group').innerHTML = data;
						$("#loading3").remove();
						$("#delete_group").slideUp("slow", function(){
							$("#add_new_group").remove();
							$("#delete_group").remove();
							$("#simple_manage_group").fadeOut("slow").load("manage_grouptable.php?&reload=1").fadeIn("slow");
						});
   					}
				 );
		return false;
	});
});