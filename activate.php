<?php
include('includes/defines.php');
include('includes/sql.php');
include('includes/header.tpl');
include('includes/main.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Information</h1>";
				echo "<p>";
					echo "<b>Account Activation:</b><br>";
					echo "<form name='form' method='post' action='auth/activation.php'>";
						echo "Username (Email Address):<br>";
					echo "<input name='username' type='text' id='username'>";
						echo "<br>Activation Key:<br>";
					echo "<input name='key' type='text' id='key'>";
					echo "<input type='submit' name='Submit' value='Submit'>";
					echo "</form>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
include('includes/footer.tpl');
?>