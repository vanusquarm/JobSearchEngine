<?php  $page_title="User Profile";
	
include('includes/header.php'); 
include('includes/function.php');

require_once("library/HTMLPurifier.auto.php");

$config1 = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config1);

//All Company
$company_qry="SELECT * FROM tbl_company  WHERE `user_id` ='".$_GET['user_id']."'";
$company_result=mysqli_query($mysqli,$company_qry); 
$company_row=mysqli_fetch_assoc($company_result);  

	$user_id=strip_tags(addslashes(trim($_GET['user_id'])));

	if(!isset($_GET['user_id']) OR $user_id==''){
		header("Location: manage_users.php");
	}

    $user_qry="SELECT * FROM tbl_users WHERE id='$user_id'";
    $user_result=mysqli_query($mysqli,$user_qry);

    if(mysqli_num_rows($user_result)==0){
    	header("Location: manage_users.php");
    }

    $user_row=mysqli_fetch_assoc($user_result);

    $user_img='';

	if($user_row['user_image']!='' && file_exists('images/'.$user_row['user_image'])){
		$user_img='images/'.$user_row['user_image'];
	}
	else{
		$user_img='assets/images/user-icons.jpg';
	}


    if(isset($_POST['btn_submit']))
    {	

	 $flage=0;

	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		
	} else {
		$_SESSION['msg'] = "23";
		 header("Location:user_profile.php?user_id=".$user_id);
		exit;
	}

	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
	$phone_to_check = str_replace("-", "", $phone);

	if (strlen($phone_to_check) > 10 || strlen($phone_to_check) > 14) {
		
	} else {
		$_SESSION['msg'] = "24";
		 header("Location:user_profile.php?user_id=".$user_id);
		exit;
	}
    	
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        {
            $_SESSION['class']="warn";
            $_SESSION['msg']="invalid_email_format";
        }
        else{

            $email=cleanInput($_POST['email']);

            $sql="SELECT * FROM tbl_users WHERE `email` = '$email' AND `id` <> '".$user_id."'";

            $res=mysqli_query($mysqli, $sql);

            if(mysqli_num_rows($res) == 0){
                $data = array(
                    'name'  =>  filter_var($_POST['name'], FILTER_SANITIZE_STRING),
                    'email'  =>  $email,
                    'phone'  =>  $phone_to_check,
                    'city'  => filter_var($_POST['city'], FILTER_SANITIZE_STRING),
			        'address'  => filter_var($_POST['address'], FILTER_SANITIZE_STRING),
			        'user_resume' => $user_resume,
			        'current_company_name'  =>filter_var($_POST['current_company_name'], FILTER_SANITIZE_STRING),
			        'experiences'  => filter_var($_POST['experiences'], FILTER_SANITIZE_STRING),
			        'gender'  =>  filter_var($_POST['gender'], FILTER_SANITIZE_STRING),
			        'date_of_birth'  =>  strtotime($_POST['date_of_birth']),
			        'skills'  => filter_var($_POST['skills'], FILTER_SANITIZE_STRING)
                );

                if($_POST['password']!="")
                {

                    $password=md5(trim($_POST['password']));

                    $data = array_merge($data, array("password"=>$password));
                }

                if($_FILES['user_image']['name']!="")
                {

                    if($user_row['user_image']!="" OR !file_exists('images/'.$user_row['user_image']))
                    {
                        unlink('images/'.$user_row['user_image']);
                    }

                    $ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);

                    $user_image=rand(0,99999).'_'.date('dmYhis')."_user.".$ext;

                    //Main Image
                    $tpath1='images/'.$user_image;   

                    if($ext!='png')  {
                      $pic1=compress_image($_FILES["user_image"]["tmp_name"], $tpath1, 80);
                    }
                    else{
                      $tmp = $_FILES['user_image']['tmp_name'];
                      move_uploaded_file($tmp, $tpath1);
                    }

                    $data = array_merge($data, array("user_image" => $user_image));

                }

                $user_edit=Update('tbl_users', $data, "WHERE id = '".$user_id."'");
                
                if($_FILES['company_logo']['name']!="")
    			  {

	    	  $img_res_company=mysqli_query($mysqli,'SELECT * FROM tbl_company WHERE `id`='.$_GET['id'].'');
	          $img_res_row_company=mysqli_fetch_assoc($img_res_company);

		          if($img_res_row_company['company_logo']!="")
		            {
		              unlink('images/'.$img_res_row_company['company_logo']);
		           }

		    $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);

			$company_logo=rand(0,99999).".".$ext;

			//Main Image
			$tpath='images/'.$company_logo;

			if($ext!='png') {
			$pic1=compress_image($_FILES["company_logo"]["tmp_name"], $tpath, 80);
			}
			else{
			$tmp = $_FILES['company_logo']['tmp_name'];
			move_uploaded_file($tmp, $tpath);
			} 
			$clean_company_desc = $purifier->purify($_POST['company_desc']);      	     
	       $data1 = array(
		        'company_name'  =>  filter_var($_POST['skills'], FILTER_SANITIZE_STRING),
		        'company_email'  =>  filter_var($_POST['company_email'], FILTER_SANITIZE_EMAIL),
		        'mobile_no'  =>  filter_var($_POST['mobile_no'], FILTER_SANITIZE_NUMBER_INT),
		        'company_address'  => filter_var($_POST['company_address'], FILTER_SANITIZE_STRING),
		        'company_desc'  =>  addslashes($clean_company_desc),
		        'company_website'  => filter_var($_POST['company_website'], FILTER_SANITIZE_STRING),
		        'company_work_day'  => filter_var($_POST['company_work_day'], FILTER_SANITIZE_STRING),
		        'company_work_time'  => filter_var($_POST['company_work_time'], FILTER_SANITIZE_STRING),
		        'company_logo' => $company_logo

	             );
	   
	   }else{
	     
	     	$data1 = array(
		         'company_name'  =>  filter_var($_POST['skills'], FILTER_SANITIZE_STRING),
		        'company_email'  =>  filter_var($_POST['company_email'], FILTER_SANITIZE_EMAIL),
		        'mobile_no'  =>  filter_var($_POST['mobile_no'], FILTER_SANITIZE_NUMBER_INT),
		        'company_address'  => filter_var($_POST['company_address'], FILTER_SANITIZE_STRING),
		        'company_desc'  =>  addslashes($clean_company_desc),
		        'company_website'  => filter_var($_POST['company_website'], FILTER_SANITIZE_STRING),
		        'company_work_day'  => filter_var($_POST['company_work_day'], FILTER_SANITIZE_STRING),
		        'company_work_time'  => filter_var($_POST['company_work_time'], FILTER_SANITIZE_STRING)
		        
	        	 );
	       }
	        
	        $company_edit=Update('tbl_company', $data1, " WHERE `user_id` = '".$user_id."'");

                $_SESSION['class']="success";

                $_SESSION['msg']="11";
            }
            else{
                $_SESSION['class']="warn";
                $_SESSION['msg']="email_exist";
            }
        }
		
		$_SESSION['msg']="11";
        header("Location:user_profile.php?user_id=".$user_id);
        exit;
    }

    function get_job_info($job_id)
    {
      global $mysqli;

      $qry_job="SELECT * FROM tbl_jobs WHERE `id` ='".$job_id."'";
      $res_job= mysqli_fetch_array(mysqli_query($mysqli,$qry_job));
       
      return  $res_job['job_name']; 

    }

  function get_cat_info($cat_id,$field_name) 
    {
      global $mysqli;

      $qry_cat="SELECT * FROM tbl_category WHERE cid='".$cat_id."'";
      $query1=mysqli_query($mysqli,$qry_cat);
      $row_cat = mysqli_fetch_array($query1);

      $num_rows1 = mysqli_num_rows($query1);

      if ($num_rows1 > 0)
      {     
        return $row_cat[$field_name];
      }
      else
      {
        return "";
      }
    } 

    function get_user_name($user_id) 
	 {
	 	global $mysqli;

	 	$qry_video="SELECT * FROM tbl_users WHERE id='".$user_id."'";
	 	$query1=mysqli_query($mysqli,$qry_video);
		$row_video = mysqli_fetch_array($query1);

			return $row_video['name'];
	 }
?>

<link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css">
<style>
#applied_user .dataTables_wrapper .top{
	position: relative;
	width: 100%;
}	
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

 <script type="text/javascript">
$(document).ready(function(e) {
           $("#user_type").change(function(){
          
           var type=$("#user_type").val();
              
             if(type=="1")
              {
                 
                  $("#book_local_display").show();
                  $("#local_display").hide();
              }
              else
              {   
                $("#book_local_display").hide();
                 $("#local_display").show();
 
              }    
              
         });
        });

	$(document).ready(function(e) {
           $("#user_type").change(function(){
          
           var type=$("#user_type").val();
              
             if(type=="1")
              {
                 
                  $("#gender_display").show();
                  $("#date_display").show();
                  $("#skill_display").show();
                  $("#experi_display").show();
                  $("#company_display").show();

              }
              else
              {   
                $("#gender_display").hide();
                $("#date_display").hide();
                $("#skill_display").hide();
                $("#experi_display").hide();
                $("#company_display").hide();
              }    
              
         });
        });
    </script>    
<div class="row">
	<div class="col-lg-12">
		<?php
			if(isset($_GET['redirect'])){
	          echo '<a href="'.$_GET['redirect'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	        }
	        else{
	         	echo '<a href="manage_users.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	        }
		?>
		<div class="page_title_block user_dashboard_item" style="background-color: #333;border-radius:6px;0 1px 4px 0 rgba(0, 0, 0, 0.14);border-bottom:0">
			<div class="user_dashboard_mr_bottom">
			  <div class="col-md-12 col-xs-12"> <br>
				<span class="badge badge-success badge-icon">
				  <div class="user_profile_img">
					<img type="image" src="<?php echo $user_img;?>" alt="image" style=""/>
				  </div>
				  <span style="font-size: 14px;"><?php echo $user_row['name'];?>				
				  </span>
				</span>  
				<span class="badge badge-success badge-icon">
				<i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
				<span style="font-size: 14px;text-transform: lowercase;"><?php echo $user_row['email'];?></span>
				</span> 
				<span class="badge badge-success badge-icon">
				  <strong style="font-size: 14px;">Registered At:</strong>
				  <span style="font-size: 14px;"><?php echo (date('d-m-Y',$user_row['register_date']));?>  
				</span>
				</span>
			  </div>
			</div>
		</div>

		  <div class="card card-tab">
			<div class="card-header" style="overflow-x: auto;overflow-y: hidden;">
				<ul class="nav nav-tabs" role="tablist">
		            <li role="dashboard" class="active"><a href="#edit_profile" aria-controls="edit_profile" role="tab" data-toggle="tab">Edit Profile</a></li>
		             <?php if($user_row['id']!='0'){?>
		            <?php if($user_row['user_type']!='2'){?>
		            <li role="applied_user"><a href="#applied_user" aria-controls="applied_user" role="tab" data-toggle="tab">Applied jobs</a></li>
		            <?php }?>
		            <li role="favourite_news"><a href="#favourite_news" aria-controls="favourite_news" role="tab" data-toggle="tab">Users Favourite Jobs</a></li>
		            <?php if(($user_row['id']==$company_row['user_id'])==2){?>
		            <li role="users_job"><a href="#users_job" aria-controls="users_job" role="tab" data-toggle="tab">Users Manage Jobs</a></li>
		        	<?php }?>
		        	<?php }?>	
		        </ul>
			</div>
			<div class="card-body no-padding tab-content">
				<div role="tabpanel" class="tab-pane active" id="edit_profile">
					<div class="row">
						<div class="col-md-12">
							<form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
					          <div class="section">
					            <div class="section-body">
					              <div class="form-group">
					                <label class="col-md-3 control-label">Name :-</label>
					                <div class="col-md-6">
					                  <input type="text" name="name" id="name" value="<?=$user_row['name']?>" class="form-control" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Email :-</label>
					                <div class="col-md-6">
					                  <input type="email" name="email" id="email" value="<?php echo $user_row['email'];?>" class="form-control" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Password :-</label>
					                <div class="col-md-6">
					                  <input type="password" name="password" id="password" value="" class="form-control">
					                </div>
					              </div>
					              <div class="form-group">
					                <label class="col-md-3 control-label">Phone :-</label>
					                <div class="col-md-6">
					                  <input type="text" name="phone" id="phone" value="<?php echo $user_row['phone'];?>" class="form-control">
					                </div>
					              </div>
					              	<div class="form-group">
				                    <label class="col-md-3 control-label">City :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="city" id="city" value="<?php if(isset($_GET['user_id'])){echo $user_row['city'];}?>" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Address :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="address" id="address" value="<?php if(isset($_GET['user_id'])){echo stripslashes($user_row['address']);}?>" class="form-control">
				                    </div>
				                  </div>
				                   <div class="form-group" id="company_display" <?php if($user_row['user_type']=='1'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
				                    <label class="col-md-3 control-label">Current Company Name :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="current_company_name" id="current_company_name" value="<?php if(isset($_GET['user_id'])){echo stripslashes($user_row['current_company_name']);}?>" class="form-control">
				                    </div>
				                  </div>
				                 <div class="form-group" id="experi_display" <?php if($user_row['user_type']=='1'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
				                    <label class="col-md-3 control-label">Experiences :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="experiences" id="experiences" value="<?php if(isset($_GET['user_id'])){echo stripslashes($user_row['experiences']);}?>" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group" id="skill_display" <?php if($user_row['user_type']=='1'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
				                    <label class="col-md-3 control-label">skills :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="skills" id="skills" value="<?php if(isset($_GET['user_id'])){echo $user_row['skills'];}?>" class="form-control" data-role="tagsinput">
				                    </div>
				                  </div>
				                  <br>
				                   <div id="gender_display" class="form-group" <?php if($user_row['id']){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
				                  	 <label class="col-md-3 control-label">Gender :-</label>
				                  	  <div class="col-md-6">
									<select name="gender" id="gender" class="select2" required>
										<option value="">select Gender</option>
										<option value="Male" <?php if(isset($user_row['id']) && $user_row['gender']=="Male"){?>selected<?php }?>>Male</option>
										<option value="Female" <?php if(isset($user_row['id']) && $user_row['gender']=="Female"){?>selected<?php }?>>Female</option>                        
									</select>
								  </div>
								</div>
				                  <div id="date_display" class="form-group" <?php if($user_row['id']){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
					                  <label class="col-md-3 control-label">Date of birth :-</label>
					                  <div class="col-md-6">
						            <input type="text" name="date_of_birth" id="date_of_birth" value="<?php if($user_row['date_of_birth'] !=NULL){echo date('d-m-Y',$user_row['date_of_birth']);}?>" placeholder="DD/MM/YY" class="form-control datepicker" required autocomplete="off">
									</div>
									</div>
									<div class="form-group">
					                <label class="col-md-3 control-label">Profile Image :-
					                <p class="control-label-help">(Recommended resolution: 100x100, 200x200) OR Squre image</p>
					                </label>
					                <div class="col-md-6">
					                  <div class="fileupload_block">
					                    <input type="file" name="user_image" value="fileupload" accept=".png, .jpg, .jpeg, .svg, .gif" <?php echo (!isset($_GET['user_id'])) ? 'required="require"' : '' ?> id="fileupload" onchange="readURL(this);">
					                    <div class="fileupload_img">
					                    <?php 
					                      $img_src="";
					                      if(!isset($_GET['user_id']) || $user_row['user_image']==''){
					                          $img_src='assets/images/landscape.jpg';
					                      }else{
					                          $img_src='images/'.$user_row['user_image'];
					                      }
					                    ?>
					                    <img type="image" src="<?=$img_src?>" alt="image" id="ImdID"/>
					                    </div>   
					                  </div>
					                </div>
					              </div>
					              
								<div id="book_local_display" class="form-group" <?php if($user_row['user_type']=='2'){?>style="display:none;"<?php }else{?>style="display:block"<?php }?>>
			                    <label class="col-md-3 control-label">User Resume :-</label>
			                    <div class="col-md-6">
			                      <div class="fileupload_block">
			                        <input type="file" name="user_resume" value="" id="fileupload">
			                            <?php if(isset($_GET['user_id']) and $user_row['user_resume']!="") {?>
			                            <input type="hidden" name="old_user_resume" value="<?php echo $user_row['user_resume'];?>" id="fileupload">

			                            <a href="uploads/<?php echo $user_row['user_resume'];?>" class="btn btn-success btn-sm" target="_blank"><i class="icon fa fa-cloud-download" aria-hidden="true"></i></a>


			                          <?php }?>
				                      </div>
				                    </div>
				                  </div>
				                 <?php if($user_row['id'] AND ($company_row['user_id'])!=''){?>
				                 <div id="local_display" <?php if($user_row['user_type']=='2'){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
				                  <hr>
				                  <h4>Company Details</h4>
				                  <hr>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Company Name :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_name" id="company_name" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_name']);}?>" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Company Email :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_email" id="company_email" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_email']);}?>" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Mobile No :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="mobile_no" id="mobile_no" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['mobile_no']);}?>" class="form-control">
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Company Address :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_address" id="company_address" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_address']);}?>" class="form-control">
				                    </div>
				                  </div>
				                   <div class="form-group">
				                    <label class="col-md-3 control-label">Company Website :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_website" id="company_website" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_website']);}?>" class="form-control">
				                    </div>
				                  </div>
				                   <div class="form-group">
				                    <label class="col-md-3 control-label">Company Work Day :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_work_day" id="company_work_day" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_work_day']);}?>" class="form-control">
				                    </div>
				                  </div>
				                   <div class="form-group">
				                    <label class="col-md-3 control-label">Company Work Time :-</label>
				                    <div class="col-md-6">
				                      <input type="text" name="company_work_time" id="company_work_time" value="<?php if(isset($_GET['user_id'])){echo stripslashes($company_row['company_work_time']);}?>" class="form-control" required>
				                    </div>
				                  </div>
				                 <div class="form-group">
				                    <label class="col-md-3 control-label">Company Logo :-</label>
				                    <div class="col-md-6">
				                      <div class="fileupload_block">
				                        <input type="file" name="company_logo" value="" id="fileupload" onchange="readURLimg(this);">
				                            <?php if(isset($_GET['user_id']) and $company_row['company_logo']!="") {?>
				                            <input type="hidden" name="old_user_image" value="<?php echo $company_row['company_logo'];?>" id="fileupload">
				                            <div class="fileupload_img"><img type="image" src="images/<?php echo $company_row['company_logo'];?>" alt=" image" id="ImdID1"/></div>
				                          <?php } else {?>
				                            <div class="fileupload_img"><img type="image" src="assets/images/landscape.jpg" alt="category image" id="ImdID1"/></div>
				                          <?php }?>
				                      </div>
				                    </div>
				                  </div>
				                  <div class="form-group">
				                    <label class="col-md-3 control-label">Description :-</label>
				                    <div class="col-md-6">
				                      <textarea name="company_desc" id="company_desc" class="form-control"><?php echo stripslashes($company_row['company_desc']);?></textarea>
				                      <script>CKEDITOR.replace( 'company_desc' );</script>
				                    </div>
				                  </div>
				              	</div>
				                   <?php }?>
				                   <br>
					              <div class="form-group">
					                <div class="col-md-9 col-md-offset-3">
					                  <button type="submit" name="btn_submit" class="btn btn-primary">Save</button>
					                </div>
					              </div>
					            </div>
					          </div>
					        </form>
						</div>
					</div>
					</div>
					
					<div role="tabpanel" class="tab-pane" id="favourite_news">
		             
							<div class="row">
							 <div class="col-md-12">
						      	<?php

						      	  $user_id=$_GET['user_id'];

								  //Favourite list    
								  $tableName="tbl_jobs";   
								  $targetpage = "user_profile.php";   
								  $limit = 12; 
								  
								  $query = "SELECT COUNT(*) as num FROM tbl_saved WHERE tbl_saved.`user_id` = '".$user_id."'";
								  $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
								  $total_pages = $total_pages['num'];
								  
								  $stages = 1;
								  $page=0;
								  if(isset($_GET['page'])){
								  $page = mysqli_real_escape_string($mysqli,$_GET['page']);
								  }
								  if($page){
								    $start = ($page - 1) * $limit; 
								  }else{
								    $start = 0; 
								  }

								 $users_qry="SELECT * FROM tbl_saved
											 LEFT JOIN tbl_users ON tbl_saved.`user_id`= tbl_users.`id`
											 LEFT JOIN tbl_jobs ON tbl_saved.`job_id`= tbl_jobs.`id`
											 WHERE tbl_saved.`user_id` = '$user_id'
											 ORDER BY tbl_saved.`sa_id` DESC LIMIT $start, $limit";

	  							$user_result = mysqli_query($mysqli,$users_qry)or die(mysqli_error($mysqli));

					      		$i=0;
					      		while ($row=mysqli_fetch_assoc($user_result)){
					      		 ?>
					      		 <div class="col-lg-4 col-sm-6 col-xs-12">
					            <div class="block_wallpaper" style="box-shadow:2px 3px 5px #333;">
					              <div class="wall_category_block">
					               <h2><?=ucwords(get_cat_info($row['cat_id'],'category_name'))?></h2>  

					              <div class="checkbox" style="float: right;margin-right: 0px;z-index: 5;">
				                     <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>" class="post_ids">
				                      <label for="checkbox<?php echo $i;?>">
				                      </label>
				                    </div> 
				                    </div>   
					              <div class="wall_image_title">
					              	 <h2><?php echo $row['job_name'];?></h2>  
					                <ul>
					                  <li><a href="edit_job.php?job_id=<?php echo $row['id'];?>&action=edit&redirect=<?=$redirectUrl?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>

					                  <li><a href="javascript:void(0)" data-id="<?php echo $row['sa_id'];?>" class="btn_delete_as" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></li>

					                  <?php if($row['status']!="0"){?>
					                   <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li> 

					                  <?php }else{?>
					                  
					                   <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-id="<?=$row['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li> 
					              
					                  <?php }?>
					                </ul>
					              </div>
					               <span><img src="images/<?php echo $row['job_image'];?>" /></span>
					             </div>
					          </div>
						      <?php
						        $i++;
						        }
						      ?>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="users_job">
		            
							<div class="row">
							 <div class="col-md-12">
						      	<?php

					       	    $sql1="SELECT tbl_category.`category_name`,tbl_jobs.* FROM tbl_jobs
					      			LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
					      			WHERE tbl_jobs.`user_id`='$user_id' ORDER BY tbl_jobs.`id` DESC";

					      		$res1=mysqli_query($mysqli,$sql1) or die(mysqli_error($mysqli));
					      		$i=0;
					      		while ($row1=mysqli_fetch_assoc($res1)){
					      		 ?>
					      		 <div class="col-lg-4 col-sm-6 col-xs-12">
					            <div class="block_wallpaper" style="box-shadow:2px 3px 5px #333;">
					              <div class="wall_category_block">
					               <h2><?php echo $row1['category_name'];?></h2>  
					              </div>
					              <div class="wall_image_title">
					              	 <h2><?php echo $row1['job_name'];?></h2>  
					                <ul>

					                  <li><a href="edit_job.php?job_id=<?php echo $row1['id'];?>&action=edit&redirect=<?=$redirectUrl?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>

					                  <li><a href="" data-toggle="tooltip" data-tooltip="Delete" class="btn_delete_a" data-id="<?php echo $row1['id'];?>"><i class="fa fa-trash"></i></a></li>

					                  <?php if($row1['status']!="0"){?>
					                   <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row1['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li> 

					                  <?php }else{?>
					                  
					                   <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?=$row1['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li> 
					              
					                  <?php }?>
					                </ul>
					              </div>
					               <span><img src="images/<?php echo $row1['job_image'];?>" /></span>
					             </div>
					          </div>
						      <?php
						        $i++;
						        }
						      ?>
							</div>
						</div>
					</div>
				  <div role="tabpanel" class="tab-pane" id="applied_user">
					<div class="row">
						<div class="col-md-12">
							<table class="datatable table table-striped table-bordered table-hover">
					          <thead>
					            <tr>
					              <th>Job Title</th>
				                  <th>Name</th>						 
								  <th>Email</th>
								  <th>Phone</th>
								  <th>Resume</th>
								  <th>Apply Date</th>
								  <th>Status</th>
					            </tr>
					          </thead>
					          <tbody>
						      	<?php

					      		$sql="SELECT * FROM tbl_apply
									 LEFT JOIN tbl_users ON tbl_users.`id`= tbl_apply.`user_id`
									 WHERE tbl_apply.`user_id`='$user_id'
									 ORDER BY tbl_apply.`ap_id` DESC";

					      		$res=mysqli_query($mysqli, $sql);
					      		$i=1;
					      		while ($users_row=mysqli_fetch_assoc($res)) {
					      			?>
					      			<tr>
					      			   <td><?php echo get_job_info($users_row['job_id']);?></td> 
							           <td><?php echo $users_row['name'];?></td>
							           <td><?php echo $user_row['email']; ?></td>   
							           <td><?php echo $user_row['phone']; ?></td>
							           <td><?php if(isset($users_row['user_resume'])){?><a href="<?php echo 'uploads/'.$users_row['user_resume'];?>" class="btn btn-success btn-xs" target="_blank" style="padding: 5px 10px;">Resume</a><?php }?></td> 
		           						<td><?php echo $users_row['apply_date'];?></td>
		           						<td> 
								        <a href="javascript:void(0)" data-id="<?php echo $users_row['ap_id'];?>" class="btn btn-danger btn_delete" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a> 
									        </td>
					      			</tr>
					      			<?php $i++; }?>
						      </tbody>
						  	</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('includes/footer.php');?>

<script type="text/javascript">

  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
  });
  var activeTab = localStorage.getItem('activeTab');
  if(activeTab){
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }
  $('a[data-toggle="tab"]').click(function(e){
  	location.reload();
  });

  $(".toggle_btn a").on("click",function(e){
    e.preventDefault();
    var _for=$(this).data("action");
    var _id=$(this).data("id");
    var _column=$(this).data("column");
    var _table='tbl_jobs';

    $.ajax({
      type:'post',
      url:'processData.php',
      dataType:'json',
      data:{id:_id,for_action:_for,column:_column,table:_table,'action':'toggle_status','tbl_id':'id'},
      success:function(res){
          console.log(res);
          if(res.status=='1'){
            location.reload();
          }
        }
    });

  });


  $(".btn_delete_a").click(function(e){
    e.preventDefault();

    var _ids=$(this).data("id");
    var _tbl_nm='tbl_jobs';

    swal({
        title: "Are you sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger btn_edit",
        cancelButtonClass: "btn-warning btn_edit",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {

          $.ajax({
            type:'post',
            url:'processData.php',
            dataType:'json',
             data:{id:_ids,tbl_nm:_tbl_nm,'action':'multi_delete'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  swal({
                      title: "Successfully", 
                      text: "News is deleted...", 
                      type: "success"
                  },function() {
                      location.reload();
                  });
                }
              }
          });
        }
        else{
          swal.close();
        }
    });
  });


  $(".btn_delete_as").click(function(e){
    e.preventDefault();

    var _ids=$(this).data("id");
    var _tbl_nm='tbl_saved';

    swal({
        title: "Are you sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger btn_edit",
        cancelButtonClass: "btn-warning btn_edit",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {

          $.ajax({
            type:'post',
            url:'processData.php',
            dataType:'json',
             data:{id:_ids,tbl_nm:_tbl_nm,'action':'multi_delete'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  swal({
                      title: "Successfully", 
                      text: "Favourite is deleted...", 
                      type: "success"
                  },function() {
                      location.reload();
                  });
                }
              }
          });
        }
        else{
          swal.close();
        }
    });
  });


$("button[name='delete_rec']").click(function(e){
      e.preventDefault();

      var _ids = $.map($('.post_ids:checked'), function(c){return c.value; });

      if(_ids!='')
      {
        if(confirm("Are you sure you want to delete this Jobs?")){
          $.ajax({
            type:'post',
            url:'processData.php',
            dataType:'json',
            data:{id:_ids,'action':'multi_delete','tbl_nm':'tbl_jobs'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  location.reload();
                }
                else if(res.status=='-2'){
                  alert(res.message);
                }
              }
          });
        }
      }
      else{
        alert("No Jobs selected");
      }
  });


 $(".btn_delete").click(function(e){
		e.preventDefault();

		var _ids=$(this).data("id");

		swal({
          title: "Are you sure to delete?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger btn_edit",
          cancelButtonClass: "btn-warning btn_edit",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: false,
          closeOnCancel: false,
          showLoaderOnConfirm: true
        },

        function(isConfirm) {
          if (isConfirm) {
            $.ajax({
              type:'post',
              url:'processData.php',
              dataType:'json',
              data:{ids:_ids,'action':'removeapply'},
              success:function(res){
                  console.log(res);
                  if(res.status=='1'){
                    swal({
					  title: "Successfully", 
					  text: "Applied has been deleted...", 
					  type: "success"
					},function() {
					  location.reload();
					});
                  }
                  else{
                  	swal("Something went to wrong !");
                  }
                }
            });
          }
          else{
            swal.close();
          }

        });

	});

 $("#checkall").click(function () {
  $('input:checkbox').not(this).prop('checked', this.checked);
});

function readURL(input) {

if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#ImdID').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
}
}

function readURLimg(input) {

if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#ImdID1').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
}
}

</script>

