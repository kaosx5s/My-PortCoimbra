<?php
session_start();
if(!isset($_SESSION['username'])){
	header('Location: index.php');
};
include('includes/sql.php');
include('includes/defines.php');
include('includes/header.tpl');
		echo "<div id='content'>";
			echo "<div id='simple'>";
				echo "<h1>My.Portcoimbra.Com FAQ</h1>";
				echo "<p>";
?>
<font size='-1'>
<b>Q. What is MyPC?</b><br>
A. MyPC is a family management system and signature generator for Granado Espada, specifically Sword of the New World.
<br><br>
<b>Q. How do I use MyPC?</b><br>
A. There are two different methods of utilizing MyPC. The first method is by manually adding your Family Name and Characters.<br>
The second method uses a client modification which will automatically update your MyPC profile with new characters, changes to character levels, 
changes to family level or clan name.
<br><br>
<b>Q. I'm concerned about this 'Client Mod', what data are you collecting?</b><br>
A. MyPC only collects these bits of information: Family Name, Family Level, Family Exp, Clan Name, Character Name, Character Job, Character Level, Character Exp, Basic Stats, Attack Stats, Defense Stats and Resist Stats.
<br><br>
<b>Q. What are your affiliate agreements?</b><br>
A. MyPC will only associate with K2 Network in regards to data. We will <b>never</b> give your information to any other affiliates.
<br><br>
<b>Q. Why is my Email required to sign up?</b><br>
A. Email addresses are unique, we <b>do</b> mail you any confirmation email and please be sure to use a real email address!
<br><br>
<b>Q. Does MyPC ever email me about updates or changes?</b><br>
A. No, MyPC will never email you regarding your MyPC account. Any emails received (after the initial activation email) regarding your password is a scam; do not reply to such messages.
<br><br>
<b>Q. My Server says "locked", why?</b><br>
A. To prevent "server hopping" you may only choose your Server one time. If you wish to have this reset (a valid excuse is required) please post on our support 
forums about it and an admin will gladly reset it.
<br><br>
<b>Q. What is a "Custom URL"?</b><br>
A. The Custom URL feature is ideally for users who are using our Client Modification. If your Family Name contains special characters (UTF-8 or Latin) your 
profile will be unavailable. With the Custom URL feature these users will be able to have a clean URL for showing off their profile.
<br><br>
<b>Q. I don't have any special characters, can I still use the "Custom URL" feature?</b><br>
A. You are free to enter a Custom URL, though it is discouraged for users without special characters.
<br><br>
<b>Q. What are these "Privacy Settings"?</b><br>
A. Privacy Settings are settings regarding what is viewable at your online profile page. You may hide your Family Level, Clan Name, and Character List from 
public viewing.
<br><br>
<b>Q. What are these "Client Mod Settings"?</b><br>
A. These settings are for our Client Modification users. Logging into multiple accounts (on the same server) will overwrite previous Family Level and Clan data, 
it will also add characters from the second account. The setting for "Client Mod Based Updates" is so that these specific users can stop all dynamic updates 
from their modified client. This setting is a toggle setting so that the user may allow or deny dynamic updates when ever they want.
<br><br>
<b>Q. How do I add characters?</b><br>
A. Go to the "Manage Family" tab in the upper navigation and click the "Add Character Manually" button.
<br><br>
<b>Q. How do I add characters to my signature?</b><br>
A. On the "Manage Family" page roll over a character with your cursor and click the plus (+) Symbol to add the respective character to your signature.
<br><br>
<b>Q. How do I remove characters to my signature?</b><br>
A. On the "Manage Family" page roll over a character with your cursor and click the plus (-) Symbol to remove the respective character from your signature.
<br><br>
<b>Q. How do I remove a character?</b><br>
A. On the "Manage Family" page roll over a character and click the "Edit" button, then click the trash can in the upper right hand corner to remove the respective character from your list of characters.
<br><br>
<b>Q. How do I re-add a character?</b><br>
A. If you are not using the Client Modification then you will need to manually re-add the character. If you have the Client Modification then you will need to 
start Sword of the New World and enter a town with the specific character you wish to re-add; our system will automatically re-add your character at this point.
<br><br>
<b>Q. How do I change a characters image?</b><br>
A. On the "Manage Family" page roll over the respective character with your cursor and click "Edit". In the pop up window look for "UPC Image", click the bold text to open a drop down menu and select a custom image.
<br><br>
<b>Q. How many characters can I add?</b><br>
A. MyPC supports and infinite amount of chracters. Though for load purposes we do ask you to not exceed 120 characters.
<br><br>
<b>Q. How many characters can I add to my signature?</b><br>
A. MyPC signature generator supports up to 20 characters.
<br><br>
<b>Q. What are groups?</b><br>
A. Groups are a set of characters (similar to an in game team) you wish to have a special signature for.
<br><br>
<b>Q. How do I add a group?</b><br>
A. While on the "Manage Family" page you will need to click the "Add Group" button to start adding a group. 
Your group name should contain no special characters and is limited to a max of 25 text characters.
<br><br>
<b>Q. How do I delete a group?</b><br>
A. While on the "Manage Family" page you will need to click the "Delete Group" button to start deleting a group. 
<br><br>
<b>Q. How do I add my characters into a group?</b><br>
A. While on the "Manage Family" page roll over the character you wish to add to a group with your cursor and select "Edit". In the pop up window look for "Assigned Group", click the bold text to open a drop down menu and select a group.
<br><br>
<b>Q. How do I remove my characters from a group?</b><br>
A. While on the "Manage Family" page roll over the character you wish to add to a group with your cursor and select "Edit". In the pop up window look for "Assigned Group", click the bold text to open a drop down menu and select "No Group".
<br><br>
<b>Q. Can characters be in multiple groups at the same time?</b><br>
A. Currently you are unable to add characters to more than one group at a time.
<br><br>
</font>
<?php
				echo "</p>";
			echo "</div>";
		echo "</div>";
include('includes/footer.tpl');
?>