<?php  
	include_once('database.php');  
	session_start();
  
	// Reset errors and success messages  
	$errors = array();  
	$success = array();  
  
	// Login attempt  
	if(isset($_POST['loginSubmit']) && $_POST['loginSubmit'] == 'true'){  
    
		$loginEmail = trim($_POST['email']);  
    		$loginPassword  = trim($_POST['password']);  
    
		# modularize regexes/validation	
    		if (!eregi('^[^@]{1,64}@[^@]{1,255}$', $loginEmail)) {
       		$errors['loginEmail'] = 'Your email address is invalid.';
		}
      
    		if(strlen($loginPassword) < 6 || strlen($loginPassword) > 18) {
        		$errors['loginPassword'] = 'Your password must be between 6-18 characters.';
		}
      
    		if(!$errors){  
			$query  = 'select * from USERS where email_address = "' . mysql_real_escape_string($loginEmail) . '" and password = MD5("' . $loginPassword . '") LIMIT 1';  
			$user = $DATABASE->fetchOneResult($query);
			if($user) {
				$query = 'update USERS set session_id = "' . session_id() . '", last_login = "' . date('Y-m-d h:i:s') . '"  where id = ' . $user['id'] . ' LIMIT 1';
				$DATABASE->executeQuery($query);
				header('Location: index.php');
			}
			else {
				$errors['login'] = 'No user was found with the details provided.';
			}
		}
	}
  
	// Register attempt  
	if(isset($_POST['registerSubmit']) && $_POST['registerSubmit'] == 'true'){  
    		$registerUsername = trim($_POST['username']);
		$registerEmail = trim($_POST['email']);  
    		$registerPassword = trim($_POST['password']);  
    		$registerConfirmPassword    = trim($_POST['confirmPassword']); 	
	
		if(strlen($registerUsername) < 5 || strlen($registerUsername) > 18)
	    		$errors['registerUsername'] = 'Your username is invalid.'; 
      
    		if (!eregi('^[^@]{1,64}@[^@]{1,255}$', $registerEmail))   
       		$errors['registerEmail'] = 'Your email address is invalid.';  
      
   		if(strlen($registerPassword) < 6 || strlen($registerPassword) > 18)     
 		       $errors['registerPassword'] = 'Your password must be between 6-18 characters.';  
      
 		if($registerPassword != $registerConfirmPassword)  
  		      	$errors['registerConfirmPassword'] = 'Your passwords did not match.'; 

    		// Check to see if we have a user registered with this username already  
    		$query = 'SELECT * FROM USERS WHERE username = "' . mysql_real_escape_string($registerUsername) . '" LIMIT 1';  
    		$result = $DATABASE->fetchOneResult($query);  
    		if($result)   
        		$errors['registerUsername'] = 'This username already exists.';  		
      
    		// Check to see if we have a user registered with this email address already  
    		$query = 'SELECT * FROM USERS WHERE email_address = "' . mysql_real_escape_string($registerEmail) . '" LIMIT 1';  
    		$result = $DATABASE->fetchOneResult($query);  
    		if($result)   
        		$errors['registerEmail'] = 'This email address already exists.';  
      
    		if(!$errors){  
        		$query = 'INSERT INTO USERS SET username = "' . mysql_real_escape_string($registerUsername) . '",
		                                                                email_address = "' . mysql_real_escape_string($registerEmail) . '",  
                                                                        password = MD5("' . mysql_real_escape_string($registerPassword) . '"),  
                                                                        date_registered = "' . date('Y-m-d h:i:s') . '"';  
				if($DATABASE->resultlessQuery($query)) {  
					$success['register'] = 'Thank you for registering. You can now log in.';  
				}
				else {  
            		$errors['register'] = 'There was a problem registering you. Please check your details below and try again.';  
				}  
    		}  
			else{
			   $errors['register'] = 'There was a problem registering you. Please check your details below and try again.'; 
			}

    	$DATABASE->close();
	}  
?>

<!DOCTYPE html>
<html lang=“en”>

	<head>
   		<title>Totem - The Future of Social Networking</title>
   		<meta charset=“utf-8”/>
   		<meta name="title"       content="Our Template"/>
   		<meta name="author"      content="Dylan and Austin"/>
   		<meta name="description" content="Template with CSS"/>
   		<meta name="keywords"    content="HTML, CSS"/>
   		<meta name="language"    content="English"/>

   		<link href="style/style.css" rel="Stylesheet" />
		<link href="style/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" />
   		<link rel="icon" href="style/images/totem.gif" type="image/x-icon">
   		<link rel="SHORTCUT ICON" href="style/images/totem.gif" type="image/x-icon">
   		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   		<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
   		<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
   		<script src="js/script.js" type="text/javascript"></script>
   
    
		<!-- PHP SCRIPT FOR RANDOM BACKGROUND -->	
    		<?php
	   		$images = glob("style/images/loginimages/*.jpg");

       		$i = rand(0, count($images)-1); // generate random number for image
       		$selectedBg = $images[$i]; // set background equal to which random image was chosen
		?>
   
   		<!-- Please don't kill me for document-level css, it was the only way :P -->
   		<style type="text/css">
      			body{
         		background: url(<?php echo $selectedBg; ?>) no-repeat;
		 	background-size: 100%;
      			}
   		</style>
	</head>

	<body class="loginPage">
 
   		<div class="loginHead">
     
			<h1 id="pageTitle">TOTEM</h1>

		</div> <!--close loginHead-->

		<div id="loginWrapper">
       	   
       		<div class="loginLeftContent"><!-- TOP AREA OF LOGIN MODULE -->
					<?php if(isset($success['register'])) print '<div class="valid">' . $success['register'] . '</div>'; ?> 
				    <?php if(isset($errors['register'])) print '<div class="invalid">' . $errors['register'] . '</div>'; ?>  

		      		<form class="box400" name="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  
		 			<?php if(isset($success['login'])) echo '<div class="valid">' . $success['login'] . '</div>'; ?>
         				<?php if(isset($errors['login'])) echo '<div class="invalid">' . $errors['login'] . '</div>'; ?>		
		 
         				<label for="email">Email Address</label> 
         				<input class="input" type="text" name="email" value="<?php if(isset($loginEmail)) echo htmlspecialchars($loginEmail); ?>" /> </br> 
         				<?php if(isset($errors['loginEmail'])) print '<div class="invalid" id = "loginError">' . $errors['loginEmail'] . '</div>'; ?> </br>	
	
         				<label for="password">Password</label> 
         				<input class="input" id="passWord" type="password" name="password" value="" /></br>
         				<?php if(isset($errors['loginPassword'])) print '<div class="invalid" id = "loginError">' . $errors['loginPassword'] . '</div>'; ?> </br>
		 
         				<label for="loginSubmit">&nbsp;</label>  
         				<input type="hidden" name="loginSubmit" id="loginSubmit" value="true" />  
         				<input id = "loginButton" type="submit" value="Login" />
  
		 			<span id="newUserText">New User?</span>

	  				<div class = "loginErrorBlock">
         				</div>

      				</form> 
            
       		</div>

       		<div class="loginRightContent center-Align"><!-- DROP DOWN AREA OF LOGIN MODULE (Registration) -->

            			New here? Fill out the following information to get started:

				<form class="registerForm" name="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
   
			    		<div class = "registerErrorBlock">

						<?php if(isset($errors['registerUsername'])) print '<div class="invalid">' . $errors['registerUsername'] . '</div>'; ?> 
						<?php if(isset($errors['registerEmail'])) print '<div class="invalid">' . $errors['registerEmail'] . '</div>'; ?>     
						<?php if(isset($errors['registerPassword'])) print '<div class="invalid">' . $errors['registerPassword'] . '</div>'; ?> 
						<?php if(isset($errors['registerConfirmPassword'])) print '<div class="invalid">' . $errors['registerConfirmPassword'] . '</div>'; ?> 
 
			    		</div>	
	
					<label for="username">Username</label>  
					<input class= "newUser" type="text" name="username" value="<?php if(isset($registerUsername)) echo htmlspecialchars($registerUsername); ?>" />  
		        		<br>
				
					<label for="email">Email Address</label>  
					<input class= "newUser" type="text" name="email" value="<?php if(isset($registerEmail)) echo htmlspecialchars($registerEmail); ?>" />  
                			<br>
				  
					<label for="password">Password</label>  
					<input class= "newUser" type="password" name="password" value="" />  
		        		<br>
				  
					<label for="confirmPassword">Confirm Password</label>  
					<input class= "newUser" type="password" name="confirmPassword" value="" />  
					<br>
				  
					<label for="registerSubmit">&nbsp;</label>  
					<input type="hidden" name="registerSubmit" id="registerSubmit" value="true" />  
					<input id ="newUserSubmit" type="submit" value="Register" /> 
 				
				</form>

       		</div>

     		</div> <!--close Wrapper-->
	 
	 	<footer id="loginFooter">

        		<p class="Copyright" >

           			Copyright&copy 2013 - Austin Valle and Dylan Jahns <br>

        		</p>

     		</footer>

		<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	</body>

</html>
