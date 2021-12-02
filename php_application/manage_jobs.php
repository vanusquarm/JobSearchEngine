<?php 
	  $page_title="Manage Jobs";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");

 $tableName="tbl_jobs";  
 $limit = 12; 

	if(isset($_GET['filter'])){
			if($_GET['filter']=='enable'){
			  $status="tbl_jobs.`status`='1'";
			}else if($_GET['filter']=='disable'){
			  $status="tbl_jobs.`status`='0'";
			}
      } 


  if(isset($_GET['cat_id'])){
  
      $cat_id = filter_var($_GET['cat_id'], FILTER_SANITIZE_STRING);

  	 if(isset($_GET['filter'])){
      
        $query ="SELECT COUNT(*) as num FROM tbl_jobs 
					LEFT JOIN tbl_category ON tbl_jobs.`cat_id`=tbl_category.`cid`
		        	WHERE $status AND ".$_GET['filter']."";

        $targetpage = "manage_jobs.php?cat_id=$cat_id&filter=".$_GET['filter'];
      }
      else{
       $query = "SELECT COUNT(*) as num FROM $tableName WHERE `cat_id`='$cat_id'";

	   $targetpage = "manage_jobs.php?cat_id=".$cat_id; 
      }
      

      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
      $total_pages = $total_pages['num'];
      
      $stages = 3;
      $page=0;
      if(isset($_GET['page'])){
        $page = mysqli_real_escape_string($mysqli,$_GET['page']);
      }
      if($page){
        $start = ($page - 1) * $limit; 
      }else{
        $start = 0; 
      }

     $job_qry="SELECT tbl_category.`category_name`,tbl_jobs.* FROM tbl_jobs
	            LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
	            WHERE tbl_jobs.`cat_id`='$cat_id'
	            ORDER BY tbl_jobs.`id` DESC LIMIT $start, $limit";

	 $job_result=mysqli_query($mysqli,$job_qry);

      if(isset($_GET['filter'])){
  
        $status='';

			if($_GET['filter']=='enable'){
			  	$status="tbl_jobs.`status`='1'";
			}else if($_GET['filter']=='disable'){
			  	$status="tbl_jobs.`status`='0'";
				
			}
        $job_qry="SELECT tbl_category.`category_name`,tbl_jobs.* FROM tbl_jobs
				LEFT JOIN tbl_category ON tbl_jobs.`cat_id`=tbl_category.`cid`
				WHERE $status AND tbl_jobs.`cat_id`='$cat_id'
				ORDER BY tbl_jobs.`id` DESC LIMIT $start, $limit";

        $job_result=mysqli_query($mysqli,$job_qry);
      }

  }
  else if(isset($_GET['filter'])){

		
			$targetpage = "manage_jobs.php?filter=".$_GET['filter'];
			$status='';

			if($_GET['filter']=='enable'){
			  	$status="tbl_jobs.`status`='1'";
			}else if($_GET['filter']=='disable'){
			  	$status="tbl_jobs.`status`='0'";
				
			}

			$query ="SELECT COUNT(*) as num FROM tbl_jobs 
					LEFT JOIN tbl_category ON tbl_jobs.`cat_id`=tbl_category.`cid`
		        	WHERE $status";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
			$total_pages = $total_pages['num'];

			$stages = 3;
			$page=0;
			if(isset($_GET['page'])){
			$page = mysqli_real_escape_string($mysqli,$_GET['page']);
			}
			if($page){
			  $start = ($page - 1) * $limit; 
			}else{
			  $start = 0; 
			} 


			$job_qry="SELECT tbl_category.`category_name`,tbl_jobs.* FROM tbl_jobs
				LEFT JOIN tbl_category ON tbl_jobs.`cat_id`=tbl_category.`cid`
				WHERE $status
				ORDER BY tbl_jobs.`id` DESC LIMIT $start, $limit";
			 
	 		$job_result=mysqli_query($mysqli,$job_qry) or die(mysqli_error($mysqli));
	 }
	 		
  else if(isset($_POST['job_search']))
	 {
		
		$keyword = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);

		$job_qry="SELECT tbl_jobs.*,tbl_category.`category_name` FROM tbl_jobs
                  LEFT JOIN tbl_category ON tbl_jobs.`cat_id` = tbl_category.`cid` WHERE tbl_jobs.`job_name` LIKE '%$keyword%' ORDER BY tbl_jobs.`id` DESC";  
							 
		$job_result=mysqli_query($mysqli,$job_qry);
		 
	 }
	
   else
   {
			     
		      $targetpage = "manage_jobs.php"; 
		      $query = "SELECT COUNT(*) as num FROM $tableName";
		      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
		      $total_pages = $total_pages['num'];
		      
		      $stages = 3;
		      $page=0;
		      if(isset($_GET['page'])){
		      $page = mysqli_real_escape_string($mysqli,$_GET['page']);
		      }
		      if($page){
		        $start = ($page - 1) * $limit; 
		      }else{
		        $start = 0; 
		        } 
		      
		     $job_qry="SELECT tbl_category.`category_name`,tbl_jobs.* FROM tbl_jobs
		            LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		            ORDER BY tbl_jobs.`id` DESC LIMIT $start, $limit";
		 
		     $job_result=mysqli_query($mysqli,$job_qry); 
		}  

function get_user_info($user_id,$field_name) 
  {
    global $mysqli;

    $qry_user="SELECT * FROM tbl_users WHERE id='".$user_id."'";
    $query1=mysqli_query($mysqli,$qry_user);
    $row_user = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);
    
    if ($num_rows1 > 0)
    {     
      return $row_user[$field_name];
    }
    else
    {
      return "";
    }
  }
  	
?>
                
    <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Jobs</div>
            </div>
            <div class="col-md-7 col-xs-12">
              <div class="search_list">
                 <div class="search_block">
                      <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" value="<?php if(isset($_POST['search_value'])){ echo $_POST['search_value']; } ?>" required>
                        <button type="submit" name="job_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_job.php">Add Job</a> </div>
              </div>
            </div>
            <form id="filterForm" accept="" method="GET">
             <div class="col-md-8">
              <div class="" style="padding: 0px 0px 5px;float: left;margin-right:10px;">
                <select name="filter" class="form-control select2 filter" required style="padding: 5px 40px;height: 40px;">
                    <option value="">All</option>
                    <option value="enable" <?php if(isset($_GET['filter']) && $_GET['filter']=='enable'){ echo 'selected';} ?>>Enable</option>
                    <option value="disable" <?php if(isset($_GET['filter']) && $_GET['filter']=='disable'){ echo 'selected';} ?>>Disable</option>
                  </select>
              </div>
              <div class="" style="padding: 0px 0px 5px;float: left;">
                  <select name="cat_id" class="form-control select2 filter" required style="padding: 5px 40px;height: 40px;">
                    <option value="">All Category</option>
                    <?php
                      $cat_qry="SELECT * FROM tbl_category ORDER BY category_name";
                      $cat_result=mysqli_query($mysqli,$cat_qry);
                      while($cat_row=mysqli_fetch_array($cat_result))
                      {
                    ?>                       
                    <option value="<?php echo $cat_row['cid'];?>" <?php if(isset($_GET['cat_id']) && $_GET['cat_id']==$cat_row['cid']){echo 'selected';} ?>><?php echo $cat_row['category_name'];?></option>                           
                    <?php
                      }
                    ?>
                  </select>
              </div>
             </div>
         	</form>
              <div class="col-md-6 col-xs-12" style="float: right;width:200px">
                <div class="checkbox">
                  <input type="checkbox" name="checkall" id="checkall" value="">
                  <label for="checkall">Select All</label>
                  <button type="submit" class="btn btn-danger btn_cust" name="delete_rec" value="delete_wall">Delete</button>
                </div> 
              </div>

          </div>
          <div class="clearfix"></div>
          <div class="col-md-12 mrg-top">
            <div class="row">
              <?php 
                  $i=0;
                  while($row=mysqli_fetch_array($job_result))
                  {         
              ?>
              <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="block_wallpaper" style="box-shadow: 2px 3px 5px #333">           
                  <div class="wall_category_block">
                    <h2>
                      <?php 
                          if(strlen($row['category_name']) > 13){
                            echo mb_substr(stripslashes($row['category_name']), 0, 13).'...';  
                          }else{
                            echo $row['category_name'];
                          }
                      ?>
                    </h2>
                    
                    <div class="checkbox" style="float: right;margin-left: 10px;z-index: 5;">
                      <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>" class="post_ids">
                      <label for="checkbox<?php echo $i;?>">
                      </label>
                    </div> 

                  </div>
                  <div class="wall_image_title">
                     <p style="font-size: 16px;"><?php echo stripslashes($row['job_name']);?></p>
                     <p>By: <a href="user_profile.php?user_id=<?php echo $row['user_id'];?>" style="color: #ddd"><?=ucwords(get_user_info($row['user_id'],'name'))?></a></p>
                    <ul>
                      <li><a href="edit_job.php?job_id=<?php echo $row['id'];?>&action=edit&redirect=<?=$redirectUrl?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>

                      <li>
                        <a href="" class="btn_delete_a" data-id="<?php echo $row['id'];?>"  data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a>
                      </li>

                      <?php if($row['status']!="0"){?>
                        <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="" /></a></div></li>

                      <?php }else{?>
                        
                        <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="" /></a></div></li>
                      <?php }?>
                      
                    </ul>
                  </div>
                  <span><img src="images/<?php echo $row['job_image'];?>" /></span>
                </div>
              </div>
          <?php $i++; }?>
         </div>
          </div>
           <div class="col-md-12 col-xs-12">
            <div class="pagination_item_block">
              <nav>
                <?php if(!isset($_POST["job_search"])){ include("pagination.php");}?>          
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
        
<?php include("includes/footer.php");?>       

<script type="text/javascript">

  $(".toggle_btn a, .toggle_btn_a").on("click",function(e){
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
            data:{id:_ids,'action':'multi_delete','tbl_nm':'tbl_jobs'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  swal({
                      title: "Successfully", 
                      text: "Job is deleted...", 
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
        swal({
          title: "Do you really want to perform?",
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
              data:{id:_ids,'action':'multi_delete','tbl_nm':'tbl_jobs'},
              success:function(res){
                  console.log(res);
                  $('.notifyjs-corner').empty();
                  if(res.status=='1'){
                    swal({
                        title: "Successfully", 
                        text: "You have successfully done", 
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
      }
      else{
        swal("Sorry no data selected !!")
      }
   });

  $("select[name='cat_id']").on("change",function(e){
    if($(this).val()!='')
    {
      window.location.href="manage_jobs.php?cat_id="+$(this).val();
    }else{
      window.location.href="manage_jobs.php";
    }
  });

 $(".filter_status").on("change",function(e){
    var _val=$(this).val();
    if(_val!=''){
      window.location.href="manage_jobs.php?filter="+_val;
    }else{
      window.location.href="manage_jobs.php";
    }
  });

$("#checkall").click(function () {
  $('input:checkbox').not(this).prop('checked', this.checked);
});

$(".filter").on("change",function(e){
    $("#filterForm *").filter(":input").each(function(){
      if ($(this).val() == '')
        $(this).prop("disabled", true);
    });
    $("#filterForm").submit();
  });

</script> 
