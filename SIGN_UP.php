<!doctype html>

<?php
SESSION_START();
?>

<html>
<head>
<title>SIGN-UP- search4jobs</title> 
 <script
src="clock assets/jquery-3.3.1.slim.min.js">
</script>
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
<script>
$(document).ready(fuction(){
	$("button").click(function(){
		$("div").animate({left:'100px'});
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
        <h1><a href="#intro" class="scrollto">JOB SIGN-UP</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="#intro"><img src="img/logo.png" alt="" title=""></a> -->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="#intro">HOME</a></li>
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
                    
       
                        
                        <div class="col-sm-1 middle-border"></div>
                        <div class="col-sm-1"></div>
                        	
                        <div class="col-sm-5">
                        	
                        	<div class="form-box">
                        		<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Sign up now</h3>
	                            		<p>Fill in the form below to get instant access:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-pencil"></i>
	                        		</div>
	                            </div>
								
								<form action="SIGN_UP.php" method="post" enctype="multipart/form-data">
								
	                            <div class="form-bottom">
				                    <form role="form" action="" method="post" class="registration-form">
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-first-name">EMAIL ADDRESS</label>
											 <div class="input-group">
	    <span class="input-group-addon">@</span>

				                        	<input type="email" name="EMAIL" placeholder="EMAIL ADDRESS..." class="form-first-name form-control" id="form-first-name">
				                        </div>
										</div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-last-name">USERNAME</label>
				                        	<input type="text" name="USERNAME" placeholder="USERNAME..." class="form-last-name form-control" id="form-last-name">
				                        </div>
				                        
    <div class="form-group">
    <label class="sr-only" for="exampleInputPassword3">PASSWORD</label>
    <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password" name="PASSWORD" required="required">
  </div>

     <div class="form-group">
    <label class="sr-only" for="exampleInputPassword4">CONFIRM PASSWORD</label>
    <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Confirm Password" name="CPASSWORD" required="required">
  </div>

				                      <div class="form-group">
									  <label class="sr-only" for="exampleInputPassword4">FIRST NAME</label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="FIRST NAME" name="FNAME" required="required"/>
  </div>

  
   <div class="form-group">
   <label class="sr-only" for="exampleInputPassword4">SURNAME </label>
    <input type="text" class="form-control" id="exampleInputName2" placeholder="Surname" name="SNAME" required="required"/>
  </div>
  
  <div class="form-group">
	<label id="label">
	EMPLOYED <input id="set" type="radio" name="employed" value="employed"required="required"/>
	UNEMPLOYED <input id="set" type="radio" name="employed" value="UNEMPLOYED"required="required"/>
	SELFEMPLOYED <input id="set" type="radio" name="employed" value="selfEm"required="required"/>
	STUDENT <input id="set" type="radio" name="employed" value="STUDENT"required="required"/>

	  </label>

				                        <button type="REGISTER"  name="REGISTER" value= "REGISTER NOW" class="btn">Sign me up!</button>
				                    </form>
			                    </div>
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
        				<p>Made by Anli Zaimi at <a href="http://azmind.com" target="_blank"><strong>AZMIND</strong></a> 
        					having a lot of fun. <i class="fa fa-smile-o"></i></p>
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
		
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    

<script>
fuction myfunction(){
	var x=document.getElementById("why");
	if(x.type==="password"){
		x.type="text";
	}else{
		x.type="password";
	}
}
</script>
 <?php
 //getting values from the fields//
 if(isset($_POST['REGISTER'])){
	 $user_EMAIL= mysqli_real_escape_string($con,$_POST['EMAIL']);
	 $user_USERNAME=mysqli_real_escape_string($con,$_POST['USERNAME']);
	 $user_password=mysqli_real_escape_string($con, $_POST['PASSWORD']);
	 $confirm_password= mysqli_real_escape_string($con,$_POST['CPASSWORD']);
	 $user_FNAME=mysqli_real_escape_string($con, $_POST['FNAME']);
	 $user_SNAME=mysqli_real_escape_string($con, $_POST['SNAME']);
	 $user_Estatus= mysqli_real_escape_string($con,$_POST['employed']);
	 
	 
	 
	

	if(!FILTER_var($user_EMAIL,FILTER_VALIDATE_EMAIL)){
				echo"<script>alert('INVALID EMAIL ADDRESS, TRY AGAIN')</script>";
				exit();
	}
	if(strlen($user_password)<8){
				echo"<script>alert('PASSWORD TOO SHORT!!')</script>";
		exit();
	}
			if($user_password!=$confirm_password){
			echo"<script>alert('PASSWORD DO NOT MATCH , TRY AGAIN!!')</script>";
			exit();
		}
	
		 if(strlen($user_password)>20){
				$error.="PASSWORD TOO LONG, TRY MAKING IT STRONG AND PRECISE";	
				echo"<script>alert('PASSWORD TOO LONG, TRY MAKING IT STRONG AND PRECISE!!')</script>";
	}	

	if(!preg_match("#[0-9]+#",$user_password)){
				$error.="PASSWORD MUST INCLUDE AT LEAST ONE NUMBER";
echo"<script>alert('PASSWORD MUST INCLUDE AT LEAST ONE NUMBER!!')</script>";				
	}
		if(strlen($user_USERNAME)<5){
				echo"<script>alert('USERNAME TOO SHORT!!')</script>";
		exit();
	}
		if(!preg_match("#[0-9]+#",$user_USERNAME)){
echo"<script>alert('USERNAME MUST INCLUDE AT LEAST ONE NUMBER!!')</script>";				
	}
		 	else{
		$_SESSION['user_EMAIL']=$user_EMAIL;
	 $insert="insert into jobsearch(user_EMAIL,user_USERNAME,user_password,user_FNAME,user_SNAME,user_Estatus) values('$user_EMAIL','$user_USERNAME','$user_password','$user_FNAME','$user_SNAME','$user_Estatus')";
     $run_insert= mysqli_query($con, $insert);
	  echo"<script>alert('REGISTRATION SUCCESSFUL')</script>";
	 if($run_insert){
		 echo"<script>window.open('signupPage.php','_self')</script>";
		 $_SESSION['user_EMAIL']= $user_EMAIL;
		 $_SESSION['user_USERNAME']=$user_USERNAME;
		 }
		 }
		 }
		 ?>
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="js/bootstrap.min.js"></script>

		 </body>
		 </html>