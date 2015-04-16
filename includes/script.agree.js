var checkobj
function agreesubmit(el){
	checkobj=el
	if(document.all||document.getElementById){
		for(i=0;i<checkobj.form.length;i++){
			var tempobj=checkobj.form.elements[i]
			if(tempobj.type.toLowerCase()=="submit")
				tempobj.disabled=!checkobj.checked
			}
		}
	}

function defaultagree(el){
	if(!document.all&&!document.getElementById){
		if(window.checkobj&&checkobj.checked)
			return true
		else{
			alert("Please read/accept terms to submit form")
			return false
		}
	}
}
document.forms.agreeform.agreecheck.checked=false