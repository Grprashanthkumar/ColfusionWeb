<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include_once('Smarty.class.php');
$main_smarty = new Smarty;

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'link.php');
include(mnminclude.'tags.php');
include(mnminclude.'smartyvariables.php');
include(mnminclude.'csrf.php');

check_referrer();
global $db;
// restrict access to god
$canIhaveAccess = 0;
$canIhaveAccess = $canIhaveAccess + checklevel('god');
$main_smarty->assign('isAdmin', $canIhaveAccess);


if(isset($_GET['id'])){
	$theid = sanitize($_GET['id'], 3);
}
if(isset($_POST['id'])){
	$theid = sanitize($_POST['id'], 3);
}
if(!is_numeric($theid)){$theid = 0;}
// misc smarty
if (checklevel('god'))
    $Story_Content_Tags_To_Allow = Story_Content_Tags_To_Allow_God;
elseif (checklevel('admin'))
    $Story_Content_Tags_To_Allow = Story_Content_Tags_To_Allow_Admin;
else
    $Story_Content_Tags_To_Allow = Story_Content_Tags_To_Allow_Normal;
$main_smarty->assign('Story_Content_Tags_To_Allow', htmlspecialchars($Story_Content_Tags_To_Allow));

// DB 11/11/08
$link = $db->get_row("SELECT link_id, link_author, UNIX_TIMESTAMP(link_date) AS date FROM " . table_links . " WHERE link_id=".$theid.";");
/////

if ($link) {
	if ($link->link_author==$current_user->user_id || $current_user->user_level == "admin" || $current_user->user_level == "god")
	{
		// DB 11/11/08
		if ($current_user->user_level != "god" && $current_user->user_level != "admin" && limit_time_to_edit!=0 && (time()-$link->date)/60 > edit_time_limit)
		{
			echo "<br /><br />" . sprintf($main_smarty->get_config_vars('PLIGG_Visual_EditLink_Timeout'),edit_time_limit) . "<br/ ><br /><a href=".my_base_url.my_pligg_base.">".$main_smarty->get_config_vars('PLIGG_Visual_Name')." home</a>";
			exit;
		}
		/////

		$CSRF = new csrf();


		
		if(isset($_POST["id"])) {
//print_r($_POST);
//exit;
			echo $_POST['title'];
		    $CSRF->check_expired('edit_link');
		    if ($CSRF->check_valid(sanitize($_POST['token'], 3), 'edit_link')){
			$linkres=new Link;
				$linkres->id=$link_id = sanitize($_GET['id'], 3);
				

				
				
				
				
			if(!is_numeric($link_id)) die();
			$linkres->read();
				
			// if notify link submitter is selected
			if(isset($_POST["notify"]))
				{
					if(sanitize($_POST["notify"], 3) == "yes")
					{
						$link_author = $db->get_col("SELECT link_author FROM " . table_links . " WHERE link_id=".$theid.";");
						$user = $db->get_row("SELECT * FROM " . table_users . " WHERE user_id=".$link_author[0].";");

						$to = $user->user_email;
						$subject = $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_Subject');
						$body = $user->user_login . ", \r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_AdminMadeChange') . "\r\n";
						$body = $body . strtolower(strtok($_SERVER['SERVER_PROTOCOL'], '/')).'://'.$_SERVER['HTTP_HOST'] . getmyurl('story', sanitize($_POST['id'], 3)) . "\r\n\r\n";
						if ($linkres->category != sanitize($_POST["category"], 3)){$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_Submit2_Category') . " change\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_PreviousText') . ": " . GetCatName($linkres->category) . "\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_NewText') . ": " . GetCatName(sanitize($_POST["category"], 3)) . "\r\n\r\n";}
						if ($linkres->title != sanitize($_POST["title"], 4, $Story_Content_Tags_To_Allow)){$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_Submit2_Title') . " change\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_PreviousText') . ": " . $linkres->title . "\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_NewText') . ": " . sanitize($_POST["title"], 3) . "\r\n\r\n";}
						if ($linkres->content != close_tags(sanitize($_POST["bodytext"], 4, $Story_Content_Tags_To_Allow))){$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_Submit2_Description') . " change\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_PreviousText') . ": " . $linkres->content . "\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_NewText') . ": " . close_tags(sanitize($_POST["bodytext"], 3)) . "\r\n\r\n";}
						if ($linkres->tags != tags_normalize_string(sanitize($_POST['tags'], 3))){$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_Submit2_Tags') . " change\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_PreviousText') . ": " . $linkres->tags . "\r\n\r\n" . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_NewText') . ": " . tags_normalize_string(sanitize($_POST['tags'], 3)) . "\r\n\r\n";}
						$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Email_ReasonText') . ": ";
						if (sanitize($_POST["reason"], 3) == "other")
							{$body = $body . sanitize($_POST["otherreason"], 3);}
						else
						{
							$body = $body . $main_smarty->get_config_vars('PLIGG_Visual_EditStory_Reason_' . sanitize($_POST["reason"], 3));
						}
						$headers = 'From: ' . $main_smarty->get_config_vars("PLIGG_PassEmail_From") . "\r\n";
						$headers .= "Content-type: text/plain; charset=utf-8\r\n";
						if (!mail($to, $subject, $body, $headers))
						{
							echo '<br /><div class="error">'.$main_smarty->get_config_vars('PLIGG_PassEmail_SendFail').'</div>';
							die;
						}
					}
				}
			
	/*		if($canIhaveAccess == 1)
			{
				$url = htmlspecialchars(sanitize($_POST['url'], 3));
				$url= str_replace('&amp;', '&', $url);  
			
				$linkres->url=$url;
			}
			*/

			$vars = '';
			check_actions('edit_link_hook', $vars);
				echo $_POST['category'];
			if (is_array($_POST['category']))
			{
			    $linkres->category=sanitize($_POST['category'][0], 3);
			    $linkres->additional_cats=array_slice($_POST['category'],1);
			}
			else
			    $linkres->category=sanitize($_POST['category'], 3);
			
		
			if($linkres->title != stripslashes(sanitize($_POST['title'], 3))){
				$linkres->title = stripslashes(sanitize($_POST['title'], 3));
				$linkres->title_url = makeUrlFriendly($linkres->title, $linkres->id);
			}
			
			$linkres->content = close_tags(sanitize($_POST['bodytext'], 4, $Story_Content_Tags_To_Allow));
			$linkres->tags = tags_normalize_string(stripslashes(sanitize($_POST['tags'], 3)));
			if(sanitize($_POST['summarytext'], 3) == ""){
				$linkres->link_summary = utf8_substr(sanitize($_POST['bodytext'], 4, $Story_Content_Tags_To_Allow), 0, StorySummary_ContentTruncate - 1);
				$linkres->link_summary = close_tags(str_replace("\n", "<br />", $linkres->link_summary));	
			} else {
				$linkres->link_summary = sanitize($_POST['summarytext'], 4, $Story_Content_Tags_To_Allow);
				$linkres->link_summary = close_tags(str_replace("\n", "<br />", $linkres->link_summary));
				if(utf8_strlen($linkres->link_summary) > StorySummary_ContentTruncate){
					loghack('SubmitAStory-SummaryGreaterThanLimit', 'username: ' . sanitize($_POST["username"], 3).'|email: '.sanitize($_POST["email"], 3), true);
					$linkres->link_summary = utf8_substr($linkres->link_summary, 0, StorySummary_ContentTruncate - 1);
					$linkres->link_summary = close_tags(str_replace("\n", "<br />", $linkres->link_summary));
				}
			}
			

     $linkres->content = str_replace("\n", "<br />", $linkres->content);
	
	   //to store edited attributes
				$linkres->edit_store();
				
				$story_url = $linkres->get_url();
				
				echo $story_url;
	
			//	
		
				
		
			if (link_errors($linkres)) {
		echo "this is wrong";
					
					//header('Location: ' . $linkres->getmyurl());
				return;
			}
				header("Location: ".$story_url);
		//	tags_insert_string($linkres->id, $dblang, $linkres->tags);
		
		    } else {
		    	$CSRF->show_invalid_error(1);
		    }
		    exit;
		}
		else
		{
			$linkres=new Link;
		
			$edit = false;

			$link_id = sanitize($_GET['id'], 3);
		
			if (!is_numeric($link_id)) die();
			$linkres->id=$link_id;
			$linkres->read();
			$link_title = $linkres->title;
		    $link_url = $linkres->url;
			
			$link_content = str_replace("<br />", "\n", $linkres->content);
			$link_category=$linkres->category;
			$link_summary = $linkres->link_summary;
			$link_summary = str_replace("<br />", "\n", $link_summary);
	
			$main_smarty->assign('enable_tags', Enable_Tags);
			$main_smarty->assign('submit_url', $linkres->url);
			$main_smarty->assign('submit_url_title', $linkres->url_title);
			$main_smarty->assign('submit_id', $linkres->id);
		
			//$main_smarty->assign('submit_type', $linkres->type());
	
			$main_smarty->assign('submit_title', htmlspecialchars($link_title));
			$main_smarty->assign('submit_content', $link_content);
			$main_smarty->assign('submit_category', $link_category);
			$main_smarty->assign('submit_additional_cats', $linkres->additional_cats);

			$main_smarty->assign('SubmitSummary_Allow_Edit', SubmitSummary_Allow_Edit);
			$main_smarty->assign('StorySummary_ContentTruncate', StorySummary_ContentTruncate);
			$main_smarty->assign('submit_summary', $link_summary);			

			include_once(mnminclude.'dbtree.php');
			$array = tree_to_array(0, table_categories, FALSE);
			$main_smarty->assign('lastspacer', 0);
			$main_smarty->assign('cat_array', $array);

			$canIhaveAccess = 0;
			$canIhaveAccess = $canIhaveAccess + checklevel('god');
			$canIhaveAccess = $canIhaveAccess + checklevel('admin');
			$main_smarty->assign('canIhaveAccess', $canIhaveAccess);

			if(Enable_Tags){
				$main_smarty->assign('tags', $linkres->tags);
				if (!empty($linkres->tags)) {
					$word_array = explode(",",$linkres->tags);
					foreach($word_array as $word)
					{
						$tag_array[] = trim($word);
					}
					$tags_words = implode(", ", $tag_array);					
					$tags_url = urlencode($linkres->tags);
					$main_smarty->assign('tags_words', $tags_words);
					$main_smarty->assign('tags_url', $tags_url);
				}
			}

			$CSRF->create('edit_link', true, true);
		
		
			// pagename
			define('pagename', 'editlink'); 
		        $main_smarty->assign('pagename', pagename);
			
			// sidebar
			$main_smarty = do_sidebar($main_smarty);

			// show the template
			$main_smarty->assign('storylen', utf8_strlen(str_replace("<br />", "\n", $link_content)));
			$main_smarty->assign('tpl_extra_fields', $the_template . '/submit_extra_fields');
			$main_smarty->assign('tpl_center', $the_template . '/editlink_edit_center');
			$main_smarty->display($the_template . '/pligg.tpl');
		}
	}
	else
	{
		echo "<br /><br />" . $main_smarty->get_config_vars('PLIGG_Visual_EditLink_NotYours') . "<br/ ><br /><a href=".my_base_url.my_pligg_base.">".$main_smarty->get_config_vars('PLIGG_Visual_Name')." home</a>";
	}
}
else
{
	echo "<br /><br />" . $main_smarty->get_config_vars('PLIGG_Visual_EditLink_NotYours') . "<br/ ><br /><a href=".my_base_url.my_pligg_base.">".$main_smarty->get_config_vars('PLIGG_Visual_Name')." home</a>";
}


//copied directly from submit.php
function link_errors($linkres)
{
	global $main_smarty, $the_template;
	$error = false;


	if(Submit_Require_A_URL && ($linkres->url == "http://" || $linkres->url == "")){
		$main_smarty->assign('submit_error', 'invalidurl');
		$error = true;
	}
	// if story title or description is too short
	$story = preg_replace('/[\s]+/',' ',strip_tags($linkres->content));
	if(utf8_strlen($linkres->title) < minTitleLength  || utf8_strlen($story) < minStoryLength ) {
		$main_smarty->assign('submit_error', 'incomplete');
		$error = true;
	}
	if(utf8_strlen($linkres->title) > maxTitleLength) {
		$main_smarty->assign('submit_error', 'long_title');
		$error = true;
	}
  	if (utf8_strlen($linkres->content) > maxStoryLength ) { 
		$main_smarty->assign('submit_error', 'long_content');
		$error = true;
	}
	if(utf8_strlen($linkres->tags) > maxTagsLength) {
		$main_smarty->assign('submit_error', 'long_tags');
		$error = true;
	}
  	if (utf8_strlen($linkres->summary) > maxSummaryLength ) { 
		$main_smarty->assign('submit_error', 'long_summary');
		$error = true;
	}
	// if URL is submitted in story title
	if(preg_match('/.*http:\//', $linkres->title)) {
		$main_smarty->assign('submit_error', 'urlintitle');
		$error = true;
	}
/*	// if no category is selected
	if(!$linkres->category > 0) {
		$main_smarty->assign('submit_error', 'nocategory');
		$error = true;
	}*/

	if ($error)
	{echo "errorture";
		$main_smarty->assign('tpl_center', $the_template . '/submit_errors');
	//	$main_smarty->assign('tpl_center', $the_template . '/submit_step_1');
		$main_smarty->assign('link_id', $_GET['id']);
		
		// pagename
		define('pagename', 'editlink'); 
		$main_smarty->assign('pagename', pagename);
		
		// show the template
		$main_smarty->display($the_template . '/pligg.tpl');
	}

	return $error;
}

?>
