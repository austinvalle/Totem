<?php
    include('sessionStart.php');	

	$query = 'UPDATE USERS SET session_id = NULL WHERE id = ' . $_SESSION['user']['id'] . ' LIMIT 1';  
	$DATABASE->executeQuery($query);  
	unset($_SESSION['user']);  
	header('Location: login.php');  
	exit; 
?> 