<!DOCTYPE html>
<?php
SESSION_START();
$_SESSION['com_NAME'];
$con= mysqli_connect("localhost","root","","com") OR die();
?>
<html>
<head>
<title>EMPLOYER PAGE- search4jobs</title> 
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
    <li> <a href="index.php">HOME</a>   <!-- the left one needs to be adjusted -->
   <li>WELOME @ <?php echo $_SESSION['com_NAME'];?></li>
   <li> <a href="SIGN_UP.php">SIGN-UP</a> <!-- the left one needs to be adjusted -->
   <li><a href="login.php">LOGIN</a></li>
   <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
    </ul>
	
	<div id="main">
	 <h1>WELCOME</h1>
    <h2><?php echo $_SESSION['com_NAME'];?> YOU HAVE JUST POSTED A JOB ON OUR SITE.
	PLEASE WAIT PATIENTLY AS WE WOULD UPDATE YOU ON ANY APPLICANTS
	</h2>
 
        <br>
		<br>
		<br>
		
<table align ="center" bgcolor="white" width="1200" id="tableBG2">


<tr>
<th> NAME OF APPLICANTS </th>
<th>VIEW CV</th>
<th>VIEW RESUME</th>
<th> ANY OTHER INFORMATION</th>

</tr>
	

  
  
</body>
</html>