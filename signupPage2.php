<!DOCTYPE html>
<?php
SESSION_START();
$_SESSION['user_EMAIL'];
$_SESSION['user_USERNAME'];
$con= mysqli_connect("localhost","root","","resume2") OR die();
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
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
   <meta http-equiv="Content-Style-Type" content="text/css" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="Content-Language" content="uk" />
   <meta name="author" content="Q-TECH GROUP" /> 
   <meta name="KeyWords" content="search4jobs, Q-TECH Webseite" /> 
   <meta name="Description" content="Q-TECH MAKING JOBS EASY TO FIND JOBS IN GHANA" /> 
	 <link href="css/print.css" rel="stylesheet" type="text/css" media="print" /> 
   <link rel = "stylesheet" type="text/css" href="css/new 1.css">
</head>
<body>
 <div id="container"> 
  <div id="logo">
   <a href="index.php">QTEC<span>H</span></a> 
  </div>
   <ul id="menu">
   <li><?php echo $_SESSION['user_EMAIL'];?></li> <!-- there should not be any link to the link  you are currently on -->
   <li> <a href="index.php">HOME</a> <!-- the left one needs to be adjusted -->
     <li> <a href="SIGN_UP.php">SIGN-UP</a> <!-- the left one needs to be adjusted -->
   <li><a href="login.php">LOGIN</a></li>
   <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
   <li><a href="EMPLOYER.php">POST A JOB(EMPLOYERS) </a></li>
  </ul>
  <form action="signupPage2.php" method="post" enctype="multipart/form-data">
  
<table align ="center" bgcolor="white" width="900">
<tr>
 <div id="Button">
<td><h1>Welcome <?php echo $_SESSION['user_USERNAME'];?> </h1></td>
</tr>
<tr>
<td><h2> STEP 2 OF 3 </h2>
</tr>
<tr>
<td><h3> REQUIRED * </h3></td>
</tr>

<tr>
<td><h4>GET STARTED BY CREATING YOUR RESUME</h4>
<h5> INFOMATION</h5></td>

<tr id="input">
<td align="left" ><strong> CITY *: </strong><br>
<input type="text" name="CITY"  required="required" id="set"/><br><br>
</td>
</tr>

<tr id="input">
<td align="left" ><strong> YOUR CURRENT LOCATION*: </strong><br>
<input type="text" name="LOCATION"  required="required"id="set"/><br><br>
</td>
</tr>
<tr id="input_23">
<td align="left" ><strong> YOUR HIGHEST DEGREE *: </strong><br><br>
<input type="text" name="HDEGREE"  required="required"id="set"/><br><br>
 </td>
 
 <td align="left" ><strong> YOUR MOST RECENT TITLE *: </strong><br><br>
<input type="text" name="TITLE"  required="required"id="set"/><br><br>
 </td>
 
<tr>
<td><input align= "center" type= "submit" name="SAVE" value= "NEXT"id="button" ></td>
</tr>

<tr>
<td><h6> By creating a Q-TECH RESUME you agree to our <a href ="#" style="color:dogerblue">Terms & Privacy</a>.</h5></td>
</tr>
</table>


</form>
 <?php
 //getting values from the fields//
 if(isset($_POST['SAVE'])){
	 $user_CITY= mysqli_real_escape_string($con,$_POST['CITY']);
	 $user_LOCATION=mysqli_real_escape_string($con,$_POST['LOCATION']);
	  $user_HDEGREE= mysqli_real_escape_string($con,$_POST['HDEGREE']);
	  $user_TITLE= mysqli_real_escape_string($con,$_POST['TITLE']);
	  if($user_LOCATION==''){
		  
		  echo"<script>alert('YOU LEFT OUT YOUR LOCATION !!')</script>";
	  }
	   else{
	 $insert= "insert into resume_table2 (CITY, LOCATION,HIGHEST_DEGREE,JOB_TITLE) values('$user_CITY','$user_LOCATION','$user_HDEGREE',user_TITLE)";
     $run_insert= mysqli_query($con, $insert);
	 if($run_insert){
		  $_SESSION['user_USERNAME']=$user_USERNAME;
		  header("location:next.php");
 
 }
 }
 
 }
 
 ?>

</body>
</html>






