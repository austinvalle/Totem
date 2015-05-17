<?php
	include_once('sessionStart.php');

   // Here are the variables that will access all of the information sent to this script
   //------------------------------------------------------------------------------------
   // Email is $_POST['email']
   // Username is $_POST['username']
   // New Password is $_POST['newPassword']
   // Confirm Password is $_POST['confirmPassword']
   //------------------------------------------------------------------------------------
   // -- Example Below --
	$email = $_POST['email'];
       $username = $_POST['username'];
	$newPass = $_POST['newPassword'];
	$confirmPass = $_POST['confirmPassword'];
	
    	$updatequery = "update USERS set";	
	
    	if(strlen($username) < 5 || strlen($username) > 18){
	   	$errors['usernameError'] = 'Your username is invalid.'; 
   	}
	else{
       	$query = 'SELECT * FROM USERS WHERE username = "' . mysql_real_escape_string($username) . '" AND NOT id = "' . $_SESSION['user']['id'] . '" LIMIT 1';  
	   
       	if($DATABASE->executeQuery($query)) {
          		$errors['usernameError'] = 'This username already exists.'; 
		}
	   	else{
	      		$updatequery .= ' username = "' . $username . '",';
	   	}
	}
	
    	if (!eregi('^[^@]{1,64}@[^@]{1,255}$', $email)) {
       	$errors['emailError'] = 'Your email address is invalid.';
	}
	else{
       	$query = 'SELECT * FROM USERS WHERE email_address = "' . mysql_real_escape_string($email) . '" AND NOT id = "' . $_SESSION['user']['id'] . '" LIMIT 1';  
       	if($DATABASE->executeQuery($query)) {
          		$errors['emailError'] = 'This email address already exists.'; 
		}
	   	else{
	      		$updatequery .= ' email_address = "' . $email . '",';
	   	}
	}
	
	if($newPass == ""){
       // Don't do anything	
	}
    	else if((strlen($newPass) < 6 || strlen($newPass) > 18)) {
       	$errors['newPassError'] = 'Your password must be between 6-18 characters.';
	}
	else if($newPass != $confirmPass){
	   	$errors['confirmPassError'] = 'Your entered passwords do not match.';
	}
	else{
	   	$updatequery .= ' password = "' . MD5($newPass) . '",';
	}
	
	$updatequery .= ' session_id = "' . $sessionid . '" where id="' .$_SESSION['user']['id'] . '"';
	$DATABASE->executeQuery($updatequery);
	
	echo json_encode($errors);
?>