$(document).ready(function(){
	$('#edit_server').hide();
	$('#edit_password').hide();
	$('a#toggle').click(function(){
		$('#edit_server').toggle(300);
		return false;
	});
	$('a#toggle2').click(function(){
		$('#edit_password').toggle(300);
		return false;
	});
	var current_color=$('#colorselector').attr('name');
	$('#colorselector').ColorPicker({
		flat: true,
		color: current_color,
		onSubmit: function(hsb, hex, rgb, el){
			//set color to hex.
			$.post("acc_mgmt.php",{color:hex},function(data){location.reload();});
		},
		onChange: function(hsb, hex, rgb){
			$('#banner').css('backgroundColor', '#' + hex);
			$('#nav').css('backgroundColor', '#' + hex);
		}
	});
});
   var http_request = false;
   function makeRequest(url, parameters){
      http_request = false;
      if(window.XMLHttpRequest){
         http_request = new XMLHttpRequest();
         if(http_request.overrideMimeType) {
            http_request.overrideMimeType('text/html');
         }
      }else if(window.ActiveXObject){
         try{
            http_request = new ActiveXObject('Msxml2.XMLHTTP');
         }catch (e){
            try{
               http_request = new ActiveXObject('Microsoft.XMLHTTP');
            }catch (e){}
         }
      }
      if(!http_request){
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      http_request.onreadystatechange = alertContents;
      http_request.open('GET', url + parameters, true);
      http_request.send(null);
   }

   function alertContents(){
      if(http_request.readyState == 4){
         if(http_request.status == 200){
            //alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById('return_info').innerHTML = result;
			document.getElementById('return_info2').innerHTML = result;         
         }else{
            alert('There was a problem with the request.');
         }
      }
   }
   
   function get(obj){
      var getstr = "?";
      for(i=0; i<obj.childNodes.length; i++){ 
        if(obj.childNodes[i].tagName == 'INPUT'){
            if (obj.childNodes[i].type == "password") {
               getstr += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
            }
		}
         if(obj.childNodes[i].tagName == 'SELECT'){
            var sel = obj.childNodes[i];
            getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
         }
         
      }
      makeRequest('update_info.php', getstr);
   }
