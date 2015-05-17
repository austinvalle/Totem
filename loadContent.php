<?php
	
	include('sessionStart.php');
	
	# for creating new posts. replaces post title and body with textboxes
	if (isset($_POST['newPost']) && $_POST['newPost'] == true) {
		echo "<div id=\"postTitle\"><span id=\"postTitleText\"><input type=\"text\" class=\"newPostTitle\" value=\"New post title\" /></span>";
		echo "<br><div class=\"postBody\"><input type=\"text\" class=\"newPostBody\" value=\"New post body\" /><input type=\"button\" class=\"totemButton\" id=\"addPostButton\" value=\"Save\" /></div></div>";
	}
	else {
		if (isset($_POST['id'])) {
			$postid = $_POST['id'];
		}
		else if (isset($_POST['title'])) {
			$q = "select * from POSTS where title = '{$_POST['title']}'";
			$res = $DATABASE->fetchOneResult($q);
			if ($res) {
				$postid = $res['id'];
			}
		}
		$topFiveQuery = "Select COMMENTS.id, COMMENTS.body, date_format(COMMENTS.date_posted, '%h:%i %p on %m-%d-%y') as dposted, USERS.username, (SELECT COUNT(*) FROM LIKES where LIKES.comment_id = COMMENTS.id) as numLikes 
				  from COMMENTS INNER JOIN USERS on USERS.id = COMMENTS.user_id where post_id = {$postid} order by numLikes DESC, COMMENTS.date_posted DESC LIMIT 5";
		$topFiveResults = $DATABASE->executeAssocQuery($topFiveQuery);
		
		if (isset($_POST['showAll'])) {
			$allQuery = "Select COMMENTS.id, COMMENTS.body, date_format(COMMENTS.date_posted, '%h:%i %p on %m-%d-%y') as dposted, USERS.username, (SELECT COUNT(*) FROM LIKES where LIKES.comment_id = COMMENTS.id) as numLikes 
				  from COMMENTS INNER JOIN USERS on USERS.id = COMMENTS.user_id where post_id = {$postid}";
			if ($topFiveResults) {
				$allQuery = $allQuery . " and (";
				for ($i = 0; $i < count($topFiveResults); $i++) {
					if ($i != 0) {
						$allQuery = $allQuery . " and ";
					}
					$allQuery = $allQuery . "COMMENTS.id != {$topFiveResults[$i]['id']}";
				}
				$allQuery = $allQuery . ") order by COMMENTS.date_posted DESC";
			}
			
			$allResults = $DATABASE->executeAssocQuery($allQuery);
		}
		
		$commentResults = array();
		if ($topFiveResults) {
			$commentResults = array_merge((array)$commentResults, (array)$topFiveResults);
		}
		if (isset($allResults)) {
			$commentResults = array_merge((array)$commentResults, (array)$allResults);
		}
		
		$query = "Select * from POSTS where id = '{$postid}'";
		$postResults = $DATABASE->fetchOneResult($query);
		
		if ($postResults) {
			echo "<div id=\"postTitle\"><span id=\"postTitleText\">{$postResults['title']}</span><div class=\"replyContainer\"><img id=\"replyIcon\" src=\"style/images/reply.png\" /><span id=\"replyText\">reply</span></div><div id=\"showAllContainer\"><span id=\"showAllText\">Show all comments</span></div>" .
					"<br><div class=\"postBody\" id=\"postHeader{$postResults['id']}\"><span>{$postResults['body']}</span></div></div>";
		}

		echo "<ul class=\"Statuses\">";
		
		if ($postResults) {			
			echo "<div class=\"commentBlock statusUpdate\"><span class=\"closeButton\" id=\"commentBlockClose\">X</span><input class=\"commentText\" type=\"textarea\" /><p>
			<input class=\"totemButton\" id=\"addCommentButton\" type=\"button\" value=\"Add Comment\" /></p></div>";
		}
		if ($commentResults) {		
			foreach($commentResults as $result) {
				$query = "Select COUNT(*) as numLikes FROM LIKES where comment_id = '{$result['id']}'";
				$likeResults = $DATABASE->fetchOneResult($query);
				$query = "Select user_id FROM LIKES where comment_id = '{$result['id']}' and user_id = '{$_SESSION['user']['id']}'";
				
				$alreadyLiked = $DATABASE->fetchOneResult($query);
				if($alreadyLiked){
				   $display = "\"voteUpDone\"";
				}
				else{
				   $display = "\"voteUp\"";
				}
				$output = "<li><div class=\"statusUpdate\" id=\"comment{$result['id']}\"><div class=\"commentBody\"><span>{$result['body']}</span></div><img class={$display} src=\"style/images/ThumbsUp.png\" />
					  </br><div id=\"likeBox\">{$likeResults['numLikes']}</div><span id=\"commentDetails\">Posted by {$result['username']} at {$result['dposted']}</span></div></li>";
				echo $output;
			}

		}
		
		echo "</ul>";
	
	}
?>

