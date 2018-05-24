<!DOCTYPE html> 
<?php
SESSION_START();
$con= mysqli_connect("localhost","root","","qtech") OR die();
?>
<html>
<head>
<title>LOGIN PAGE- search4jobs</title> 
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
   <meta http-equiv="Content-Style-Type" content="text/css" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="Content-Language" content="uk" />
   <meta name="author" content="Q-TECH GROUP" /> 
   <meta name="KeyWords" content="search4jobs, Q-TECH Webseite" /> 
   <meta name="Description" content="Q-TECH MAKING JOBS EASY TO FIND JOBS IN GHANA" /> 
	 <link href="css/print.css" rel="stylesheet" type="text/css" media="print" /> 
   <link rel = "stylesheet" type="text/css" href="css/LOGINpage.css">
</head>
<body>
<div id="container"> 
  <a class="skip" href="#main">Navigation</a>
  <div id="logo">
   <a href="index.php">QTEC<span>H</span></a> 
  </div>
  <ul id="menu">
   <li> <a href="index.php">HOME</a> <!-- the left one needs to be adjusted -->
   <li>EMPLOYER LOGIN</li>
    <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
   <li><a href="EMPLOYER.php">POST A JOB(EMPLOYERS) </a></li>

  </ul>
  
       <dl id="subnav"> <!--this subnav can be changed by the content. it is not pinted --> 
	 <ul id="menu_34"> 
  <h3 align="left">SIGN IN WITH <dd><br>
     <li><a href="https://facebook.com">FACEBOOK</a><img src="img/FB.png" alt="FB" class="fleft" style="width: 35px; height: 25px;"/><br><br>
   <li> <img src="img/GOOGLE.png" alt="GOOGLE" class="fleft" style="width: 35px; height: 25px;" /><a href="https://www.google.com.uk/">GOOGLE</a><br> <!-- the left one needs to be adjusted -->
    
  </ul>
  <p>All job search information is kept private.</p>
  <p>We wont post anything without your permission</p>
   <dd>&copy;2018 Q-TECH GROUP</dd>
  </dd></h3>
	</dl>
		<div id="main">
   <h1>JOB4SEARCH</h1>
      <h2><img src="img/WEL.png" alt="WEL" class="fleft" style="width: 97px; height: 100px;" /><img src="img/QTECH.jpg" alt="QTECH" class="fleft" style="width: 97px; height: 100px;" />LOGIN</h2>
   </div>
   <form action="ER.php" method="post">
	
      <table>
<tr>
<td id="label"align="right" ><strong> COMPANYS NAME: </strong><br><br>
</td>
<td><input id="set" type="text" name="COMPANY'S NAME" placeholder="NAME OF COMPANY" required="required"/><br><br>
</td>


<tr>
<td  id="label" align="right" ><strong>SEPCIAL PIN: </strong><br><br>
</td>
<td><input  type="text" name="PIN" placeholder="SPECIAL PIN" required="required" id="set" /><br><br>
</td>

<tr>
<tr>
<td id="label" align="right" ><strong>PASSWORD: </strong><br><br>
</td>
<td ><input id="set" type="password" name="PASSWORD" placeholder="ENTER PASSWORD" required="required"/><br><br>
</td>
<tr>
<tr align="center">
<td colspan="8">
<input id="button" type= "submit" name="LOGIN" value= "LOGIN">
</td>



	  </table>
	  <br>
	  <br>
	  <br>
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
</body>
</html>
