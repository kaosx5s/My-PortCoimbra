<?php
	if(isset($_POST)){
		include('../includes/sql.php');
		
		$e=mysql_query("SELECT user FROM account_info WHERE user='" . $_POST['em'] . "'");
		if(mysql_num_rows($e) == 0){
			echo "true";
		}else{
			echo "bemail";
		};
	}else{
		echo "false";
	}
?>