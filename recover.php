<?php
include('includes/defines.php');
include('includes/sql.php');
include('includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Password Recovery</h1>";
				echo "<p>";
?>
					<b>Forgot Password:</b><br>
					<center><a class='launch_small' href='forgot.php'>Click Here</a></center>
					<br>
					<b>Password Reset:</b><br>
					<form name='form' method='post' action='auth/reset.php'>
						Username (email address):<br>
					<input name='username' type='text' id='username'>
						<br>Reset Key:<br>
					<input name='password' type='text' id='password'>
						<br>New Password (6 characters minimum):<br>
					<input name='newpass' type='password' id='newpass'>
					<input type='submit' name='Submit' value='Submit'>
					</form>
<?php
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>