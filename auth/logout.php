<?php
session_start();
setcookie("username",0,0,"/",".my.portcoimbra.com",0,1);
setcookie("remkey",0,0,"/",".my.portcoimbra.com",0,1);
setcookie("ip",0,0,"/",".my.portcoimbra.com",0,1);
include('../includes/defines.php');
include('../includes/sql.php');
include('../includes/header.tpl');
?>
<head>
	<meta http-equiv='REFRESH' content='5;url=../index.php'>
</head>
		<tr><td>
			<?php echo LOGOUT_MESSAGE; ?>
			<br>
			<a href='../index.php'>Click here if you are not redirected.</a>
		</td></tr>
	</tbody>
</table>
<?php 
include('../includes/footer.tpl');
$_SESSION = array();
session_regenerate_id();
session_destroy();
?>