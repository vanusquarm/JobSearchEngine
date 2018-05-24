<!DOCTYPE html>
<?php
SESSION_START();
$_SESSION['user_EMAIL'];
$_SESSION['user_USERNAME'];
$con= mysqli_connect("localhost","root","","resume3") OR die();
?>
<html>
<head>
<title>search4jobs</title> 
<script
src="clock assets/jquery-3.3.1.min.js"> </script>
<script>
$(document).ready(function(){
	$("input").focus(function(){
		$(this).css("background-color","#cccccc");
	});
	$("input").blur(function(){
		$(this).css("background-color","#ffffff");
	});
});
</script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Job For Search Sign-Up</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
	

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">

  

  <!-- Libraries CSS Files -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">

    </head>
      <body>
	    <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
        <h1><a href="#intro" class="scrollto">RESUME</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="#intro"><img src="img/logo.png" alt="" title=""></a> -->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="index.php">HOME</a></li>
          <li><a href="QUICKSEARCH.php">QUICKSEARCH</a></li>
          <li><a href="SIGN_UP.php">SIGN-UP</a></li>
          <li><a href="login.php">LOGIN</a></li>
            <li><a href="EMPLOYER.php">POST A JOB</a></li>
          <li><a href="ER.php">EMPLOYER LOGIN</a></li>
         
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                	
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            
                            <div class="description">
                            	
                            </div>
                        </div>
                    </div>
    
    <form action="signupPage.php" method="post" enctype="multipart/form-data">                    
                        <div class="col-sm-1 middle-border"></div>
                        <div class="col-sm-1"></div>
                        	
                        <div class="col-sm-5">
                        	
                        	<div class="form-box">
                        		<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>WELCOME <?php echo $_SESSION['user_EMAIL'];?></h3>
	                            		<p><?php echo $_SESSION['user_USERNAME'];?> CREATE YOUR RESUME :</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-pencil"></i>
	                        		</div>
	                            </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-last-name">FIRST NAME </label>
				                        	<input type="text" required="required" name="firstNAME" placeholder="first name..." class="form-last-name form-control" id="form-last-name">
				                        </div>


				                      <div class="form-group">
									  <label class="sr-only" for="exampleInputPassword4">LAST NAME</label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="last name..." name="LASTNAME" required="required" required="required" />
  </div>

  

  
  <div class="form-group" class="sr-only" for="exampleInputPassword4">
	<label id="label">
	MALE <input id="set" type="radio" name="gender" value="male"required="required"/>
	FEMALE <input id="set" type="radio" name="gender" value="female"required="required"/>
	OTHER <input id="set" type="radio" name="gender" value="other"required="required"/>
RATHR NOT SAY <input id="set" type="radio" name="gender" value="rather not say"required="required"/>

	  </label>
	  
	   <div class="form-group">
				                        	<label class="sr-only" for="form-about-yourself">About yourself</label>
				                        	<textarea name="form-about-yourself" placeholder="About yourself..." 
				                        				class="form-about-yourself form-control" id="form-about-yourself"name="myself"></textarea> 
				                        </div>
										
														                      <div class="form-group">
									  <label class="sr-only" for="exampleInputPassword4">CURRENT LOCATION</label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="Current Location..." name="LOCATION" required="required" required="required" />
  </div>

  				                      <div class="form-group">
									  <label class="sr-only" for="exampleInputPassword4">HIGHEST DEGREE</label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="Highest Degree..." name="HDEGREE" required="required" required="required" />
  </div>

				                      <div class="form-group">
									  <label class="sr-only" for="exampleInputPassword4">MOST RECENT TITLE</label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="Most Recent Title..." name="TITLE" required="required" required="required" />
  </div>

				                        <button type= "submit" name="SAVE" value= "NEXT" class="btn">SAVE!</button>
				                    </form>
			                      </div>
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
		
            
        <!-- Footer -->
        <footer>
        	<div class="container">
        		<div class="row">
        			
        			<div class="col-sm-8 col-sm-offset-2">
        				<div class="footer-border"></div>
        				<p>Made by DWAMENA EMMANUEL <strong>DEKA</strong></a> 
        					 <i class="fa fa-smile-o"></i></p>
        			</div>
        			
        		</div>
        	</div>
        </footer>

        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
		

<script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/magnific-popup/magnific-popup.min.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>
		
 <?php
 //getting values from the fields//
 if(isset($_POST['SAVE'])){
	 $user_FNAME= mysqli_real_escape_string($con,$_POST['firstNAME']);
	 $user_LNAME=mysqli_real_escape_string($con,$_POST['LASTNAME']);
	  $user_GENDER= mysqli_real_escape_string($con,$_POST['gender']);
	   $user_MYSELF= mysqli_real_escape_string($con,$_POST['myself']);
	   $user_LOCATION=mysqli_real_escape_string($con,$_POST['LOCATION']);
	  $user_HDEGREE= mysqli_real_escape_string($con,$_POST['HDEGREE']);
	  $user_TITLE= mysqli_real_escape_string($con,$_POST['TITLE']);

	  
	  if($user_FNAME==''){
		  
		  echo"<script>alert('YOU LEFT OUT YOUR FIRST NAME !!')</script>";
	  }
	  		if(strlen($user_FNAME)<5){
				echo"<script>alert('USERNAME TOO SHORT!!')</script>";
		exit();
	}
			if(strlen($user_LNAME)<5){
				echo"<script>alert('USERNAME TOO SHORT!!')</script>";
		exit();
	}
	   else{
	 $insert= "insert into resume_table3 (FIRST_NAME, LAST_NAME, GENDER,MYSELF,LOCATION,HDEGREE,TITLE) values('$user_FNAME','$user_LNAME','$user_GENDER','$user_MYSELF','$user_LOCATION','$user_HDEGREE','$user_TITLE')";
     $run_insert= mysqli_query($con, $insert);
	 if($run_insert){
		  $_SESSION['user_USERNAME']=$user_USERNAME;
		  echo"<script>alert('RESUME SAVED')</script>";
		 echo"<script>window.open('index.php','_self')</script>";
		 
 
 }
 }
 
 }
 
 ?>

</body>
</html>






