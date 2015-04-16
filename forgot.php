<?php
include('includes/defines.php');
include('includes/sql.php');
include('includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
?>
					<b>Email Address:</b><br>
					<form name='form' method='post' action='auth/mail.php'>
					<input name='username' type='text' id='username'>
					<input type='submit' name='Submit' value='Submit'>
					</form>
<?php
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>