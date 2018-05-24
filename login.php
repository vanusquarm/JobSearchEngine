<!DOCTYPE html>
<?php
SESSION_START();

$con= mysqli_connect("localhost","root","","qtech") OR die();
?>
<html>
<head>
 <title>LOGIN- search4jobs</title> 
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
 <title>Homepage - search4jobs</title> 
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
   <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="Content-Language" content="uk" />
   <meta name="author" content="Q-TECH GROUP" /> 
   <meta name="KeyWords" content="search4jobs, Q-TECH Webseite" /> 
   <meta name="Description" content="Q-TECH MAKING JOBS EASY TO FIND JOBS IN GHANA" /> 
    <!-- Bootstrap -->
	   <link rel = "stylesheet" type="text/css" href="css/bootstrap.min.css">
	 <link href="css/print.css" rel="stylesheet" type="text/css" media="print" /> 
    <link rel = "stylesheet" type="text/css" href="css/login.css">
	
	
	 </head>
	 
	 <body>
   <div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row">
					<div class="col-xs-12">
						<div id="logo">
							<a href="./">QTEC<span>H</span></a> 
						</div>
					</div>
				</div>
				<nav class="navbar navbar-inverse">
				  <div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="index.php">MENU</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					  <ul class="nav navbar-nav">
						<li><a href="index.php">HOME <span class="sr-only">(current)</span></a></li>
						<li><a href="SIGN_UP.php">SIGN-UP</a></li>
						  <li class="active"><a href="login.php">LOGIN</a></li>
   <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
  <li class="dropdown" class="navbar navbar-inverse">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">POST A JOB<span class="caret"></span></a>
          <ul class="dropdown-menu" >
            <li><a href="ER.php">EMPLOYER LOGIN</a></li>
            <li><a href="signUP.php">EMPLOYER SIGN-UP</a></li>
					   <li role="separator" class="divider"></li>
            <li><a href="EMPLOYER.php">POST A JOB</a></li>
			       </ul>
        </li>
      </ul>
					  </ul>
					  <ul class="nav navbar-nav navbar-right">
						<li><a href="SIGN_UP.php">SIGN-UP</a></li>
						<li><a href="login.php">LOGIN</a></li>
					  </ul>
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
  
         
 
 <div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
		<div id="main">
   <h1>LOGIN</h1>
     </div>
  
   <form action="login.php" method="post">
	
	  <form class="form-inline">
  <div class="form-group">
    <label id="label" for="exampleInputEmail2">EMAIL ADDRESS</label>
	 <div class="input-group">
	    <span class="input-group-addon">@</span>
    <input type="text" class="form-control" id="exampleInputEmail2" placeholder="EMAIL ADDRESS" required="required" onfocus="this.value=''" name="EMAIL">
  </div>
  </div>
  
   <div class="form-group">
    <label id="label" for="exampleInputName2">USERNAME</label>
    <input type="email" class="form-control" id="exampleInputName2" placeholder="username" name="USERNAME" required="required"/>
  </div>

    <div class="form-group">
    <label class="sr-only" for="exampleInputPassword3">PASSWORD</label>
    <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password" name="PASSWORD" required="required">
  </div>

<tr align="center">
<td colspan="8">
<input id="button" type= "submit" name="LOGIN" value= "LOGIN">
</td>

	  <p> By sigining in to your account, you agree to Q-TECH <a href ="#" style="color:dogerblue">Terms Of Service</a>.</p>
<p> And consent to our <a href ="#" style="color:dogerblue">Cookie Policy</a> and <a href ="#" style="color:dogerblue">Privacy Policy</p>
  
  </form>
  <?php
 //getting values from the fields//
 if(isset($_POST['LOGIN'])){
	 $user_EMAIL=mysqli_real_escape_string($con,$_POST['EMAIL']);
	 $user_USERNAME=mysqli_real_escape_string($con,$_POST['USERNAME']);
	 $user_password=mysqli_real_escape_string($con, $_POST['PASSWORD']);
	 
	 $sel= "select * from jobsearch where user_EMAIL='$user_EMAIL' AND user_USERNAME='$user_USERNAME' AND user_password='$user_password' ";
$run= mysqli_query($con,$sel);
$check= mysqli_num_rows($run);
if($check==0){
			echo"<script>alert('EMAIL OR PASSWORD DOES NOT EXIST, TRY AGAIN')</script>";
				exit();
				
}
else{
	 $_SESSION['user_EMAIL']=$user_EMAIL;
	 $_SESSION['user_USERNAME']=$user_USERNAME;
 echo"<script>window.open('new2.php','_self')</script>";
}
 }	 
	 ?>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(fuction(){
	$("button").click(function(){
		$("div").animate({left:'100px'});
	});
});
</script>
	  </body>
	  </html>