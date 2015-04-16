var ddmenuitem=0;
function jsddm_open(){
	jsddm_close();
	ddmenuitem = $(this).find('ul').css('visibility', 'visible');
}
function jsddm_close(){if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}
function ShowHide_SigSort(){
	$("#SigPosition").animate({"height": "toggle"}, { duration: 250 });
};
function ShowHide_AddNew(){
	$("#add_new_character").animate({"height": "toggle"}, { duration: 250 });
};
function ShowHide_AddGroup(){
	$("#add_new_group").animate({"height": "toggle"}, { duration: 250 });
};
function ShowHide_DelGroup(){
	$("#delete_group").animate({"height": "toggle"}, { duration: 250 });
};
$("*[id^=qtip-]").each(function(){
	$(this).remove();
});
$(document).ready(function(){
	$("#add_new_character").hide();
	$("#character_info").hide();
	$("#SigPosition").hide();
	$("*[id^=jsddm]").hide();
	$(function(){
		$("#SigPosition ul").sortable({items:'li',revert:'invalid',opacity:0.6,cursor:'move',tolerance:'pointer',update:function(){
			var order = $(this).sortable("serialize") + '&action=update_char_sort_num';
			$.post("sig_sort.php", order, function(theResponse){
				$("#signature").fadeOut("slow").load("/sig/sig.php").fadeIn("slow");
				$("#simple_manage").fadeOut("slow").load("manage_familytable.php?&reload=1").fadeIn("slow");
			});
		}
		});
	});
	$('[tooltip]').each(function(){
	    $(this).qtip({content:$(this).attr('tooltip'),show:'mouseover',hide:'mouseout',style:{textAlign:'center',width:{min:175}},show:{solo:true},position:{corner:{target:'topMiddle',tooltip:'bottomMiddle'}}}); 
	}); 
	$("#signature").qtip({
		content:'This is your current signature!<br>It updates dynamically every time you change a characters information.',
		show:{solo:true},adjust:{screen:true},
		position:{corner:{target:'rightTop',tooltip:'rightBottom'}}
	});
	$("#simple_manage_group").qtip({
		content:'These are your groups.<br>Groups can be created and deleted at will. Each group can have up to 70 characters.<br><b>(Note: A character can only join one group at a time.)</b>',
		show:{solo:true},adjust:{screen:true},
		position:{corner:{target:'rightTop',tooltip:'rightBottom'}}
	});
	$("#simple_manage_pre").qtip({
		content:'These are your characters.<br>Roll over them with your mouse for basic information; click them to edit or change their information.<br><b>(Note: Characters with "No Image" will not attain an image till they are added to your signature.)</b>',
		show:{solo:true},adjust:{screen:true},
		position:{corner:{target:'rightTop',tooltip:'rightBottom'}}
	});
	$("#SigPosition").qtip({
		content:'These characters are sortable!<br>Click and drag them left or right to change their position in your signature.',
		show:{solo:true},adjust:{screen:true},
		position:{corner:{target:'leftTop',tooltip:'leftBottom'}}
	});
	$("*[id^=character_image]").hover(
		function(){
			var character_id=$(this).attr("name");
			$('ul[name|=' + character_id + ']').show();
			$('ul[name|=' + character_id + ']>li').bind('mouseover', jsddm_open);
			document.onclick = jsddm_close;
		},
		function(){
			var character_id=$(this).attr("name");
			$('ul[name|=' + character_id + ']').hide();
		}
	);
	$("*[id^=character_image]").dblclick(
		function(){
			var character_id=$(this).attr("name");
			$("#simple_manage_pre").qtip('hide');
			$("#simple_manage_pre").qtip('disable');
			loadPopup(character_id);	
		}		
	);
	$("*[id^=edit_character]").click(function(){
		$("#simple_manage_pre").qtip('hide');
		$("#simple_manage_pre").qtip('disable');
		var character_id=$(this).attr("name");
		loadPopup(character_id);
	});
	$("#SigPosition").hide();
});