<?php

if($_SERVER['REQUEST_URI']=='/index.php'){
	//display login info only
}else if($_SERVER['REQUEST_URI']=='/main.php'){
?>
		<div id="main">
			<div id='info' class='info'>
				Information
			</div>
			<div id='content'>
				<p>Welcome to the PortCoimbra.com Family Management System and Signature Generator!</p>
				<p>If you require assistance please use the 'contact us' link in the top navigation.<br>
				Many basic questions are covered in our FAQ! Please read through it before posting on our fourms.</p>
				<p>Thanks,<br>~PortCoimbra Admin.</p>
			</div>
		</div>
<?php
};
?>