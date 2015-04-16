<?php
session_start();
if(!session_is_registered(username)){
	header('Location: index.php');
};
//CHECK FOR MOD STATUS EVERY SINGLE FUCKING TIME.
if($_SESSION['access_level']>'1'){
	header('Location: ../index.php');
};
include('../includes/defines.php');
include('../includes/sql.php');
include('../includes/header.tpl');
$fam_name=$_POST['family_name'];

$sql_search_fam_name=mysql_query("SELECT id,user,server,family_name FROM `account_info` WHERE family_name OR user LIKE '%" . $fam_name . "%'");
if(!$sql_search_fam_name){
	die('Invalid query: ' . mysql_error());
};
$search_array=array();
//Build array.
while($row=mysql_fetch_array($sql_search_fam_name, MYSQL_ASSOC)){
	array_push($search_array, $row);
};
if($fam_name!=""){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Search Results</h1>";
				echo "<p>";
					if($search_array<=0){
						echo "No Results.";
					}else{
						echo "<table class='search_results'>";
							echo "<tr>";
								echo "<th>ID";
								echo "<th>Account Name";
								echo "<th>Family Name";
								echo "<th>Server";
							echo "</tr>";
							
							for($i=0,$size=count($search_array);$i<$size;$i++){
							echo "<tr align='left'>";
								echo "<td><a href='mod_edit.php?id=" . $search_array[$i][id] . "'>" .$search_array[$i][id]. "</a></td>";
								echo "<td><a href='mod_edit.php?id=" . $search_array[$i][id] . "'>" .$search_array[$i][user]. "</a></td>";
								echo "<td><a href='mod_edit.php?id=" . $search_array[$i][id] . "'>" .$search_array[$i][family_name]. "</a></td>";
								echo "<td>" .$search_array[$i][server]. "</td>";
							};
						echo "</table>";
					};
				echo "</p>";
				echo "<a class='launch_small2' href='" . WEBSITE . "/mod/index.php'>Back</a>";
			echo "</div>";
		echo "</div>";
};
include('../includes/footer.tpl');
?>