<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
$account_id=$_SESSION['id'];
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');
?>
<?php
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Downloads</h1>";
				echo "<p>";
					echo "<a href='http://my.portcoimbra.com/download/MyPc_1.1[3.4b_NA]_Setup.zip'>SotNW 3.4 Client Mod</a> - Released 03/05/10<br>";
					echo "<a href='http://my.portcoimbra.com/download/MyPc_1.1[3.5.14_SG]_Setup.zip'>sGE 3.5.14 Client Mod</a> - Released 03/05/10<br>";
					echo "<a href='http://my.portcoimbra.com/download/MyPC_1.2[Universal]_Setup.zip'>Universal Client Mod (Supports: Sword2/IAH/AsiaSoft/rGE/vGE)</a> - Updated 11/13/10";
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>