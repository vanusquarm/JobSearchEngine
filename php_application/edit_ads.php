<?php include("includes/header.php");

require("includes/function.php");
require("language/language.php");

require_once("thumbnail_images.class.php");

if (isset($_GET['ads_id'])) {

  $qry = "SELECT * FROM tbl_advertisements WHERE `id`='" . $_GET['ads_id'] . "'";
  $result = mysqli_query($mysqli, $qry);
  $row = mysqli_fetch_assoc($result);
}
if (isset($_POST['submit']) and isset($_POST['ads_id'])) {
	
  $data = array(

    'ad_title'  => filter_var($_POST['ad_title'], FILTER_SANITIZE_STRING),
    'ad_code'  =>  $_POST['ad_code'],
    'status'  =>  $_POST['status']
  );

  $edit = Update('tbl_advertisements', $data, "WHERE `id` = '" . $_POST['ads_id'] . "'");

  $_SESSION['msg'] = "11";
  header("Location:edit_ads.php?ads_id=" . $_POST['ads_id']);
  exit;
}


?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?php if (isset($_GET['ads_id'])) { ?>Edit<?php } else { ?><?php } ?> Ads Management</div>
        </div>
        <div class="col-sm-6" align="left" style="float: right;width:11%;margin-top:28px;">
          <a href="manage_ads_management.php">
            <h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;color:#e91e63;"><i class="fa fa-arrow-left"></i> Back</h4>
          </a>
        </div>
      </div>
      <div class="clearfix"></div>
    
      <div class="card-body mrg_bottom">
        <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">
          <input type="hidden" name="ads_id" value="<?php echo $_GET['ads_id']; ?>" />

          <div class="section">
            <div class="section-body">
              <div class="form-group">
                <label class="col-md-3 control-label">Ad Title:-</label>
                <div class="col-md-6">
                  <input type="text" name="ad_title" id="ad_title" value="<?php echo $row['ad_title']; ?>" class="form-control" readonly></input>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Ad Code:- <p class="control-label-help" id="square_lable_info">(Note:Paste here code)</p></label>
                <div class="col-md-6">
                  <textarea type="text" name="ad_code" id="ad_code" class="form-control"><?php echo stripslashes($row['ad_code']); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Status :-</label>
                <div class="col-md-6">
                  <select class="select2" name="status" id="status">
                    <option value="1" <?php if ($row['status'] == '1') { ?>selected<?php } ?>>Active</option>
                    <option value="0" <?php if ($row['status'] == '0') { ?>selected<?php } ?>>Inactive</option>
                  </select>
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