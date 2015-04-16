<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/defines.php');
include('includes/sql.php');
include('includes/header.tpl');
$fam_name=$_POST['family_name'];
$server=$_POST['server'];


$sql_search_fam_name=mysql_query("SELECT server,url_name,family_name FROM `account_info` WHERE family_name LIKE '%" . $fam_name . "%'");
if(!$sql_search_fam_name){
	die('Invalid query: ' . mysql_error());
};
$search_array=array();
//Build array.
while($row=mysql_fetch_array($sql_search_fam_name, MYSQL_ASSOC)){
	array_push($search_array, $row);
};

$sql_search_server=mysql_query("SELECT server,url_name,family_name FROM `account_info` WHERE `server` = '$server' AND `family_name` IS NOT NULL ORDER BY `family_name` ASC");
if(!$sql_search_server){
	die('Invalid query: ' . mysql_error());
};
$search_array_server=array();
//Build array.
while($row=mysql_fetch_array($sql_search_server, MYSQL_ASSOC)){
	array_push($search_array_server, $row);
};



if($fam_name!=""){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Search Results</h1>";
				echo "<p>";
				echo "<a class='launch_small2' href='" . WEBSITE . "/main.php'>Back</a>";
					if($search_array<=0){
						echo "No Results.";
					}else{
						echo "<table class='search_results'>";
							echo "<tr>";
								echo "<th>Family Name";
								echo "<th>Server";
								echo "<th>URL";
							echo "</tr>";
							
							for($i=0,$size=count($search_array);$i<$size;$i++){
							echo "<tr align='left'>";
								echo "<td>" .$search_array[$i][family_name]. "</td>";
								echo "<td>" .$search_array[$i][server]. "</td>";
								if($search_array[url_name]!=""){
									echo "<td><a href='" . WEBSITE . "/profile/" . $search_array[$i]['server'] . "/" . $search_array[$i]['url_name'] . "/' target='blank'>" . WEBSITE . "/profile/" . $search_array[$i]['server'] . "/" . $search_array[$i]['url_name'] . "/</a></td>";
								}else{
									echo "<td><a href='" . WEBSITE . "/profile/" . $search_array[$i]['server'] . "/" . $search_array[$i]['family_name'] . "/' target='blank'>" . WEBSITE . "/profile/" . $search_array[$i]['server'] . "/" . $search_array[$i]['family_name'] . "/</a></td>";
								};
							};
						echo "</table>";
					};
				echo "</p>";
			echo "</div>";
		echo "</div>";
};

if($server!=""){
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>Search Results</h1>";
				echo "<p>";
				echo "<a class='launch_small2' href='" . WEBSITE . "/main.php'>Back</a>";
					if($search_array_server<=0){
						echo "No Results.";
					}else{
						echo "<table class='search_results'>";
							echo "<tr>";
								echo "<th>Family Name";
								echo "<th>Server";
								echo "<th>URL";
							echo "</tr>";
	
							for($i=0,$size=count($search_array_server);$i<$size;$i++){
								echo "<tr align='left'>";
									echo "<td>" .$search_array_server[$i][family_name]. "</td>";
									echo "<td>" .$search_array_server[$i][server]. "</td>";
									if($search_array_server[$i][url_name]!=""){
										echo "<td><a href='" . WEBSITE . "/profile/" . $search_array_server[$i]['server'] . "/" . $search_array_server[$i]['url_name'] . "/' target='blank'>" . WEBSITE . "/profile/" . $search_array_server[$i]['server'] . "/" . $search_array_server[$i]['url_name'] . "/</a></td>";
									}else{
										echo "<td><a href='" . WEBSITE . "/profile/" . $search_array_server[$i]['server'] . "/" . $search_array_server[$i]['family_name'] . "/' target='blank'>" . WEBSITE . "/profile/" . $search_array_server[$i]['server'] . "/" . $search_array_server[$i]['family_name'] . "/</a></td>";
									};
							};
						echo "</table>";
					};
				echo "</p>";
			echo "</div>";
		echo "</div>";
};
include('includes/footer.tpl');
?>