
$(function () {
	
	//for keeping track of the currentPost
	var currentPostId;
	
	var top;
	if ($("#postTitle").offset()) {
		top = $("#postTitle").offset().top;
	}	
	
	var cancelPostLoad = false;
	var postTitleFixed = false;
	var showAllComments = false;
	var oldEmail = $("#newEmail").val();
	var oldUsername = $("#newUser").val();
	//THIS IS A BOOL THAT WILL CHECK IF A PASSWORD WAS ENTERED
	var passwordEntered = false;
	
	$(".currentPosts").mCustomScrollbar({
		theme: "light",
		advanced:{
			updateOnContentResize: true
		}
	});
	
	$(window).on("scroll", function() {
		var titleTop = $("#postTitle").offset().top;
		var wrapperTop = $(".statusWrapper").offset().top - $("#postTitle").height() - 32;
		if (titleTop <= $(window).scrollTop()) {
			$("#postTitle").addClass('fixed');
			if (!postTitleFixed) {
				$(window).scrollTop($("#postTitle").height());
				postTitleFixed = true;
				$(".statusWrapper").css("border-radius","0 0 10px 10px");
			}
		}
		if (wrapperTop > $(window).scrollTop() - 5) {
			$("#postTitle").removeClass('fixed');
			if (postTitleFixed) {
				$(window).scrollTop($(window).scrollTop()+titleTop);
				postTitleFixed = false;
				$(".statusWrapper").css("border-radius","10px 10px 10px 10px");
			}
		}
	});
	
	$("#editProfText").click(function () {
        
		$("#newEmail").val(oldEmail);
		$("#newUser").val(oldUsername);
	    $("div.editAccount").slideToggle("fast");
		$("div.userInfo").css("border-radius","10px 10px 0 0");
		$("img.greenCheck").css("display", "none");
		$("img.redX").css("display", "none");

		return false;
	});
	
	$("#closeEdit").click(function () {
	
		$("div.editAccount").slideToggle("fast");
		$("div.userInfo").css("border-radius","10px 10px 10px 10px");

		return false;
	});			

	$("#editAccountSubmit").click(function(){
	   	var formData = {};
		//USE VARIABLE
	   	var newPassword = $("#newPass").val();
	   	if(newPassword != ""){
			passwordEntered = true;
	   	}
      	formData["email"] = $("#newEmail").val();
       	formData["username"] = $("#newUser").val();
       	formData["newPassword"] = $("#newPass").val();
       	formData["confirmPassword"] = $("#confirmPass").val();
	   
	   	$("div.editAccount").addClass("loading"); 
	   	$.ajax({
	      		url: "editAccount.php",
		  	type: "POST",
		  	data: formData,
		  	dataType: "json",
		  	success: function(errors)
		  	{
			 	$("#newPass").val("");
		     	$("#confirmPass").val("");	
		 
				if(errors){
					if(!errors.usernameError){
						$("#username").empty();
						$("#username").html("<i>" + $("#newUser").val() + "</i>");
						if(oldUsername == $("#newUser").val()){
						}
						else{		 
							oldUsername = $("#newUser").val();
							$("#usernameCorrect").css("display", "inline");
						}
					}
					else{
						$("#newUser").empty();
						$("#newUser").val(oldUsername);
						$("#usernameInCorrect").css("display", "inline");
					}
				 
					if(errors.emailError){
						$("#newEmail").empty();
						$("#newEmail").val(oldEmail);
						$("#emailInCorrect").css("display", "inline");
					}
					else{
						if(oldEmail == $("#newEmail").val()) {
						}
						else{
							oldEmail = $("#newEmail").val();
							$("#emailCorrect").css("display", "inline");
						}

					}
					if(!errors.newPassError && !errors.confirmPassError && passwordEntered){
						passwordEntered = false;
						$("#newPassCorrect").css("display", "inline");
						$("#confirmPassCorrect").css("display", "inline");
					}
					else{
						if(errors.newPassError)
							$("#newPassInCorrect").css("display", "inline");
						if(errors.confirmPassError)
							$("#confirmPassInCorrect").css("display", "inline");
					}
					$("div.editAccount").removeClass("loading"); 
				}
				else{
					$("#username").empty();
					$("#username").html("<i>" + $("#newUser").val() + "</i>");
					if(oldUsername != $("#newUser").val()){		 
						oldUsername = $("#newUser").val();
					$("#usernameCorrect").css("display", "inline");
					}
					if(oldEmail != $("#newEmail").val()){		 
						oldEmail = $("#newEmail").val();
						$("#emailCorrect").css("display", "inline");
					}  
					if(passwordEntered){
						$("#newPassCorrect").css("display", "inline");
						$("#confirmPassCorrect").css("display", "inline");
					}
					passwordEntered = false;
					$("div.editAccount").removeClass("loading"); 				
				} 			 
			},
		  error: function (){
		     $("div.editAccount").removeClass("loading"); 
		     alert("Didn't work!");
		  }
	   });
	
	   return false;
	});
	
	$("#statusSection").on("click", "img.voteUp", function(){
		var id = $(this).parent().attr("id");
		id = id.substring(7);
		var parameters = {
			id: id
		};
		var likeBox = $(this).next().next();
		var voteUp = $(this);
		
		
		$.ajax({
		  url: "addLike.php",
		  type: "POST",
		  data: parameters,
		  complete: function(data) {
		     var num = likeBox.html();
			 voteUp.removeClass("voteUp");
			 voteUp.addClass("voteUpDone");
			 num = parseInt(num, 10) + 1;
			 likeBox.html(num);
		  }
		});
	});
	
	$("#statusSection").on("click", "#addCommentButton", function() {
	  if($("input.commentText").val().length > 2){
		var id = $(".postBody").attr("id");
		id = id.substring(10);
		var parameters = {
			text: $("input.commentText").val(),
			id: id
		};
		$.ajax({
		  url: "addComment.php",
		  type: "POST",
		  data: parameters,
		  complete: function() {
		    if (showAllComments) {
				$("#statusSection").load("loadContent.php", { "id": id, "showAll": true}, function() {
					$(".commentBlock").hide();
				});
			}
			else {
				$("#statusSection").load("loadContent.php", { "id": id}, function() {
					$(".commentBlock").hide();
				});
			}
		  }
		});
      }
	});
	
	$(".addPost").click(function(){
	   if($(".newPostTitle").val().length > 3)
	   {
	      var bodyText = prompt($(".newPostTitle").val(), "What do you want to say?") // CHANGE TO JQUERY UI!!! NEED A POPUP DIALOG
	      if(bodyText){
		     var formData = {
		        title: $(".newPostTitle").val(),
			    body: bodyText
		     };
			 
			 $.ajax({
			    url: "addPost.php",
			    type: "POST",
			    data: formData,
                complete: function(){
					$(".newPostTitle").hide();
					$(".addPost").hide();
					$(".currentPosts").load("loadPosts.php", {"callFunction" : "true"}, function() {
						$(this).mCustomScrollbar({ theme:"light",advanced:{ updateOnContentResize: true } });
					});
				}				
			 });
		  }
		  
	   }
	});
	
	$("#statusSection").on("click", "div.replyContainer", function() {
		$(".commentBlock").show("fast");
		$(".commentBlock").css("display", "block");
		$(".commentText").focus();
		return false;
	});
	
	$("#statusSection").on("keyup", "input.commentText", function(e) {
		if (e.keyCode == 13) {
			$("#addCommentButton").trigger("click");
		}
	});
	
	$("#statusSection").on("keyup", ".newPostTitle", function(e) {
		if (e.keyCode == 13) {
			$("#addPostButton").trigger("click");
		}
	});
	
	$("#statusSection").on("keyup", ".newPostBody", function(e) {
		if (e.keyCode == 13) {
			$("#addPostButton").trigger("click");
		}
	});
	
	$(".currentPosts").on("click", "div.postContainer", function() {
		
		if (!cancelPostLoad) {
			$(".postContainer").css("font-weight", "normal");
			$(".postContainer").css("color", "white");	
			$(this).css("font-weight", "bold");
			$(this).css("color", "gray");
		
			//remove old current post
			$(".currentPost").removeClass("currentPost");
			//set new current post
			$(this).addClass("currentPost");
			showAllComments = false;
			var id = $(this).attr("id");
			id = id.substring(4); // remove 'post' from id
			$(".statusWrapper").css("background-color","rgba(12,12,12,.85)");
			$("#statusSection").load("loadContent.php", { "id": id }, function() {
				$(".commentBlock").hide();
			}); 
		}
		else {
			cancelPostLoad = false;
		}
	});
	
	/*
    $(".postContainer").click(function() {
		//remove old current post
		$(".currentPost").removeClass("currentPost");
		//set new current post
		$(this).addClass("currentPost");
		showAllComments = false;
		var id = $(this).attr("id");
		id = id.substring(4); // remove 'post' from id
		$(".statusWrapper").css("background-color","rgba(12,12,12,.85)");
		$("#statusSection").load("loadContent.php", { "id": id }, function() {
			$(".commentBlock").hide();
		});  
	});	
	*/
	
	$("#newUserText").click(function() {
		$(".loginRightContent").slideToggle("slow", function() {
			if ($(".loginRightContent").is(":visible")) {
				$("#newUserText").html("Close");
			}
			else {
				$("#newUserText").html("New User?");
			}
		});		
	});
	
	$(".newPostText").click(function() {
		//remove old current post
		$(".currentPost").removeClass("currentPost");
		$(".postContainer").css("font-weight", "normal");
		$(".postContainer").css("color", "white");	
		$(".statusWrapper").css("background-color","rgba(12,12,12,.85)");
		$("#statusSection").load("loadContent.php", {"newPost": true}, function() {
			$(".commentBlock").hide();
			$(".newPostTitle").focus();
		});
	});
	
	$("#statusSection").on("click", "#showAllContainer", function() {
		var id = $(".currentPost").attr("id");
		id = id.substring(4);
		showAllComments = true;
		$("#statusSection").load("loadContent.php", { "id": id, "showAll": "true" }, function() {
			$(".commentBlock").hide();
			$("html, body").animate( { scrollTop: $(document).height() }, 100);
		});
	});
	
	$(window).resize(function(e) {
		var s = window.innerWidth * .586;
		var size = (s.toString()) + "%";
		if (s > 676) {
			$(".indexLogo").css("font-size", size);
		}
	});
	
	$("#statusSection").on("click", ".newPostTitle", function() {
		if ($(this).val() == 'New post title') {
			$(this).val('');
		}
	});
	
	$("#statusSection").on("focusout", ".newPostTitle", function() {
		if ($(this).val() == '') {
			$(this).val('New post title');
		}
	});
	
	$("#statusSection").on("focusout", ".newPostBody", function() {
		if ($(this).val() == '') {
			$(this).val('New post body');
		}
	});
	
	$("#statusSection").on("click", ".newPostBody", function() {
		if ($(this).val() == 'New post body') {
			$(this).val('');
		}
	});
	
	/*
	$(window).keyup(function(e) {
		if (e.keyCode == 49) {
			alert($(document).height());
		}
	});
	*/
	
	$("#statusSection").on("click", "#addPostButton", function() {
		var postTitle = ($(".newPostTitle").val() != 'New post title') ? $(".newPostTitle").val() : '';
		var postBody = ($(".newPostBody").val() != 'New post body') ? $(".newPostBody").val() : '';		
		if (postTitle != '') {
			var parameters = {
				title: postTitle,
				body: postBody
			};
			$.ajax({
			    url: "addPost.php",
			    type: "POST",
			    data: parameters,
                complete: function(){
					$(".currentPosts").load("loadPosts.php", {"callFunction" : "true"}, function() {
						$(this).mCustomScrollbar({ theme:"light",advanced:{ updateOnContentResize: true } });
						//remove old current post
						$(".currentPost").removeClass("currentPost");
						//set new current post
						$(this).addClass("currentPost");
						$("#statusSection").load("loadContent.php", { "title": postTitle }, function() {
							$(".commentBlock").hide();
						});
					});
				}				
			 });
		}
	});
	
	$(".currentPosts").mouseenter(function() {
		$(".mCSB_dragger .mCSB_dragger_bar").show();
	});
	
	$(".currentPosts").mouseleave(function() {
		$(".mCSB_dragger .mCSB_dragger_bar").hide();
	});
	
	$("#statusSection").on("click", ".closeButton", function() {
		$(this).parent().hide();
	});
	
	$("#refreshText").click(function() {
		currentPostId = $(".currentPost").attr("id");
		currentPostId = "#" + currentPostId;
		$(".currentPosts").load("loadPosts.php", {"callFunction" : "true"}, function() {
			$(this).mCustomScrollbar({ theme:"light",advanced:{ updateOnContentResize: true } });
			$(currentPostId).addClass("currentPost");
			$(currentPostId).css("font-weight", "bold");
			$(currentPostId).css("color", "gray");
		});
	});
	
	$(".currentPosts").on("click", ".postCloseButton", function() {
		var parent = $(this).parent();
		var id = parent.attr("id").substring(4);
		alert("This post will be hidden");
		cancelPostLoad = true;
	});
	
});