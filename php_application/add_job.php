<?php $page_title="Add Job";
      

include("includes/header.php");

require("includes/function.php");
require("language/language.php");

require_once("thumbnail_images.class.php");

require_once("library/HTMLPurifier.auto.php");

$config1 = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config1);

//All Category
$cat_qry = "SELECT * FROM tbl_category ORDER BY category_name";
$cat_result = mysqli_query($mysqli, $cat_qry);

//All City
$city_qry = "SELECT * FROM tbl_city ORDER BY city_name";
$city_result = mysqli_query($mysqli, $city_qry);

if (isset($_POST['submit'])) {

	$flage=0;

	$job_mail = filter_var($_POST['job_mail'], FILTER_SANITIZE_EMAIL);
	if (!filter_var($job_mail, FILTER_VALIDATE_EMAIL) === false) {
		
	} else {
		$_SESSION['msg'] = "23";
		 header("Location:add_job.php");
		exit;
	}

	$job_phone_number = filter_var($_POST['job_phone_number'], FILTER_SANITIZE_NUMBER_INT);
	$phone_to_check = str_replace("-", "", $job_phone_number);

	if (strlen($phone_to_check) > 10 || strlen($phone_to_check) > 14) {
		
	} else {
		$_SESSION['msg'] = "24";
		 header("Location:add_job.php");
		exit;
	}
	
	
	  $job_image = rand(0, 99999) . "_" . $_FILES['job_image']['name'];

	  //Main Image
	  $tpath1 = 'images/' . $job_image;
	  $pic1 = compress_image($_FILES["job_image"]["tmp_name"], $tpath1, 80);

	  //Thumb Image 
	  $thumbpath = 'images/thumbs/' . $job_image;
	  $thumb_pic1 = create_thumb_image($tpath1, $thumbpath, '200', '200');

  $clean_job_desc = $purifier->purify($_POST['job_desc']);
  $data = array(
   		'cat_id'  => mysqli_real_escape_string($mysqli, $_POST['cat_id']),
		'city_id'  => mysqli_real_escape_string($mysqli, $_POST['city_id']),
		'job_name'  => filter_var($_POST['job_name'], FILTER_SANITIZE_STRING),
		'job_type'  => filter_var($_POST['job_type'], FILTER_SANITIZE_STRING),
		'job_designation'  => filter_var($_POST['job_designation'], FILTER_SANITIZE_STRING),
		'job_desc'  =>  addslashes($clean_job_desc),
		'job_salary'  =>  filter_var($_POST['job_salary'], FILTER_SANITIZE_STRING),
		'job_company_name'  => filter_var($_POST['job_company_name'], FILTER_SANITIZE_STRING),
		'job_company_website'  => filter_var($_POST['job_company_website'], FILTER_SANITIZE_STRING),
		'job_phone_number'  => $phone_to_check,
		'job_mail'  => $job_mail,
		'job_vacancy'  =>  filter_var($_POST['job_vacancy'],FILTER_SANITIZE_STRING),
		'job_address'  =>  filter_var($_POST['job_address'], FILTER_SANITIZE_STRING),
		'job_experince'  => filter_var($_POST['job_experince'], FILTER_SANITIZE_STRING),
		'job_qualification'  =>  filter_var($_POST['job_qualification'], FILTER_SANITIZE_STRING),
		'job_skill'  =>  filter_var($_POST['job_skill'], FILTER_SANITIZE_STRING),
		'job_image'  =>  $job_image,
		'job_work_day'  =>  filter_var($_POST['job_work_day'], FILTER_SANITIZE_STRING),
		'job_work_time'  =>  filter_var($_POST['job_work_time'], FILTER_SANITIZE_STRING),
		'job_date'  =>  strtotime($_POST['job_date'])
  );

  $qry = Insert('tbl_jobs', $data);

	
  $_SESSION['msg'] = "10";
  header("Location:manage_jobs.php");
  exit;
}

?>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Add Job</div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="card-body mrg_bottom">
        <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">

          <div class="section">
            <div class="section-body">
              <div class="form-group">
                <label class="col-md-3 control-label">Category :-</label>
                <div class="col-md-6">
                  <select name="cat_id" id="cat_id" class="select2" required>
                    <option value="">--Select Category--</option>
                    <?php
                    while ($cat_row = mysqli_fetch_array($cat_result)) {
                    ?>
                      <option value="<?php echo $cat_row['cid']; ?>"><?php echo htmlspecialchars($cat_row['category_name']); ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">City :-</label>
                <div class="col-md-6">
                  <select name="city_id" id="city_id" class="select2" required>
                    <option value="">--Select City--</option>
                    <?php
                    while ($city_row = mysqli_fetch_array($city_result)) {
                    ?>
                      <option value="<?php echo $city_row['c_id']; ?>"><?php echo htmlspecialchars($city_row['city_name']); ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Job Type:-</label>
                <div class="col-md-6">
                  <select name="job_type" id="job_type" style="width:280px; height:25px;" class="select2" required>
                    <option value="">--Select Type--</option>
                    <option value="Full Time">Full Time</option>
                    <option value="Half Time">Half Time</option>
                    <option value="Hourly">Hourly</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Job Title :-</label>
                <div class="col-md-6">
                  <input type="text" name="job_name" id="job_name" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Designation :-</label>
                <div class="col-md-6">
                  <input type="text" name="job_designation" id="job_designation" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Description :-</label>
                <div class="col-md-6">
                  <textarea name="job_desc" id="job_desc" class="form-control"></textarea>
                  <script>
                    CKEDITOR.replace('job_desc');
                  </script>
                </div>
              </div>
              <div class="form-group">&nbsp;</div>

              <div class="form-group">
                <label class="col-md-3 control-label">Salary :- <p class="control-label-help" id="square_lable_info">(10,0000)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_salary" id="job_salary" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Company Name :-</label>
                <div class="col-md-6">
                  <input type="text" name="job_company_name" id="job_company_name" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Website :- <p class="control-label-help" id="square_lable_info">(info@gmail.com)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_company_website" id="job_company_website" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Phone :- <p class="control-label-help" id="square_lable_info">(+91 1234569874)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_phone_number" id="job_phone_number" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Email :- <p class="control-label-help" id="square_lable_info">(demo@gmail.com)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_mail" id="job_mail" value="" class="form-control" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">JOB Work Day:- <p class="control-label-help" id="square_lable_info">(e.g.Mon-Fri)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_work_day" id="job_work_day" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">JOB Work Time:- <p class="control-label-help" id="square_lable_info">(e.g.9AM-6PM)</p></label>
                <div class="col-md-6">
                  <input type="text" name="job_work_time" id="job_work_time" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Vacancy :-</label>
                <div class="col-md-6">
                  <textarea name="job_vacancy" id="job_vacancy" class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Address :-</label>
                <div class="col-md-6">
                  <textarea name="job_address" id="job_address" class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Experience:- <p class="control-label-help" id="square_lable_info">(1 Years)</p></label>
                <div class="col-md-6">
                  <input name="job_experince" id="job_experince" class="form-control"></input>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Qualification :-</label>
                <div class="col-md-6">
                  <textarea name="job_qualification" id="job_qualification" class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Skill :- <p class="control-label-help" id="square_lable_info">(Seperate with Comma)</p></label>
                <div class="col-md-6">
                <input type="text" name="job_skill" id="job_skill" class="form-control" data-role="tagsinput">
                </div>
              </div>
              <br />
              <div class="form-group">
                <label class="col-md-3 control-label">Featured Image :-
                  <p class="control-label-help" id="square_lable_info">(Recommended Resolution:300x300, 400x400, 500x500, 600x600 or Square Image)</p>
                </label>
                <div class="col-md-6">
                  <div class="fileupload_block">
                    <input type="file" name="job_image" value="" id="fileupload" onchange="readURL(this)" required>
                    <div class="fileupload_img"><img type="image" src="assets/images/landscape.jpg" alt="Featured image" id="ImdID" /></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Date :-</label>
                <div class="col-md-6">
                  <input type="text" name="job_date" id="job_date" class="form-control datepicker" required autocomplete="off">
                </div>
              </div>

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
</div>

<?php include("includes/footer.php"); ?>

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
</script>