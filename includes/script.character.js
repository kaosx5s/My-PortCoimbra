$(document).ready(function(){
	$('[image_list_tooltip]').each(function(){
	    $(this).qtip({content:{url:'json_image_list.php',data:{id_img:$(this).attr('image_list_tooltip')},method:'post'},style:{width:490},show:'mouseover',hide:'mouseout',show:{solo:false},position:{corner:{target:'topMiddle',tooltip:'bottomMiddle'}}}); 
	});
	$('.edit_dropdown_sort_num').editable('sig_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		data   : "{'21':'Unsorted','1':'1','2':'2','3':'3','4':'4','5':'5','6':'6','7':'7','8':'8','9':'9','10':'10','11':'11','12':'12','13':'13','14':'14','15':'15','16':'16','17':'17','18':'18','19':'19','20':'20','selected':'<?php echo $sort_num; ?>'}",
		type   : 'select'
	});
	$('.edit_dropdown_grp_sort_num').editable('grp_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		data   : "{'71':'Unsorted','1':'1','2':'2','3':'3','4':'4','5':'5','6':'6','7':'7','8':'8','9':'9','10':'10','11':'11','12':'12','13':'13','14':'14','15':'15','16':'16','17':'17','18':'18','19':'19','20':'20','21':'21','22':'22','23':'23','24':'24','25':'25','26':'26','27':'27','28':'28','29':'29','30':'30','31':'31','32':'32','33':'33','34':'34','35':'35','36':'36','37':'37','38':'38','39':'39','40':'40','41':'41','42':'42','43':'43','44':'44','45':'45','46':'46','47':'47','48':'48','49':'49','50':'50','51':'51','52':'52','53':'53','54':'54','55':'55','56':'56','57':'57','58':'58','59':'59','60':'60','61':'61','62':'62','63':'63','64':'64','65':'65','66':'66','67':'67','68':'68','69':'69','70':'70','selected':'<?php echo $grp_sort_num; ?>'}",
		type   : 'select'
	});	
	$('.edit_dropdown_pro').editable('sig_mgmt.php', {
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		data   : "{'0':'None','1':'Veteran','2':'Expert','3':'Master','4':'High Master','5':'Grand Master','selected':'<?php echo $pro; ?>'}",
		type   : 'select'
	});
	$('.edit_dropdown_in_sig').editable('sig_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		data   : "{'0':'No','1':'Yes','selected':'<?php echo $in_sig; ?>'}",
		type   : 'select'
	});
	$('.edit_dropdown_custom_sig_image').editable('sig_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		loadurl : 'json_image_list.php?id='+$(this).attr("name"),
		loadtype:'POST',
		type   : 'select'
	});
	$('.edit_dropdown_job_list').editable('sig_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		loadurl : 'json_image_list.php?joblist=1',
		loadtype:'GET',
		type   : 'select'
	});
	$('.edit_dropdown_group_list').editable('grp_mgmt.php',{
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		loadurl : 'json_image_list.php?grouplist=1',
		loadtype:'GET',
		type   : 'select'
	});
	$('.edit').editable('sig_mgmt.php', {
		width:'75',
		style:'inherit',
		cancel:'<img src="../img/cancel.jpg">',
		submit:'<img src="../img/save.jpg">',
		indicator:'<img src="../img/loading.gif">',
		tooltip:'Click to edit...',
		data: function(value, settings) {
			var retval = '';
			return retval;
		}
	});
	$("#popupClose").click(function(){
		var character_id=$(this).attr("name");
		disablePopup();
		$("#signature").fadeOut("fast").load("/sig/sig.php").fadeIn("fast");
		$("#add_new_group").remove();
		$("#delete_group").remove();
		$("#add_new_group_form").remove();
		$("#delete_group_form").remove();
		$("#simple_manage_group").fadeOut("fast").load("manage_grouptable.php?&reload=1").fadeIn("fast");
		$("#group_signature").fadeOut("fast").load("/sig/grp_sig.php?&char="+character_id).fadeIn("fast");
		$("#simple_manage").fadeOut("fast").load("manage_familytable.php?&reload=1").fadeIn("fast");
		$("#SigPosition").fadeOut("fast").load("manage.php? #SigPosition").fadeIn("fast");

	});
});