<?php
session_start();
if(!session_is_registered(username)){
	header('Location: ../index.php');
};
//CHECK FOR MOD STATUS EVERY SINGLE FUCKING TIME.
if($_SESSION['access_level']>'1'){
	header('Location: ../index.php');
};
include('../includes/defines.php');
include('../includes/sql.php');
include('../includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Mod Functions</h1>";
				echo "<p>";
					echo "Find User:";
					echo "<form name='form' method='post' action='mod_search.php'>";
                  	 	echo "Family Name or Account Email: <input name='family_name' type='text' id='family_name' />";
						echo "<input type='submit' value='Submit' />";
					echo "</form>";
					echo "<br><br>";
					echo "Edit Character:";
					echo "<br><br>";
					echo "<form name='form_char_id' method='post' action='mod_edit_char.php'>";
                  	 	echo "Chracter ID Number: <input name='char_id' type='text' id='char_id' />";
						echo "<input type='submit' value='Submit' />";
					echo "</form>";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('../includes/footer.tpl');
?>