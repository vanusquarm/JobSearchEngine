<!DOCTYPE html>
<?php
SESSION_START();
$_SESSION['user_EMAIL'];
$_SESSION['user_USERNAME'];
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
	<?php echo $_SESSION['user_EMAIL'];?>
</head>
<body>
<div id="container"> 
  <a class="skip" href="#main">Navigation</a>
  <div id="logo">
   <a href="index.php">QTEC<span>H</span></a> 
  </div>
  <ul id="menu">
   <li><?php echo $_SESSION['user_EMAIL'];?></li> <!-- there should not be any link to the link  you are currently on -->
   <li> <a href="index.php">HOME</a> <!-- the left one needs to be adjusted -->
  </ul>
<h1>welcome @ <?php echo $_SESSION['user_USERNAME'];?> </h1>
</body>
</html>