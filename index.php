<?php  
	include_once('sessionStart.php');
	include_once('loadPosts.php');  
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
	   		#$images = glob("style/images/indeximages/*.jpg");

       		#$i = rand(0, count($images)-1); // generate random number for image
       		#$selectedBg = $images[$i]; // set background equal to which random image was chosen
		?>
   
   		<!-- Please don't kill me for document-level css, it was the only way :P -->
   		<!--<style type="text/css">
     		body{
         		background: url(<?php echo $selectedBg; ?>) no-repeat;
				background-size: cover;
				background-attachment: fixed;
      		}
   		</style>-->
		
 
	</head>

	<body class="mainPages">
   	
   		<div class="tempHead">
		
			<div class = "userInfo">

				<?php echo 'You are logged in as <span id="username"><i>' . $_SESSION['user']['username'] . '</i></span>'; ?>
				&nbsp&nbsp&nbsp
				<a href="" id="editProfText">Edit Profile</a> <a id = "logoutText" href="logout.php">Logout</a> 

			</div>

			<form name="editAccountForm">

				<div class="editAccount">

                			<div class="modal"></div>

					<div class="rightEditAccountText"> 

						Edit Email: <input id="newEmail" class="input" type="text" tabindex="2" name="email" value="<?php echo $_SESSION['user']['email_address']; ?>" />
                    				<img id="emailCorrect" class="greenCheck" src="style/images/greencheck.png" /></br>
						Confirm Password: <input id="confirmPass" class="input newInfoBox" type="password" tabindex="4" name="confirmNewPassword" value="" />
                    				<img id="confirmPassCorrect" class="greenCheck" src="style/images/greencheck.png" /></br><br>
		
					</div>

					<div class="leftEditAccountText">

						Edit Username: <input id="newUser" class="input newInfoBox" type="text" tabindex="1" name="username" value="<?php echo $_SESSION['user']['username']; ?>" /> 
						<img id="usernameCorrect" class="greenCheck" src="style/images/greencheck.png" /></br>
						New Password: <input id="newPass" class="input newInfoBox" type="password" tabindex="3" name="newPassword" value="" /> 
						<img id="newPassCorrect" class="greenCheck" src="style/images/greencheck.png" /></br></br>
				
					</div>

					<div id="editAccountSubmitPanel">
						<label for="editAccountSubmit">&nbsp;</label>  
						<input type="hidden" name="editAccountSubmit" id="editAccountHidden" value="true" />  
						<img id="closeEdit" src="style/images/cancelImage.png" />
						<input id = "editAccountSubmit" type="button" value="Submit" />
						
					</div>
					
				</div>

        		</form>

		</div> <!--close tempHead-->

		<div class="leftContent">
			<div class="indexPageTitle">
				<a class="indexLogo" href="index.php">TOTEM</a>
			</div>
			<div class="navBar center-Align">
		  
				<div class="center-Align navBarHeaderFooter"><span id="navBarTitle">Posts</span></div>
				<div class="newPostText">New Post +</div>
				<!--<input class="newPostTitle" type="text">
				<input class="addPost" type="button" value="Add Post">-->
	  
				<div class="currentPosts center-Align">
					<?php loadNavBar(); ?>
				</div>
			 
			</div> <!--close navBar-->
		</div>
		
		<div class="center-Align" id="indexWrapper">

      			<div class="statusWrapper center-Align" id="statusSection">

	     			<!--Here you can put whatever you want to show originally. No posts will be shown until you click on a Post.-->
					<br>
					<!--<div class="indexPageWelcome">Welcome <br>
						<?php echo '<span id="username">' . $_SESSION['user']['username'] . '</span>'; ?> !
					</div>	-->
      			</div> <!--close statusWrapper-->

     		</div> <!--close indexWrapper-->
	 
		<footer id="indexFooter">

	   		<p class="Copyright" >

		  		Copyright&copy 2013 – Austin Valle and Dylan Jahns <br />

	   		</p>

		</footer>

		<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	</body>

</html>