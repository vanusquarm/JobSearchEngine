<!DOCTYPE html>
<?php
SESSION_START();

$con= mysqli_connect("localhost","root","","com") OR die();
?>
<html>
<head>
 <title>LOGIN- search4jobs</title> 
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
    <link rel = "stylesheet" type="text/css" href="css/employer.css">
	
	
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
						  <li><a href="login.php">LOGIN</a></li>
   <li><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
  <li class="dropdown" class="navbar navbar-inverse" >
        <a href="EMPLOYER.php" class="dropdown-toggle" class="active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">POST A JOB<span class="caret"></span></a>
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
		<div id="main">
   <h1>JOB4SEARCH</h1>
    <h2><img src="img/WEL.png" alt="WEL" class="fleft" style="width: 97px; height: 100px;" />POST A JOB </h2>
    <form action="EMPLOYER.php" method="post">
	
      <table>
<tr>
<td id="label" align="right" ><strong> COUNTRY: </strong><br><br>
</td>
<td><input id="set" type="text" name="COUNTRY" placeholder="COUNTRY" required="required"/><br><br>
</td>

<tr>
<tr>
<td id="label" align="right" ><strong>CITY: </strong><br><br>
</td>
<td><input id="set" type="text" name="CITY" placeholder="ENTER CITY" required="required"/><br><br>
</td>

<tr>
<td id="label" align="right" ><strong> JOB CATEGORY: </strong><br><br>
</td>
 <td><select name = "jCAT"id ="set"><br><br>
 <option value ="NULL">---NULL---</option>
 <option value ="ACCOUNTING">ACCOUNTING</option>
 <option value ="ADMIN" >ADMIN AND CLERICAL</option>
 <option value ="AUTOMOTIVE" >AUTOMOTIVE</option>
 <option value ="BANKING" >BANKING</option>
 <option value ="BIOTECH" >BIOTECH</option>
 <option value ="BROADCAST-JOURNALISM" >BROADCAST-JOURNALISM</option>
 <option value ="BUSINESS">BUSINESS DEVELOPMENT</option>
 <option value ="CONSTRUTION" >CONSTRUTION</option>
 <option value ="CONSULTANT" >CONSULTANT</option>
 <option value ="CUSTOMER" >CUSTOMER SERVICE</option>
 <option value ="DESIGN" >DESIGN</option>
 <option value ="DESTRIBUTION" >DESTRIBUTION</option>
 <option value ="EDUCATION">EDUCATION</option>
 <option value ="ENGINEERING" >ENGINEERING</option>
 <option value ="ENTRY" >ENTRY LEVEL</option>
 <option value ="EXECUTIVE" >EXECUTIVE</option>
 <option value ="FACILITIES" >FACILITIES</option>
 <option value ="FRANCHISE" >FRANCHISE</option>
 <option value ="GENRAL">GENRAL BUSINESS</option>
 <option value ="GENRAL_LABOUR" >GENRAL LABOUR</option>
 <option value ="GOVERNMENT" >GOVERNMENT</option>
 <option value ="GROCERY" >GROCERY</option>
 <option value ="HEALTH" >HEALTH CARE</option>
 <option value ="HOTEL" >HOTEL- HOSPITALITY</option>
 <option value ="HUMAN">HUMAN RESOURCES</option>
 <option value ="INFORMATION" >INFORMATION TECHNOLOGY</option>
 <option value ="INSALLATION" >INSALLATION- MAINT- REPAIR</option>
 <option value ="INSURANCE" >INSURANCE</option>
 <option value ="INVENTORY" >INVENTORY</option>
 <option value ="LEGAL" >LEGAL</option>
 <option value ="LEGAL">LEGAL ADMIN</option>
 <option value ="MANAGEMENT" >MANAGEMENT</option>
 <option value ="MANUFACTURING" >MANUFACTURING</option>
 <option value ="MARKERTING" >MARKERTING</option>
 <option value ="MEDIA" >MEDIA- JOURNALISM- NEWSPAPER</option>
 <option value ="NONPROFIT-SOCIAL" >NONPROFIT-SOCIAL SERVICES</option>
  <option value ="NURSE" >NURSE</option>
   <option value ="PHARMACEUTICAL" >PHARMACEUTICAL</option>
 <option value ="PROFESSIONAL" >PROFESSIONAL SERVICES</option>
 <option value "PURCHASING" >PURCHASING- PROCUREMENT</option>
 <option value ="QA" >QA - QUALITY CONTROL</option>
 <option value ="REAL" >REAL ESTATE</option>
  <option value ="RESEARCH" >RESEARCH</option>
   <option value ="RETAIL" >RETAIL</option>
 <option value ="SALES" >SALES</option>
 <option value ="SCIENCE" >SCIENCE</option>
 <option value ="SKILLED" >SKILLED LABOR- TRADES</option>
 <option value ="STRATEGY" >STRATEGY - PLANING</option>
  <option value ="SUPPLY" >SUPPLY CHAIN</option>
   <option value ="TELECOMMUNICATIONS" >TELECOMMUNICATIONS</option>
 <option value ="TRAINING" >TRAINING</option>
 <option value ="TRANSPORTATION" >TRANSPORTATION</option>
 <option value ="WAREHOUSE" >WAREHOUSE</option>

 </select>
<br>
<br>
</td>


<tr>
<tr>
<td id="label" align="right" ><strong>TYPE OF JOB: </strong><br><br>
</td>
<td><input id="set" type="text" name="TYPE_OF_JOB" placeholder="ENTER TYPE OF JOB" required="required"/><br><br>
</td>

<tr>
<tr>
<td id="label" align="right" ><strong>COMPANY NAME: </strong><br><br>
</td>
<td><input  id="set" type="text" name="COMPANY_NAME" placeholder="ENTER CATEGORY" required="required"/><br><br>
</td>

<tr>
<tr align="center">
<td colspan="8">
<input id="button" type= "submit" name="POST" value= "POST">
</td>



	  </table>
	  <br>
	  <br>
	  <br>
	  <p> By sigining in to your account, you agree to Q-TECH <a href ="#" style="color:dogerblue">Terms Of Service</a>.</p>
<p> And consent to our <a href ="#" style="color:dogerblue">Cookie Policy</a> and <a href ="#" style="color:dogerblue">Privacy Policy</p>
  
  </form>

 <?php

 if(isset($_POST['POST'])){
	 $user_COUNTRY= mysqli_real_escape_string($con,$_POST['COUNTRY']);
	 $user_CITY=mysqli_real_escape_string($con,$_POST['CITY']);
	 $user_JOBCAT=mysqli_real_escape_string($con, $_POST['jCAT']);
	 $user_JOBTYPE=mysqli_real_escape_string($con, $_POST['TYPE_OF_JOB']);
     $user_Name=mysqli_real_escape_string($con, $_POST['COMPANY_NAME']);
	 
	

		
	 $insert="insert into employer2( COUNTRY,CITY,JOBCAT,TYPE_OF_JOB,Com_NAME) values('$user_COUNTRY','$user_CITY','$user_JOBCAT','$user_JOBTYPE','$user_Name')";
     $run_insert= mysqli_query($con, $insert);
	 if($run_insert){
		 echo"<script>alert('JOB POSTED SUCCESSFULLY')</script>";
		 echo"<script>window.open('SEARCHBLOCK.php','_self')</script>";
		  $_SESSION['com_NAME']= $user_Name;
		 
		 }


		 
 }	 
	 ?>
	  </body>
	  </html>