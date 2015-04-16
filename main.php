<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
//PHPBB SHIT
define('FORUM_ID', 2);
define('POST_LIMIT', 5);
define('PHPBB_ROOT_PATH', './news/');
define('PRINT_TO_SCREEN', false);   
define('IN_PHPBB', true);
$phpbb_root_path = PHPBB_ROOT_PATH;
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
// Start session management
$user->session_begin(false);
$auth->acl($user->data);
$user->setup();
$query =
"SELECT u.user_id, u.username, t.topic_title, t.topic_poster, t.forum_id, t.topic_id, t.topic_time, t.topic_replies, t.topic_first_post_id, p.poster_id, p.topic_id, p.post_id, p.post_text, p.bbcode_bitfield, p.bbcode_uid
FROM ".USERS_TABLE." u, ".TOPICS_TABLE." t, ".POSTS_TABLE." p
WHERE u.user_id = t.topic_poster
AND u.user_id = p.poster_id
AND t.topic_id = p.topic_id
AND p.post_id = t.topic_first_post_id
AND t.forum_id = ".FORUM_ID."
ORDER BY t.topic_time DESC";
$result = $db->sql_query_limit($query, POST_LIMIT);
$posts = array();
$news = array();
$bbcode_bitfield = '';
$message = '';
$poster_id = 0;

while($r = $db->sql_fetchrow($result)){
   $posts[] = array(
         'topic_id' => $r['topic_id'],
         'topic_time' => $r['topic_time'],
         'username' => $r['username'],
         'topic_title' => $r['topic_title'],
         'post_text' => $r['post_text'],
         'bbcode_uid' => $r['bbcode_uid'],
         'bbcode_bitfield' => $r['bbcode_bitfield'],
         'topic_replies' => $r['topic_replies'],
         );
   $bbcode_bitfield = $bbcode_bitfield | base64_decode($r['bbcode_bitfield']);
};

if($bbcode_bitfield !== ''){
   $bbcode = new bbcode(base64_encode($bbcode_bitfield));
};

// Output the posts
foreach($posts as $m){
   $poster_id = $m['user_id'];
   $message = $m['post_text'];
   if($m['bbcode_bitfield']){
      $bbcode->bbcode_second_pass($message, $m['bbcode_uid'], $m['bbcode_bitfield']);
   };
   $message = str_replace("\n", '<br />', $message);
   $message = smiley_text($message);
   $comment = ($m['topic_replies']==1) ? 'comment' : 'comments';
   if( PRINT_TO_SCREEN ){
      echo "\n\n<h3><span class=\"postinfo\">".$user->format_date($m['topic_time'])." // <a href=\"".PHPBB_ROOT_PATH."viewtopic.php?f=".FORUM_ID."&amp;t={$m['topic_id']}\">{$m['topic_replies']} {$comment}</a> // {$m['username']}</span>{$m['topic_title']}</h3>\n";
      echo "<p>{$message}</p>";
   }else{
      $news[] = array(
            'topic_id' => $m['topic_id'], // eg: 119
            
            'topic_time' => $user->format_date($m['topic_time']), // eg: 06 June, 07 (uses board default)
            'topic_replies' => $m['topic_replies'], // eg: 26
            
            'username' => $m['username'], // eg: chAos
            'topic_title' => $m['topic_title'], // eg: "News Post"
            
            'post_text' => $message, // just the text         
            );
   };
   unset($message,$poster_id);
};
include('includes/sql.php');
//ID Cookie.
$account_info=@$account->get_account_id($_SESSION['id']);
include('includes/defines.php');
include('includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Search</h1>";
				echo "<p>";
					echo "Family Search:";
?>
						<form name='form' method='post' action='search.php'>
                    	<tr><td>Family Name:</td><td><input name='family_name' type='text' id='family_name' /></td></tr>
                        <tr><td colspan=2 align='right'><input type='submit' value='Submit' /></td></tr>
						</form>
<?php
					echo "<br>";
					echo "Server Search:";
?>
						<form name='form' method='post' action='search.php'>
                    	<tr><td>
							<select name='server'>
	  							<option value='Bristia'>SotNW - Bristia</option>
	  							<option value='Orpesia'>SotNW - Orpesia</option>
	  							<option value='Illier'>SotNW - Illier</option>
	  							<option value='Bach'>sGE - Bach</option>
	  							<option value='Rembrandt'>sGE - Rembrandt</option>
	  							<option value='Draco'>Thai - Draco</option>
	  							<option value='Corona'>Thai - Corona</option>
	  							<option value='Cortes'>rusGE - Cortes</option>
	  							<option value='Hao Vong'>vGE - Hao Vong</option>
	  							<option value='Huyen Thoai'>vGE - Huyen Thoai</option>
							</select>
						</td></tr>
                        <tr><td colspan=2 align='right'><input type='submit' value='Submit' /></td></tr>
						</form>
<?php
				echo "</p>";
			echo "</div>";
			echo "<br>";
			if($account_info['family_name']==''){
			echo "<div id='simple'>";
				echo "<h1 style='background-color:#" . $color . ";'>Information</h1>";
				echo "<p>";
					echo "It appears that you are a new user!";
					echo "<br>";
					echo "<a class='launch_small' href='account.php'>Create Family</a>";
				echo "</p>";
			echo "</div>";
			echo "<br>";
			};
	//news
			echo "<div id='simple'>";
			echo "<h1 style='background-color:#" . $color . ";'>News</h1>";
			for($i=0;$i<count($news);$i++){
				echo "<table class='mypc_news' cellspacing='0'>";
					echo "<tr><th>" . $news[$i]['topic_title'] . "</th>";
					echo "<tr><td class='description'>" . $news[$i]['post_text'] . "</tr><tr><td class='right'><b>" . $news[$i]['username'] . "</b>@" . $news[$i]['topic_time'] . "</td></tr>";
				echo "</table>";
			};
			echo "</div>";
include('includes/footer.tpl');
?>