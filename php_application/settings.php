<?php  $page_title="Settings";

include("includes/header.php");

require("includes/function.php");
require("language/language.php");

require_once("library/HTMLPurifier.auto.php");

$config1 = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config1);

$qry = "SELECT * FROM tbl_settings WHERE id='1'";
$result = mysqli_query($mysqli, $qry);
$settings_row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
	
  $img_res = mysqli_query($mysqli, "SELECT * FROM tbl_settings WHERE id='1'");
  $img_row = mysqli_fetch_assoc($img_res);

  if ($_FILES['app_logo']['name'] != "") {

    unlink('images/' . $img_row['app_logo']);

    $app_logo = $_FILES['app_logo']['name'];
    $pic1 = $_FILES['app_logo']['tmp_name'];

    $tpath1 = 'images/' . $app_logo;
    copy($pic1, $tpath1);


    $data = array(
      'app_name'  =>  cleanInput($_POST['app_name']),
      'app_logo'  =>  $app_logo,
      'app_description'  => addslashes($_POST['app_description']),
      'app_version'  => cleanInput($_POST['app_version']),
      'app_author'  =>  cleanInput($_POST['app_author']),
      'app_contact'  =>  cleanInput($_POST['app_author']),
      'app_email'  =>  cleanInput($_POST['app_author']),
      'app_website'  => cleanInput($_POST['app_website']),
      'app_developed_by'  => cleanInput($_POST['app_developed_by'])

    );
  } else {
	$clean_app_description = $purifier->purify($_POST['app_description']);
    $data = array(
      'app_name'  =>  cleanInput($_POST['app_name']),
      'app_description'  => addslashes($clean_app_description),
      'app_version'  =>  cleanInput($_POST['app_version']),
      'app_author'  =>  cleanInput($_POST['app_author']),
      'app_contact'  =>  cleanInput($_POST['app_author']),
      'app_email'  =>  cleanInput($_POST['app_author']),
      'app_website'  => cleanInput($_POST['app_website']),
      'app_developed_by'  =>cleanInput($_POST['app_developed_by'])

    );
  }

  $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");


  $_SESSION['msg'] = "11";
  header("Location:settings.php");
  exit;
}

if (isset($_POST['admob_submit'])) {

  $data = array(

    'publisher_id'  =>  cleanInput($_POST['publisher_id']),
    'interstital_ad'  => ($_POST['interstital_ad']) ? 'true' : 'false',
    'interstital_ad_id'  =>  cleanInput($_POST['interstital_ad_id']),
    'interstital_ad_click'  =>  cleanInput($_POST['interstital_ad_click']),
    'interstital_facebook_id'  =>  cleanInput($_POST['interstital_facebook_id']),
    'interstital_ad_type'  =>  cleanInput($_POST['interstital_ad_type']),
    'banner_ad'  => ($_POST['banner_ad']) ? 'true' : 'false',
    'banner_ad_id'  =>  cleanInput($_POST['banner_ad_id']),
    'banner_ad_type'  =>  cleanInput($_POST['banner_ad_type']),
    'banner_facebook_id'  => cleanInput($_POST['banner_facebook_id']),

    'publisher_id_ios'  =>  cleanInput($_POST['publisher_id_ios']),
    'app_id_ios'  => cleanInput($_POST['app_id_ios']),
    'interstital_ad_ios'  => cleanInput($_POST['interstital_ad_ios']),
    'interstital_ad_id_ios'  => cleanInput($_POST['interstital_ad_id_ios']),
    'interstital_ad_click_ios'  => cleanInput($_POST['interstital_ad_click_ios']),
    'banner_ad_ios'  => cleanInput($_POST['banner_ad_ios']),
    'banner_ad_id_ios'  => cleanInput($_POST['banner_ad_id_ios']),
    'facebook_banner_ad'  => cleanInput($_POST['facebook_banner_ad']),
    'facebook_banner_ad_id'  => cleanInput($_POST['facebook_banner_ad_id']),
    'facebook_interstital_ad'  => cleanInput($_POST['facebook_interstital_ad']),
    'facebook_interstital_ad_id'  => cleanInput($_POST['facebook_interstital_ad_id']),
    'facebook_interstital_ad_click'  => cleanInput($_POST['facebook_interstital_ad_click'])

  );

  $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");


  $_SESSION['msg'] = "11";
  header("Location:settings.php");
  exit;
}

if (isset($_POST['api_submit'])) {
	
  $data = array(
    'api_home_limit'  => cleanInput($_POST['api_home_limit']),
    'api_latest_limit'  =>  cleanInput($_POST['api_latest_limit']),
    'api_cat_order_by'  => cleanInput($_POST['api_cat_order_by']),
    'api_page_limit'  =>  cleanInput($_POST['api_page_limit']),
    'api_cat_post_order_by'  => cleanInput($_POST['api_cat_post_order_by'])
  );

  $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");
	
  $_SESSION['msg'] = "11";
  header("Location:settings.php");
  exit;
}

if (isset($_POST['app_update'])) {
	
  $data = array(
    'cancel_status' => $_POST['cancel_status'] ? 'true' : 'false',
    'update_status' => $_POST['update_status'] ? 'true' : 'false',
    'app_update_desc'  =>  trim($_POST['app_update_desc']),
    'new_app_version'  =>  trim($_POST['new_app_version']),
    'app_link'  =>  trim($_POST['app_link'])
  );


  $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");
	
  $_SESSION['msg'] = "11";
  header("Location:settings.php");
  exit;
}


if (isset($_POST['app_pri_poly'])) {

  $clean_app_privacy_policy = $purifier->purify($_POST['app_privacy_policy']);
  $data = array(
    'app_privacy_policy'  => addslashes($clean_app_privacy_policy)
  );

  $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");
	
  $_SESSION['msg'] = "11";
  header("Location:settings.php");
  exit;
}

?>
<style type="text/css">
  .field_lable {
    margin-bottom: 10px;
    margin-top: 10px;
    color: #666;
    padding-left: 15px;
    font-size: 14px;
    line-height: 24px;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Settings</div>
        </div>
    	</div>
        <div class="clearfix"></div>
        <div class="card-body mrg_bottom" style="padding: 15px">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">App Settings</a></li>
            <li role="presentation"><a href="#admob_settings" aria-controls="admob_settings" role="tab" data-toggle="tab">Ads Settings</a></li>
            <li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API Settings</a></li>
            <li role="presentation"><a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab">App Privacy Policy</a></li>
            <li role="presentation"><a href="#app_update" aria-controls="app_update" role="tab" data-toggle="tab">App Update</a></li>
          </ul>

          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="app_settings">
              <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
                <div class="section">
                  <div class="section-body">

                    <?php if($settings_details['ios_envato_purchased_status'] == '1'){?>
                      <div class="checkbox" style="margin-bottom :30px;">
                        <input type="checkbox" name="checkbox" id="checkbox_app_settings" class="btn_import_a" data-type="app_settings_ios">
                        <label for="checkbox_app_settings">
                          Import From iOs App Settings
                        </label>
                      </div>
                      <br/>
                    <?php }?>
                    
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Name :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Logo :-</label>
                      <div class="col-md-6">
                        <div class="fileupload_block">
                          <input type="file" name="app_logo" id="fileupload" onchange="readURL(this);">

                          <?php if ($settings_row['app_logo'] != "") { ?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['app_logo']; ?>" alt="image" id="ImdID" /></div>
                          <?php } else { ?>
                            <div class="fileupload_img"><img type="image" src="assets/images/landscape.jpg" alt="image" id="ImdID" /></div>
                          <?php } ?>

                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Description :-</label>
                      <div class="col-md-6">

                        <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description']; ?></textarea>
                        <script>
                          CKEDITOR.replace('app_description');
                        </script>
                      </div>
                    </div>
                    <div class="form-group">&nbsp;</div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Version :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Author :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Contact :- <p class="control-label-help" id="square_lable_info">(+911236547895)</p></label>
                      <div class="col-md-6">
                        <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Email :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Website :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Developed By :-</label>
                      <div class="col-md-6">
                        <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by']; ?>" class="form-control">
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

             <!-- admob settings -->
          <div role="tabpanel" class="tab-pane" id="admob_settings">   
            <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                 <div class="section-body">            
                    <div class="row">
                      <div class="form-group">
                        <div class="col-md-6">                
                          <div class="col-md-12">
                            <div class="admob_title">Android</div>
                            <div class="form-group">
                              <label class="col-md-3 control-label">Publisher ID :-</label>
                              <div class="col-md-9">
                                <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id'];?>" class="form-control">
                              </div>
                              <div style="height:60px;display:inline-block;position:relative"></div>
                            </div>
                            <div class="banner_ads_block">
                              <div class="banner_ad_item">
                                <label class="control-label">Banner Ads :-</label>
                                <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                  <input type="checkbox" id="chk_banner" name="banner_ad" value="true" class="cbx hidden" <?=($settings_row['banner_ad']=='true') ? 'checked=""' : '' ?>>
                                  <label for="chk_banner" class="lbl"></label>
                                </div>                               
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <p class="field_lable">Banner Ad Type :-</p>
                                  <div class="col-md-12">
                                   <select name="banner_ad_type" id="banner_ad_type" class="select2">
                                      <option value="admob" <?php if($settings_row['banner_ad_type']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                      <option value="facebook" <?php if($settings_row['banner_ad_type']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <p class="field_lable">Banner ID :-</p>

                                  <div class="col-md-12 banner_ad_id" style="display: none">
                                    <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id'];?>" class="form-control">
                                  </div>
                                  <div class="col-md-12 banner_facebook_id" style="display: none">
                                    <input type="text" name="banner_facebook_id" id="banner_facebook_id" value="<?php echo $settings_row['banner_facebook_id'];?>" class="form-control">
                                  </div>

                                </div>                    
                              </div>
                            </div>  
                          </div>
                          <div class="col-md-12">
                            <div class="interstital_ads_block">
                              <div class="interstital_ad_item">
                                <label class="control-label">Interstitial Ads :-</label>
                                <div class="row toggle_btn" style="position: relative;margin-top: -8px;">
                                  <input type="checkbox" id="chk_interstitial" name="interstital_ad" value="true" class="cbx hidden" <?php if($settings_row['interstital_ad']=='true'){?>checked <?php }?>/>
                                  <label for="chk_interstitial" class="lbl"></label>
                                </div>                  
                              </div>  
                              <div class="col-md-12"> 
                                <div class="form-group">
                                  <p class="field_lable">Interstitial Ad Type :-</p>
                                  <div class="col-md-12"> 
                                    <select name="interstital_ad_type" id="interstital_ad_type" class="select2">
                                      <option value="admob" <?php if($settings_row['interstital_ad_type']=='admob'){ echo 'selected="selected"'; }?>>Admob</option>
                                      <option value="facebook" <?php if($settings_row['interstital_ad_type']=='facebook'){ echo 'selected="selected"'; }?>>Facebook</option>
                      
                                    </select>                                 
                                  </div>
                                </div>
                                <div class="form-group">
                                  <p class="field_lable">Interstitial Ad ID :-</p>
                                  <div class="col-md-12 interstital_ad_id" style="display: none">
                                    <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id'];?>" class="form-control">
                                  </div>

                                  <div class="col-md-12 interstital_facebook_id" style="display: none">
                                    <input type="text" name="interstital_facebook_id" id="interstital_facebook_id" value="<?php echo $settings_row['interstital_facebook_id'];?>" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <p class="field_lable">Interstitial Clicks :-</p>
                                  <div class="col-md-12">
                                    <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click'];?>" class="form-control">
                                  </div>
                                </div>                    
                              </div>                  
                            </div>  
                          </div>
                        </div>
                        <div class="col-md-6">                
                          <div class="col-md-12">
                            <div class="admob_title">iOS</div>
                            <div class="form-group">
                              <label class="col-md-3 control-label">Publisher ID :-</label>
                              <div class="col-md-9">
                                <input type="text" name="publisher_id_ios" id="publisher_id_ios" value="<?php echo $settings_row['publisher_id_ios'];?>" class="form-control">
                              </div>
                            </div>
                            <div class="banner_ads_block">
                              <div class="banner_ad_item">
                                <label class="control-label">Admob Banner Ads :-</label>                                  
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="col-md-3 control-label">Banner Ad:-</label>
                                  <div class="col-md-9">
                                     <select name="banner_ad_ios" id="banner_ad_ios" class="select2">
                                            <option value="true" <?php if($settings_row['banner_ad_ios']=='true'){?>selected<?php }?>>True</option>
                                            <option value="false" <?php if($settings_row['banner_ad_ios']=='false'){?>selected<?php }?>>False</option>
                                
                                        </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-3 control-label mr_bottom20">Banner ID :-</label>
                                  <div class="col-md-9">
                                    <input type="text" name="banner_ad_id_ios" id="banner_ad_id_ios" value="<?php echo $settings_row['banner_ad_id_ios'];?>" class="form-control">
                                  </div>
                                </div>                    
                              </div>
                            </div>  
                          </div>
                          <div class="col-md-12">
                            <div class="interstital_ads_block">
                              <div class="interstital_ad_item">
                                <label class="control-label">Admob Interstitial Ads :-</label>                   
                              </div>  
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label class="col-md-3 control-label">Interstitial:-</label>
                                  <div class="col-md-9">
                                    <select name="interstital_ad_ios" id="interstital_ad_ios" class="select2">
                                            <option value="true" <?php if($settings_row['interstital_ad_ios']=='true'){?>selected<?php }?>>True</option>
                                            <option value="false" <?php if($settings_row['interstital_ad_ios']=='false'){?>selected<?php }?>>False</option>
                                
                                        </select> 
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-3 control-label mr_bottom20">Interstitial ID :-</label>
                                  <div class="col-md-9">
                                  <input type="text" name="interstital_ad_id_ios" id="interstital_ad_id_ios" value="<?php echo $settings_row['interstital_ad_id_ios'];?>" class="form-control">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-3 control-label mr_bottom20">Interstitial Clicks :-</label>
                                  <div class="col-md-9">
                                  <input type="text" name="interstital_ad_click_ios" id="interstital_ad_click_ios" value="<?php echo $settings_row['interstital_ad_click_ios'];?>" class="form-control">
                                  </div>
                                </div>                    
                              </div>                  
                            </div>  
                          </div>
                        </div>
                         <div class="col-md-6">
                        <div class="col-md-12">
                          <div class="banner_ads_block">
                            <div class="banner_ad_item">
                              <label class="control-label">Facebook Banner Ads :-</label>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Banner Ad:-</label>
                                <div class="col-md-9">
                                  <select name="facebook_banner_ad" id="facebook_banner_ad" class="select2">
                                    <option value="true" <?php if ($settings_row['facebook_banner_ad'] == 'true') { ?>selected<?php } ?>>True</option>
                                    <option value="false" <?php if ($settings_row['facebook_banner_ad'] == 'false') { ?>selected<?php } ?>>False</option>
                                  </select>
                                </div>
                               </div>
                                <div class="form-group">
                                <label class="col-md-3 control-label mr_bottom20">Banner ID :-</label>
                                <div class="col-md-9">
                                  <input type="text" name="facebook_banner_ad_id" id="facebook_banner_ad_id" value="<?php echo $settings_row['facebook_banner_ad_id']; ?>" class="form-control">
                                </div>
	                         </div>
	                         </div>
	                        </div>       
	                       </div>
	                        <div class="col-md-12">
                          <div class="interstital_ads_block">
                          	
                            <div class="interstital_ad_item">
                              <label class="control-label">Facebook Interstital Ads :-</label>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Interstital:-</label>
                                <div class="col-md-9">
                                  <select name="facebook_interstital_ad" id="facebook_interstital_ad" class="select2">
                                    <option value="true" <?php if ($settings_row['facebook_interstital_ad'] == 'true') { ?>selected<?php } ?>>True</option>
                                    <option value="false" <?php if ($settings_row['facebook_interstital_ad'] == 'false') { ?>selected<?php } ?>>False</option>

                                  </select>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-md-3 control-label mr_bottom20">Interstital ID :-</label>
                                <div class="col-md-9">
                                  <input type="text" name="facebook_interstital_ad_id" id="facebook_interstital_ad_id" value="<?php echo $settings_row['facebook_interstital_ad_id']; ?>" class="form-control">
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-md-3 control-label mr_bottom20">Interstital Clicks :-</label>
                                <div class="col-md-9">
                                  <input type="text" name="facebook_interstital_ad_click" id="facebook_interstital_ad_click" value="<?php echo $settings_row['facebook_interstital_ad_click']; ?>" class="form-control">
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
	                       </div>
	                      </div>
	                    </div>                        
	                    <div class="form-group">
	                      <div class="col-md-9">
	                      <button type="submit" name="admob_submit" class="btn btn-primary">Save</button>
	                      </div>
	                    </div>
	                </div>
	              </div>
	            </form>
	          </div>

            <div role="tabpanel" class="tab-pane" id="app_update">   
              <form action="" name="app_update" method="post" class="form form-horizontal" enctype="multipart/form-data">

                <div class="section">
                  <div class="section-body">
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Update Popup Show/Hide:-
                        <p class="control-label-help" style="color:#F00">You can show/hide update popup from this option</p>
                      </label>
                      <div class="col-md-6">
                        <div class="row" style="margin-top: 15px">
                          <input type="checkbox" id="chk_update" name="update_status" value="true" class="cbx hidden" <?php if($settings_row['update_status']=='true'){ echo 'checked'; }?>/>
                          <label for="chk_update" class="lbl" style="left:13px;"></label>
                        </div>
                      </div>                   
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">New App Version Code :-
                        <a href="assets/images/android_version_code.png" target="_blank"><p class="control-label-help" style="color:#F00">How to get version code</p></a>
                      </label>
                      <div class="col-md-6">
                        <input type="number" min="1" name="new_app_version" id="new_app_version" required="" value="<?php echo $settings_row['new_app_version'];?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Description :-</label>
                      <div class="col-md-6">
                        <textarea name="app_update_desc" class="form-control"><?php echo $settings_row['app_update_desc'];?></textarea>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Link :-
                        <p class="control-label-help">You will be redirect on this link after click on update</p>
                      </label>
                      <div class="col-md-6">
                        <input type="text" name="app_link" id="app_link" required="" value="<?php echo $settings_row['app_link'];?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Cancel Option :-
                        <p class="control-label-help" style="color:#F00">Cancel button option will show in app update popup</p>
                      </label>
                      <div class="col-md-6">
                        <div class="row" style="margin-top: 15px">
                          <input type="checkbox" id="chk_cancel_update" name="cancel_status" value="true" class="cbx hidden" <?php if($settings_row['cancel_status']=='true'){ echo 'checked'; }?>/>
                          <label for="chk_cancel_update" class="lbl" style="left:13px;"></label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-9 col-md-offset-3">
                        <button type="submit" name="app_update" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="api_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data">
                <div class="section">
                  <div class="section-body">
                    <?php if($settings_details['ios_envato_purchased_status'] == '1'){?>
                      <div class="checkbox" style="margin-bottom :20px;">
                        <input type="checkbox" name="checkbox" id="checkbox_api_settings" class="btn_import_a" data-type="api_settings">
                        <label for="checkbox_api_settings">
                          Import From iOs Api Settings
                        </label>
                      </div>
                      <br>
                    <?php }?>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Home Limit:-</label>
                      <div class="col-md-6">
                        <input type="number" name="api_home_limit" id="api_home_limit" value="<?php echo $settings_row['api_home_limit']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Latest Limit:-</label>
                      <div class="col-md-6">

                        <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit']; ?>" class="form-control">
                      </div>

                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Pagination Limit:-</label>
                      <div class="col-md-6">
                        <input type="number" name="api_page_limit" id="api_page_limit" value="<?php echo $settings_row['api_page_limit']; ?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Category List Order By:-</label>
                      <div class="col-md-6">

                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if ($settings_row['api_cat_order_by'] == 'cid') { ?>selected<?php } ?>>ID</option>
                          <option value="category_name" <?php if ($settings_row['api_cat_order_by'] == 'category_name') { ?>selected<?php } ?>>Name</option>

                        </select>

                      </div>

                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Category Jobs Order:-</label>
                      <div class="col-md-6">


                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if ($settings_row['api_cat_post_order_by'] == 'ASC') { ?>selected<?php } ?>>ASC</option>
                          <option value="DESC" <?php if ($settings_row['api_cat_post_order_by'] == 'DESC') { ?>selected<?php } ?>>DESC</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-9 col-md-offset-3">
                        <button type="submit" name="api_submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="api_privacy_policy">
              <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
                <div class="section">
                  <div class="section-body">
                    <?php if($settings_details['ios_envato_purchased_status'] == '1'){?>
                      <div class="checkbox" style="margin-bottom :15px;">
                        <input type="checkbox" id="checkbox_checkbox_api_settings" class="btn_import_a" data-type="api_privacy_policy">
                        <label for="checkbox_checkbox_api_settings">
                          Import From iOs App Privacy Policy
                        </label>
                      </div>
                      <br/>
                    <?php }?>
                    <div class="form-group">
                      <label class="col-md-3 control-label">App Privacy Policy :-</label>
                      <div class="col-md-6">
                        <textarea name="app_privacy_policy" id="app_privacy_policy" class="form-control"><?php echo $settings_row['app_privacy_policy']; ?></textarea>
                        <script>
                          CKEDITOR.replace('app_privacy_policy');
                        </script>
                      </div>
                    </div>

                    <div class="form-group">&nbsp;&nbsp;</div>
                    <div class="form-group">
                      <div class="col-md-9 col-md-offset-3">
                        <button type="submit" name="app_pri_poly" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

          </div>

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

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
      localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
      $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }

    if ($("select[name='banner_ad_type']").val() === 'facebook') {
      $(".banner_ad_id").hide();
      $(".banner_facebook_id").show();
    } else {
      $(".banner_facebook_id").hide();
      $(".banner_ad_id").show();
    }

    $("select[name='banner_ad_type']").change(function(e) {
      if ($(this).val() === 'facebook') {
        $(".banner_ad_id").hide();
        $(".banner_facebook_id").show();
      } else {
        $(".banner_facebook_id").hide();
        $(".banner_ad_id").show();
      }
    });


    if ($("select[name='interstital_ad_type']").val() === 'facebook') {
      $(".interstital_ad_id").hide();
      $(".interstital_facebook_id").show();
    } else {
      $(".interstital_facebook_id").hide();
      $(".interstital_ad_id").show();
    }

    $("select[name='interstital_ad_type']").change(function(e) {

      if ($(this).val() === 'facebook') {
        $(".interstital_ad_id").hide();
        $(".interstital_facebook_id").show();
      } else {
        $(".interstital_facebook_id").hide();
        $(".interstital_ad_id").show();
      }
    });


    if ($("select[name='native_ad_type']").val() === 'facebook') {
      $(".native_ad_id").hide();
      $(".native_facebook_id").show();
    } else {
      $(".native_facebook_id").hide();
      $(".native_ad_id").show();
    }

    $("select[name='native_ad_type']").change(function(e) {

      if ($(this).val() === 'facebook') {
        $(".native_ad_id").hide();
        $(".native_facebook_id").show();
      } else {
        $(".native_facebook_id").hide();
        $(".native_ad_id").show();
      }
    });
  </script>

  <script>
    $("#interstital_ad_click").blur(function(e) {
      if ($(this).val() == '')
        $(this).val("0");
    });
  </script>

   <script>
    $("#interstital_ad_click_ios").blur(function(e) {
      if ($(this).val() == '')
        $(this).val("0");
    });
  </script>

   <script>
    $("#facebook_interstital_ad_click").blur(function(e) {
      if ($(this).val() == '')
        $(this).val("0");
    });
  </script>
