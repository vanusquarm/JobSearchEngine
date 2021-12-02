<?php $page_title=(isset($_GET['user_id'])) ? 'Edit User' : 'Add User'; 

include('includes/header.php');

include('includes/function.php');
include('language/language.php');

require_once("thumbnail_images.class.php");

error_reporting(0);

$register_date = strtotime(date('d-m-Y h:i A'));

if (isset($_POST['submit']) and isset($_GET['add'])) {

	$flage=0;

	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		
	} else {
		$_SESSION['msg'] = "23";
		header("Location:manage_users.php");
		exit;
	}

	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
	$phone_to_check = str_replace("-", "", $phone);

	if (strlen($phone_to_check) > 10 || strlen($phone_to_check) > 14) {
		
	} else {
		$_SESSION['msg'] = "24";
		header("Location:manage_users.php");
		exit;
	}
	
	$sql="SELECT * FROM tbl_users WHERE  email='".$_POST['email']."'";
	$res=mysqli_query($mysqli,$sql);
	if (mysqli_num_rows($res) > 0) {

		$_SESSION['msg']='42';
		
	}else{
		if ($_FILES['user_resume']['name'] != '') {

			$user_resume = rand(0, 99999) . "_" . $_FILES['user_resume']['name'];

    		//Main Image
			$tpath1 = 'uploads/' . $user_resume;
			move_uploaded_file($_FILES["user_resume"]["tmp_name"], "uploads/" . $user_resume);
		} else {
			$user_resume = '';
		}

		if ($_FILES['user_image']['name'] != "") {

			$ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);

			$user_image = rand(0, 99999) . '_' . date('dmYhis') . "_user." . $ext;

    		//Main Image
			$tpath1 = 'images/' . $user_image;

			if ($ext != 'png') {
				$pic1 = compress_image($_FILES["user_image"]["tmp_name"], $tpath1, 80);
			} else {
				$tmp = $_FILES['user_image']['tmp_name'];
				move_uploaded_file($tmp, $tpath1);
			}
			
			$data = array(
				'user_type' => $_POST['user_type'],
				'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
				'email' => $email,
				'password' => md5(trim($_POST['password'])),
				'phone' => $phone_to_check,
				'city'  =>  filter_var($_POST['city'], FILTER_SANITIZE_STRING),
				'address'  =>  filter_var($_POST['address'], FILTER_SANITIZE_STRING),
				'user_image' => $user_image,
				'user_resume' => $user_resume,
				'register_date'  =>  $register_date,
				'current_company_name' => filter_var($_POST['current_company_name'], FILTER_SANITIZE_STRING),
				'experiences'  => filter_var($_POST['experiences'], FILTER_SANITIZE_STRING),
				'gender'  =>  filter_var($_POST['gender'], FILTER_SANITIZE_STRING),
				'date_of_birth'  =>  strtotime($_POST['date_of_birth']),
				'skills'  =>  filter_var($_POST['skills'], FILTER_SANITIZE_STRING)
			);

		} else {

			$data = array(
				'user_type' => $_POST['user_type'],
				'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
				'email' => $email,
				'password' => md5(trim($_POST['password'])),
				'phone' => $phone_to_check,
				'city'  =>  filter_var($_POST['city'], FILTER_SANITIZE_STRING),
				'address'  =>  filter_var($_POST['address'], FILTER_SANITIZE_STRING),
				'user_resume' => $user_resume,
				'register_date'  =>  $register_date,
				'current_company_name' => filter_var($_POST['current_company_name'], FILTER_SANITIZE_STRING),
				'experiences'  => filter_var($_POST['experiences'], FILTER_SANITIZE_STRING),
				'gender'  =>  filter_var($_POST['gender'], FILTER_SANITIZE_STRING),
				'date_of_birth'  =>  strtotime($_POST['date_of_birth']),
				'skills'  =>  filter_var($_POST['skills'], FILTER_SANITIZE_STRING)
			);
		}


		$qry = Insert('tbl_users', $data);

		$last_id = mysqli_insert_id($mysqli);

		if ($_POST['register_as'] == 'company') {

			$ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);

			$company_logo = rand(0, 99999) . "." . $ext;

    		//Main Image
			$tpath = 'images/' . $company_logo;

			if ($ext != 'png') {
				$pic1 = compress_image($_FILES["company_logo"]["tmp_name"], $tpath, 80);
			} else {
				$tmp = $_FILES['company_logo']['tmp_name'];
				move_uploaded_file($tmp, $tpath);
			}

			$company_email = filter_var($_POST['company_email'], FILTER_SANITIZE_EMAIL);

			if (!filter_var($company_email, FILTER_VALIDATE_EMAIL) === false) {
				
			} else {
				$_SESSION['msg'] = "23";
				header("location:manage_users.php");
				exit;
			}

			$mobile_no = filter_var($_POST['mobile_no'], FILTER_SANITIZE_NUMBER_INT);
			$phone_to_company = str_replace("-", "", $mobile_no);

			if (strlen($phone_to_company) > 10 || strlen($phone_to_company) > 14) {
				
			} else {
				$_SESSION['msg'] = "24";
				header("location:manage_users.php");
				exit;
			}

			$data1 = array(
				'user_id' => $last_id,
				'company_name'  =>  filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),
				'company_email'  =>  $company_email,
				'mobile_no'  =>  $phone_to_company,
				'company_address'=>filter_var($_POST['company_address'], FILTER_SANITIZE_STRING),
				'company_desc'  => filter_var($_POST['company_desc'], FILTER_SANITIZE_STRING),
				'company_work_day'=>filter_var($_POST['company_work_day'], FILTER_SANITIZE_STRING),
				'company_work_time'=>filter_var($_POST['company_work_time'], FILTER_SANITIZE_STRING),
				'company_website'  => filter_var($_POST['company_website'], FILTER_SANITIZE_STRING),
				'company_logo' => $company_logo
			);

			$qry1 = Insert('tbl_company', $data1);

			
		}
	}
	
    $_SESSION['msg'] = "10";
	header("location:manage_users.php");
	exit;
}

if (isset($_GET['user_id'])) {

	$user_qry = "SELECT * FROM tbl_users WHERE id='" . $_GET['user_id'] . "'";
	$user_result = mysqli_query($mysqli, $user_qry);
	$user_row = mysqli_fetch_assoc($user_result);

  //All Company
	$company_qry = "SELECT * FROM tbl_company  WHERE `user_id` ='" . $_GET['user_id'] . "'";
	$company_result = mysqli_query($mysqli, $company_qry);
	$company_row = mysqli_fetch_assoc($company_result);
}

if (isset($_POST['submit']) and isset($_POST['user_id'])) {

	$flage=0;

	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		
	} else {
		$_SESSION['msg'] = "23";
		header("Location:add_user.php?user_id=" . $_POST['user_id']);
		exit;
	}

	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
	$phone_to_check = str_replace("-", "", $phone);

	if (strlen($phone_to_check) > 10 || strlen($phone_to_check) > 14) {
		
	} else {
		$_SESSION['msg'] = "24";
		header("Location:add_user.php?user_id=" . $_POST['user_id']);
		exit;
	}
	
	
	if ($_FILES['user_resume']['name'] != '') {

		$user_resume = rand(0, 99999) . "_" . $_FILES['user_resume']['name'];

   		 //Main Image
		$tpath1 = 'uploads/' . $user_resume;
		move_uploaded_file(
			$_FILES["user_resume"]["tmp_name"],
			"uploads/" . $user_resume
		);
	} else {
		$user_resume = $_POST['old_user_resume'];
	}

	if ($_FILES['user_image']['name'] != "") {

		$img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_users WHERE id=' . $_GET['user_id'] . '');
		$img_res_row = mysqli_fetch_assoc($img_res);


		if ($img_res_row['user_image'] != "") {
			unlink('images/thumbs/' . $img_res_row['user_image']);
			unlink('images/' . $img_res_row['user_image']);
		}
		$user_image = rand(0, 99999) . "_" . $_FILES['user_image']['name'];

	    //Main Image
			$tpath1 = 'images/' . $user_image;
			$pic1 = compress_image($_FILES["user_image"]["tmp_name"], $tpath1, 80);
		} else {
			$user_image = $_POST['old_user_image'];
		}


		$data = array(
				'user_type' => $_POST['user_type'],
				'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
				'email' => $email,
				'password' => md5(trim($_POST['password'])),
				'phone' => $phone_to_check,
				'city'  =>  filter_var($_POST['city'], FILTER_SANITIZE_STRING),
				'address'  =>  filter_var($_POST['address'], FILTER_SANITIZE_STRING),
				'user_image' => $user_image,
				'user_resume' => $user_resume,
				'current_company_name' => filter_var($_POST['current_company_name'], FILTER_SANITIZE_STRING),
				'experiences'  => filter_var($_POST['experiences'], FILTER_SANITIZE_STRING),
				'gender'  =>  filter_var($_POST['gender'], FILTER_SANITIZE_STRING),
				'date_of_birth'  =>  strtotime($_POST['date_of_birth']),
				'skills'  =>  filter_var($_POST['skills'], FILTER_SANITIZE_STRING)

		);
	}


	$user_edit = Update('tbl_users', $data, "WHERE id = '" . $_POST['user_id'] . "'");

	if ($_FILES['company_logo']['name'] != "") {

		$img_res_company = mysqli_query($mysqli, 'SELECT * FROM tbl_company WHERE `id`=' . $_GET['id'] . '');
		$img_res_row_company = mysqli_fetch_assoc($img_res_company);

		if ($img_res_row_company['company_logo'] != "") {
			unlink('admin/images/' . $img_res_row_company['company_logo']);
		}

		$ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);

		$company_logo = rand(0, 99999) . "." . $ext;

    //Main Image
		$tpath = 'admin/images/' . $company_logo;

		if ($ext != 'png') {
			$pic1 = compress_image($_FILES["company_logo"]["tmp_name"], $tpath, 80);
		} else {
			$tmp = $_FILES['company_logo']['tmp_name'];
			move_uploaded_file($tmp, $tpath);
		}

			$company_email = filter_var($_POST['company_email'], FILTER_SANITIZE_EMAIL);
			if (!filter_var($company_email, FILTER_VALIDATE_EMAIL) === false) {
				
			} else {
				$_SESSION['msg'] = "23";
				header("Location:add_user.php?user_id=" . $_POST['user_id']);
				exit;
			}

			$mobile_no = filter_var($_POST['mobile_no'], FILTER_SANITIZE_NUMBER_INT);
			$phone_to_company = str_replace("-", "", $mobile_no);

			if (strlen($phone_to_company) > 10 || strlen($phone_to_company) > 14) {
				
			} else {
				$_SESSION['msg'] = "24";
				header("Location:add_user.php?user_id=" . $_POST['user_id']);
				exit;
			}

		$data1 = array(
				'company_name'  =>  filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),
				'company_email'  =>  $company_email,
				'mobile_no'  =>  $phone_to_company,
				'company_address'=>filter_var($_POST['company_address'], FILTER_SANITIZE_STRING),
				'company_desc'  => filter_var($_POST['company_desc'], FILTER_SANITIZE_STRING),
				'company_work_day'=>filter_var($_POST['company_work_day'], FILTER_SANITIZE_STRING),
				'company_work_time'=>filter_var($_POST['company_work_time'], FILTER_SANITIZE_STRING),
				'company_website'  => filter_var($_POST['company_website'], FILTER_SANITIZE_STRING),
				'company_logo' => $company_logo

		);
	} else {

		$data1 = array(
				'company_name'  =>  filter_var($_POST['company_name'], FILTER_SANITIZE_STRING),
				'company_email'  =>  $company_email,
				'mobile_no'  =>  $phone_to_company,
				'company_address'=>filter_var($_POST['company_address'], FILTER_SANITIZE_STRING),
				'company_desc'  => filter_var($_POST['company_desc'], FILTER_SANITIZE_STRING),
				'company_work_day'=>filter_var($_POST['company_work_day'], FILTER_SANITIZE_STRING),
				'company_work_time'=>filter_var($_POST['company_work_time'], FILTER_SANITIZE_STRING),
				'company_website'  => filter_var($_POST['company_website'], FILTER_SANITIZE_STRING)

		);
	}

	$company_edit = Update('tbl_company', $data1, " WHERE `user_id` = '" . $_POST['user_id'] . "'");

	if ($user_edit > 0) {
		
		$_SESSION['msg'] = "11";
		header("Location:add_user.php?user_id=" . $_POST['user_id']);
		exit;
	
}

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(e) {
		$("#user_type").change(function() {

			var type = $("#user_type").val();

			if (type == "1") {

				$("#book_local_display").show();
				$("#local_display").hide();
				$("#skill_display").show();
				$("#experi_display").show();
				$("#company_display").show();


			} else {
				$("#book_local_display").hide();
				$("#local_display").show();
				$("#skill_display").hide();
				$("#experi_display").hide();
				$("#company_display").hide();

			}

		});
	});
	$(document).ready(function(e) {
		$("#user_type").change(function() {

			var type = $("#user_type").val();

			if (type == "1") {

				$("#date_diplay").show();
				$("#gender_display").show();



			} else {
				$("#date_diplay").hide();
				$("#gender_display").hide();


			}

		});
	});
</script>
<style>
	/* The container1 */
	.container1 {
		display: block;
		position: relative;
		padding-left: 35px;
		margin-bottom: 12px;
		cursor: pointer;
		font-size: 15px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	/* Hide the browser's default radio button */
	.container1 input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
	}

	/* Create a custom radio button */
	.checkmark {
		position: absolute;
		top: 0;
		left: 0;
		height: 25px;
		width: 25px;
		background-color: #eee;
		border-radius: 50%;
	}

	.select_provider_area_item .container1 {
		line-height: 28px;
	}

	.select_provider_area_item {
		background: #f9f9f9;
		display: inline-block;
		width: 320px;
		padding: 20px 12px 6px 12px;
		border-radius: 4px;
		border: 2px solid rgba(0, 0, 0, 0.1);
		margin-left: 275px;
	}

	/* On mouse-over, add a grey background color */
	.container1:hover input~.checkmark {
		background-color: #ccc;
	}

	/* When the radio button is checked, add a blue background */
	.container1 input:checked~.checkmark {
		background-color: #2196F3;
	}

	/* Create the indicator (the dot/circle - hidden when not checked) */
	.checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	/* Show the indicator (dot/circle) when checked */
	.container1 input:checked~.checkmark:after {
		display: block;
	}

	/* Style the indicator (dot/circle) */
	.container1 .checkmark:after {
		top: 9px;
		left: 9px;
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: white;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="page_title_block">
				<div class="col-md-5 col-xs-12">
					<div class="page_title"><?php if (isset($_GET['user_id'])) { ?>Edit<?php } else { ?>Add<?php } ?> User</div>
				</div>
				<div class="col-sm-6" align="left" style="float: right;width:11%;margin-top:28px;">
					<a href="manage_users.php">
						<h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;color:#e91e63;"><i class="fa fa-arrow-left"></i> Back</h4>
					</a>
				</div>
			</div>
			<div class="clearfix"></div>
				<div class="card-body mrg_bottom">
					<form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data">
						<input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>" />
						<input type="hidden" name="user_type" id="input" value="1">

						<div class="section">
							<div class="section-body">
								<div class="form-group">
									<label class="col-md-3 control-label">User Type :-</label>
									<div class="col-md-6">
										<select name="user_type" id="user_type" class="select2">
											<option value="1" <?php if (isset($_GET['user_id']) && $user_row['user_type'] == "1") { ?>selected<?php } ?>>Job Seeker</option>
											<option value="2" <?php if (isset($_GET['user_id']) && $user_row['user_type'] == "2") { ?>selected<?php } ?>>Job Provider</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Name :-</label>
									<div class="col-md-6">
										<input type="text" name="name" id="name" value="<?php if (isset($_GET['user_id'])) {echo $user_row['name'];
										} ?>" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Email :-</label>
									<div class="col-md-6">
										<input type="email" name="email" id="email" value="<?php if (isset($_GET['user_id'])) {echo $user_row['email'];
										} ?>" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Password :-</label>
									<div class="col-md-6">
										<input type="password" name="password" id="password" value="" class="form-control" <?php if (!isset($_GET['user_id'])) { ?>required<?php } ?>>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Phone :-  <p class="control-label-help" id="square_lable_info">(+91 1234569874)</p></label>
									<div class="col-md-6">
										<input type="text" name="phone" id="phone" value="<?php if (isset($_GET['user_id'])) {echo $user_row['phone'];
										} ?>" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">City :-</label>
									<div class="col-md-6">
										<input type="text" name="city" id="city" value="<?php if (isset($_GET['user_id'])) {
											echo $user_row['city'];
										} ?>" class="form-control" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Address :-</label>
									<div class="col-md-6">
										<input type="text" name="address" id="address" value="<?php if (isset($_GET['user_id'])) {
											echo stripslashes($user_row['address']);
										} ?>" class="form-control">
									</div>
								</div>
								<div class="form-group" id="company_display" <?php if ($user_row['user_type'] == '2') { ?>style="display:none;" <?php } else { ?>style="display:block;" <?php } ?>>
									<label class="col-md-3 control-label">Current Company Name :-</label>
									<div class="col-md-6">
										<input type="text" name="current_company_name" id="current_company_name" value="<?php if (isset($_GET['user_id'])) {
											echo stripslashes($user_row['current_company_name']);
										} ?>" class="form-control">
									</div>
								</div>
								<div class="form-group" id="experi_display" <?php if ($user_row['user_type'] == '2') { ?>style="display:none;" <?php } else { ?>style="display:block;" <?php } ?>>
									<label class="col-md-3 control-label">Experiences :-</label>
									<div class="col-md-6">
										<input type="text" name="experiences" id="experiences" value="<?php if (isset($_GET['user_id'])) {
											echo stripslashes($user_row['experiences']);
										} ?>" class="form-control">
									</div>
								</div>
								<div class="form-group" id="skill_display" <?php if ($user_row['user_type'] == '2') { ?>style="display:none;" <?php } else { ?>style="display:block;" <?php } ?>>
									<label class="col-md-3 control-label">skills :-</label>
									<div class="col-md-6">
										<input type="text" name="skills" id="skills" value="<?php if (isset($_GET['user_id'])) {
											echo $user_row['skills'];
										} ?>" class="form-control" data-role="tagsinput">
									</div>
								</div>
								<br>

								<div class="form-group">
									<label class="col-md-3 control-label">Gender :-</label>
									<div class="col-md-6">
										<select name="gender" id="gender" class="select2" required>
											<option value="">select Gender</option>
											<option value="Male" <?php if (isset($user_row['id']) && $user_row['gender'] == "Male") { ?>selected<?php } ?>>Male</option>
											<option value="Female" <?php if (isset($user_row['id']) && $user_row['gender'] == "Female") { ?>selected<?php } ?>>Female</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Date of birth :-</label>
									<div class="col-md-6">
										<input type="text" name="date_of_birth" id="date_of_birth" value="<?php if (isset($user_row['id']) && $user_row['date_of_birth'] != NULL) {
											echo date('d-m-Y', $user_row['date_of_birth']);
										} ?>" placeholder="DD/MM/YY" class="form-control datepicker" required autocomplete="off">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">User Image :-</label>
								<div class="col-md-6">
									<div class="fileupload_block">
										<input type="file" name="user_image" value="" id="fileupload" onchange="readURL(this);">
										<?php if (isset($_GET['user_id']) and $user_row['user_image'] != "") { ?>
											<input type="hidden" name="old_user_image" value="<?php echo $user_row['user_image']; ?>" id="fileupload">
											<div class="fileupload_img"><img type="image" src="images/<?php echo $user_row['user_image']; ?>" alt=" image" style="width:90px;" id="ImdID" /></div>
										<?php } else { ?>
											<div class="fileupload_img"><img type="image" src="assets/images/landscape.jpg" alt="category image" id="ImdID" /></div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="book_local_display" class="form-group" <?php if ($user_row['user_type'] == '2') { ?>style="display:none;" <?php } else { ?>style="display:block;" <?php } ?>>
								<label class="col-md-3 control-label">User Resume :-</label>
								<div class="col-md-6">
									<div class="fileupload_block">
										<input type="file" name="user_resume" value="" id="fileupload">
										<?php if (isset($_GET['user_id']) and $user_row['user_resume'] != "") { ?>
											<input type="hidden" name="old_user_resume" value="<?php echo $user_row['user_resume']; ?>" id="fileupload">
											<a href="uploads/<?php echo $user_row['user_resume']; ?>" class="btn btn-success btn-sm" target="_blank"><i class="icon fa fa-cloud-download" aria-hidden="true"></i></a>
										<?php } ?>
									</div>
								</div>
							</div>
								
							<div id="select_provider" <?php if ($user_row['user_type'] == '2') { ?>style="display:block;" <?php } else { ?>style="display:none;" <?php } ?>>
								<div class="select_provider_area_item">
									<div class="col-md-6">
										<div class="form-group">
											<label class="container1">Individual
												<input type="radio" name="register_as" value="individual" checked>
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="container1">Company
												<input type="radio" name="register_as" value="company">
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
								</div>
								<div class="company_detail" style="display:none">
									<hr>
									<h4>Company Details</h4>
									<hr>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Name :-</label>
										<div class="col-md-6">
											<input type="text" name="company_name" id="company_name" value="<?php if (isset($_GET['user_id'])) {
												echo stripslashes($company_row['company_name']);
											} ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Email :-</label>
										<div class="col-md-6">
											<input type="text" name="company_email" id="company_email" value="<?php echo 'Only Admin Can See'; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Mobile No :-  <p class="control-label-help" id="square_lable_info">(+91 1234569874)</p></label>
										<div class="col-md-6">
											<input type="text" name="mobile_no" id="mobile_no" value="<?php echo 'Only Admin Can See'; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Address :-</label>
										<div class="col-md-6">
											<input type="text" name="company_address" id="company_address" value="<?php if (isset($_GET['user_id'])) {
												echo stripslashes($company_row['company_address']);
											} ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Website :-</label>
										<div class="col-md-6">
											<input type="text" name="company_website" id="company_website" value="<?php if (isset($_GET['user_id'])) {
												echo stripslashes($company_row['company_website']);
											} ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Work Day :-</label>
										<div class="col-md-6">
											<input type="text" name="company_work_day" id="company_work_day" value="<?php if (isset($_GET['user_id'])) {
												echo stripslashes($company_row['company_work_day']);
											} ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Work Time :-</label>
										<div class="col-md-6">
											<input type="text" name="company_work_time" id="company_work_time" value="<?php if (isset($_GET['user_id'])) {
												echo stripslashes($company_row['company_work_time']);
											} ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Company Logo :-</label>
										<div class="col-md-6">
											<div class="fileupload_block">
												<input type="file" name="company_logo" value="" id="fileupload" onchange="readURLimg(this);">
												<?php if (isset($_GET['user_id']) and $company_row['company_logo'] != "") { ?>
													<input type="hidden" name="old_user_image" value="<?php echo $company_row['company_logo']; ?>" id="fileupload">
													<div class="fileupload_img"><img type="image" src="images/<?php echo $company_row['company_logo']; ?>" alt=" image" style="width:90px;" id="ImdID1" /></div>
												<?php } else { ?>
													<div class="fileupload_img"><img type="image" src="assets/images/landscape.jpg" alt="category image" id="ImdID1" /></div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Description :-</label>
										<div class="col-md-6">
											<textarea name="company_desc" id="company_desc" class="form-control"><?php echo stripslashes($company_row['company_desc']); ?></textarea>
											<script>
												CKEDITOR.replace('company_desc');
											</script>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="form-group">
								<div class="col-md-9 col-md-offset-3">
									<button type="submit" name="submit" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


	<?php include('includes/footer.php'); ?>

	<script type="text/javascript">

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#ImdID').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$(function() {
			$('input[name="register_as"]').change(function() {

				if ($(this).val() == "company") {
					$(".company_detail").show();
					$('.company_detail input').each(function() {
						$(this).attr('required', true)
					});

				} else {
					$(".company_detail").hide();
					$('.company_detail input').each(function() {
						$(this).attr('required', false)
					});
				}
			});

		});

		$(document).ready(function(e) {

			$("#user_type").change(function() {

				var type = $("#user_type").val();
				if (type == "2") {
					$("#select_provider").show();
				} else {
					$("#select_provider").hide();
				}

			});
		});

		function readURLimg(input) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#ImdID1').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>