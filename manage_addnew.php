<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
//session vars
$account_id=$_SESSION['id'];
//global vars
$ip=$_SERVER['REMOTE_ADDR'];
if($_GET['reload']=='1'){
	include('includes/sql.php');
	include('includes/defines.php');
	$account_color_info=@$account->get_account_color($account_id);
	$color=$account_color_info['color'];
	if($color==""){
		$color="000000";
	};
	echo "<script type='text/javascript' src='" . WEBSITE . "/includes/script.manage.sig.js'></script>";
};
$region_info=@$account->get_account_region($account_id);
				if(isset($Error)){
					echo '<span class="error">'.$Error.'</span>';
				};
				//Get List of Jobs
				$job_list_arr=@$datatable_job->get_job_list($region_info[region]);
				echo "<div id='simple_add_new'>";
					echo "<h1 style='background-color:#" . $color . ";'>Add Character</h1>";
					echo "<p>";
						echo "<table>";
							echo "<tr>";
								echo "<th>Character Name";
								echo "<th>Character Job";
								echo "<th>Character Level";
								echo "<th>Promotion Level";
							echo "</tr>";
							//char data
							echo "<form id='add_char' action='#' method='post'>";
							echo "<label class='error' for='all' id='error'></label>";  
							echo "<tr align='left'>";
								echo "<td><input id='char_name' type='text' name='char_name'></input>";
								echo "<td><select class='std' id='char_job' name='char_job'>";
								for($i=0,$size=count($job_list_arr);$i<$size;$i++){
									echo "<option class='std' value=" . rawurlencode($job_list_arr[$i]['Name']) . ">" . $job_list_arr[$i]['Name'] . "</option>";
								};
								echo "</select></td>";
								echo "<td><input id='char_lvl' type='text' name='char_lvl'></input></td>";
								echo "<td><select class='std' id='char_pro' name='promotion'>";
									echo "<option class='std' value='0'>None</option>";
									echo "<option class='std' value='1'>Veteran</option>";
									echo "<option class='std' value='2'>Expert</option>";
									echo "<option class='std' value='3'>Master</option>";
									echo "<option class='std' value='4'>High Master</option>";
									echo "<option class='std' value='5'>Grand Master</option>";
								echo "</select></td>";
							echo "</tr>";
						echo "</table>";
							echo "<input id='submit_add_char' class='launch_small2' type='submit' value='Add Character'></input>";
							echo "</form>";
						echo "If your character is a Veteran set their level to <b>100</b>.<br>";
						echo "If your character is a Expert set their level to <b>110</b>.<br>";
						echo "If your character is a Master set their level to <b>120</b>.<br>";
						echo "If your character is a High Master set their level to <b>130</b>.<br>";
						echo "If your character is a Grand Master set their level to <b>140</b>.<br>";
					echo "</p>";
				echo "</div>";
?>