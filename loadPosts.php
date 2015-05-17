<?php
	include('database.php');
	$query = "Select * from POSTS LIMIT 30";
	$results = $DATABASE->executeAssocQuery($query);
	if ($results) {
		foreach($results as $result) {
			$post = new Post;
			$post->load($result);
			$ALL_POSTS[] = $post;
		}
	}
	

	function loadNavBar() {
	 	$posts = $GLOBALS['ALL_POSTS'];
	 	if ($posts) {
		 	foreach($posts as $post) {
			 	echo "<div class=\"postContainer\" id=\"post{$post->properties['id']}\"><span class=\"postContainerText\">{$post->properties['title']}</span></div>";
		 	}
	 	}
 	}

 	if (isset($_POST['callFunction'])) {
		loadNavBar();
 	}

?>