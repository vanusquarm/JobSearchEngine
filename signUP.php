<?php
SESSION_START();

$con= mysqli_connect("localhost","root","","qtech") OR die();
?>
<html>
<head>
<title>EMPLOYER SIGN-UP- search4jobs</title> 
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
   <meta http-equiv="Content-Style-Type" content="text/css" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="Content-Language" content="uk" />
   <meta name="author" content="Q-TECH GROUP" /> 
   <meta name="KeyWords" content="search4jobs, Q-TECH Webseite" /> 
   <meta name="Description" content="Q-TECH MAKING JOBS EASY TO FIND JOBS IN GHANA" /> 
	 <link href="css/print.css" rel="stylesheet" type="text/css" media="print" /> 
   <link rel = "stylesheet" type="text/css" href="css/signUP.css">
</head>
<body>
<div id="container"> 
  <a class="skip" href="#main">Navigation</a>
  <div id="logo">
   <a href="index.php">QTEC<span>H</span></a> 
  </div>
  <ul id="menu">
   <li> <a href="index.php">HOME</a> <!-- the left one needs to be adjusted -->
     <li> <a href="SIGN_UP.php">SIGN-UP</a></li> <!-- the left one needs to be adjusted -->
   <li><a href="login.php">LOGIN</a></li>
   <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
     <li>EMPLOYER SIGN-UP </li>
  </ul>
  
  
  <div id="main">
   <h1>WELCOME</h1>
    <h2>JOB4SEARCH EMPLOYER SIGN-UP</h2>
	
	<table>
<tr>
<td id="label" align="right" ><strong>COMPANY EMAIL ADDRESS: </strong><br><br>
</td>
<td ><input onfocus="this.value=''" id="set"type="text" name="EMAIL" placeholder="EMAIL ADDRESS" required="required"/><br><br> <td id="label" align="right" ><br><br>
</td>


<tr>
<td id="label" align="right" ><strong> PASSWORD: </strong><br><br>
</td>
<td><input onfocus="this.value=''" id="set" type="password" name="PASSWORD" placeholder=" PASSWORD" required="required"/><br><br>  <td id="label" align="right" ><strong>CONFIRM PASSWORD: </strong><br><br>
</td>
<td><input onfocus="this.value=''" id="set" type="password" name="CPASSWORD" placeholder="CONFIRM PASSWORD" required="required"/><br><br><td id="label">
</td>

<tr>
<td id="label" align="right" ><strong> PREFERED PIN: </strong><br><br>
</td>
<td><input onfocus="this.value=''" id="set" type="password" name="PASSWORD" placeholder="ENTER PIN" required="required"/><br><br>  <td id="label" align="right" ><br><br>
</td>

<tr>
<td id="label" align="right" ><strong>COUNTRY: </strong><br><br>
</td>
<td ><input onfocus="this.value=''" id="set"type="text" name="COUNTRY" placeholder="COUNTRY" required="required"/><br><br> <td id="label" align="right" ><strong>LOCATION/CITY: </strong><br><br>
</td>
<td><input id="set" type="text" name="LOCATION" placeholder="LOCATION" required="required"/><br><br>
</td>

<tr align="center">
<td colspan="8">
<button><input id="button" type= "submit" name="REGISTER" value= "SUBMIT"> </button>
</td>
</table>

 <?php
 //getting values from the fields//
 if(isset($_POST['REGISTER'])){
	 $user_EMAIL= mysqli_real_escape_string($con,$_POST['EMAIL']);
	 $user_USERNAME=mysqli_real_escape_string($con,$_POST['USERNAME']);
	 $user_password=mysqli_real_escape_string($con, $_POST['PASSWORD']);

</body>
</html>