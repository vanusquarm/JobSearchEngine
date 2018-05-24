

<!DOCTYPE html>

<?php
SESSION_START();
$con= mysqli_connect("localhost","root","","com") OR die();
$output='';
?>

<html>
<head>
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
	 <link rel = "stylesheet" type="text/css" href="css/quicksearch.css">
<style>
*{
box-sizing:border-box;
}
#myInput{
	width:100px;
	font-size:16px;
	border:1px solid #ddd;
}
</style>
<title>search4jobs</title> 
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
   
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="js/bootstrap.min.js"></script>
   

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
  <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input  type="text" class="form-control" placeholder="Search">
        </div>
			 <select name = "jCAT"id ="set"  data-toggle="tooltip" data-placement="left" title="Job Category">
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
        <button type="submit" class="btn btn-default"  data-toggle="tooltip" data-placement="left" title="submit">Submit</button>
	

      </form>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					  <ul class="nav navbar-nav">
						<li><a href="index.php">HOME <span class="sr-only">(current)</span></a></li>
						<li><a href="SIGN_UP.php">SIGN-UP</a></li>
						  <li><a href="login.php">LOGIN</a></li>
   <li class="active"><a href="QUICKSEARCH.php">QUICK SEARCH</a></li>
   <li class="dropdown" class="navbar navbar-inverse">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">POST A JOB<span class="caret"></span></a>
          <ul class="dropdown-menu" >
            <li><a href="homepage.php">EMPLOYER LOGIN</a></li>
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
							<div class="col-xs-20">

 

  <form action="QUICKSEARCH.php" method="GET">
 


<table class="table table-hover">
  


<tr>
<th> COUNTRY </th>
<th>CITY</th>
<th>JOB CATEGORY</th>
<th> JOB TYPE</th>
<th>COMPANY NAME</th>
<th>UPDATES</th>

</tr>

	</div>
	<div class="col-md-6">
<?php
$sel="select * from employer2";
$run= mysqli_query($con,$sel);

$i=0;
while($row= mysqli_fetch_array($run)){
	$country=$row['COUNTRY'];
	$city=$row['CITY'];
	$JC=$row['JOBCAT'];
	$JT=$row['TYPE_OF_JOB'];
	$C_name=$row['Com_NAME'];
	$i++;

?>
<tr align="center">
<td id="label"><?php echo $country;?></td>
<td id="label"><?php echo $city;?></td>
<td id="label"><?php echo $JC;?></td>
<td id="label"><?php echo $JT;?></td>
<td id="label"><?php echo $C_name;?></td>
<td id="label"> <a href="index.php">apply</a></td>
<td id="demo">

</td>
</tr>


</div>
	</div>
					</div>
				</div>
			</div>
		</div>
   </div>
<?php }?>




</form>
<?php print("$output");?>
</table>

</body>
</html>