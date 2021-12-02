<?php $page_title="Manage Users";

include('includes/header.php');
include('includes/function.php');
include('language/language.php');


if(isset($_GET['user_type'])){

	      $user_type = filter_var($_GET['user_type'], FILTER_SANITIZE_STRING);

	      $tableName="tbl_users";   
	      $targetpage = "manage_users.php?user_type=".$user_type; 
	      $limit = 12; 
	      
	      $query = "SELECT COUNT(*) as num FROM $tableName WHERE tbl_users.`id` <> 0 AND `user_type`='$user_type'";
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
	      
	      $users_qry = "SELECT * FROM tbl_users WHERE tbl_users.`id` <> 0 AND `user_type`=$user_type
       		ORDER BY tbl_users.`id` DESC LIMIT $start, $limit";

  		$users_result = mysqli_query($mysqli, $users_qry);

   }

else if (isset($_POST['user_search'])) {

  $keyword = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);

  $user_qry = "SELECT * FROM tbl_users WHERE tbl_users.`name` LIKE '%$keyword' or tbl_users.`email` LIKE '%$keyword' AND tbl_users.`id` <> 0 ORDER BY tbl_users.`id` DESC";

  $users_result = mysqli_query($mysqli, $user_qry);

} else {

  $tableName = "tbl_users";
  $targetpage = "manage_users.php";
  $limit = 15;

  $query = "SELECT COUNT(*) as num FROM $tableName WHERE tbl_users.`id` <> 0";
  $total_pages = mysqli_fetch_array(mysqli_query($mysqli, $query));
  $total_pages = $total_pages['num'];

  $stages = 3;
  $page = 0;
  if (isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysqli, $_GET['page']);
  }
  if ($page) {
    $start = ($page - 1) * $limit;
  } else {
    $start = 0;
  }


  $users_qry = "SELECT * FROM tbl_users WHERE tbl_users.`id` <> 0
       ORDER BY tbl_users.`id` DESC LIMIT $start, $limit";

  $users_result = mysqli_query($mysqli, $users_qry);
}

?>


<div class="row">
  <div class="col-xs-12">
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Manage Users</div>
        </div>
        <div class="col-md-7 col-xs-12">
          <div class="search_list">
            <div class="search_block">
              <form method="post" action="">
                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>
            </div>
            <div class="add_btn_primary"> <a href="add_user.php?add">Add User</a> </div>
          </div>
        </div>
         <div style="padding: 0px 0px 5px;float: left;margin-right:10px;margin-left:20px">
            <select name="user_type" class="form-control select2 filter" required style="padding: 5px 40px;height: 40px;">
                <option value="">All</option>
                <option value="1" <?php if(isset($_GET['user_type']) && $_GET['user_type']=='1'){ echo 'selected';} ?>>Job Seeker</option>
                <option value="2" <?php if(isset($_GET['user_type']) && $_GET['user_type']=='2'){ echo 'selected';} ?>>Job Provider</option>
              </select>
           </div>
        <div class="col-md-4 col-xs-12 text-right" style="float: right;">
          <button type="submit" class="btn btn-danger btn_delete" style="margin-bottom:20px;" name="delete_rec" value="delete_wall"><i class="fa fa-trash"></i> Delete All</button>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th nowrap="">
                <div class="checkbox" style="margin-top: 0px;margin-bottom: 0px;">
                  <input type="checkbox" name="checkall" id="checkall" value="">
                  <label for="checkall"></label>
                </div>
              </th>
              <th>User Type</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th class="cat_action_list">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ($users_row = mysqli_fetch_array($users_result)) { ?>
              <tr>
                <td nowrap="">
                  <div class="checkbox" style="margin: 0px;float: left;">
                    <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i; ?>" value="<?php echo $users_row['id']; ?>" class="post_ids">
                    <label for="checkbox<?php echo $i; ?>">
                    </label>
                  </div>
                </td>
                <td>
                  <?php
                  if ($users_row['user_type'] == 1) {
                    echo $user_type = 'Job Seeker';
                  } else {
                    echo $user_type = 'Job Provider';
                  }
                  ?>
                </td>
                <td>
                  <a href="user_profile.php?user_id=<?= $users_row['id'] ?>">
                    <?php echo $users_row['name']; ?>
                  </a>
                </td>
                <td><?php echo $users_row['email']; ?></td>
                <td><?php echo $users_row['phone']; ?></td>
                <td>
                  <?php if ($users_row['status'] != "0") { ?>
                    <a title="Change Status" class="toggle_btn_a" href="javascript:void(0)" data-id="<?= $users_row['id'] ?>" data-action="deactive" data-column="status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Enable</span></span></a>

                  <?php } else { ?>
                    <a title="Change Status" class="toggle_btn_a" href="javascript:void(0)" data-id="<?= $users_row['id'] ?>" data-action="active" data-column="status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Disable </span></span></a>
                  <?php } ?>
                </td>
                <td>
                  <a href="user_profile.php?user_id=<?php echo $users_row['id']; ?>" class="btn btn-success btn_cust" data-toggle="tooltip" data-tooltip="User Profile"><i class="fa fa-history"></i></a>

                  <a href="add_user.php?user_id=<?php echo $users_row['id']; ?>" class="btn btn-primary btn_cust" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a>

                  <a href="javascript:void(0)" data-id="<?php echo $users_row['id']; ?>" class="btn btn-danger btn_delete_a btn_cust" data-toggle="tooltip" data-tooltip="Delete !"><i class=" fa fa-trash"></i></a></td>
              </tr>
            <?php $i++;
            } ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 col-xs-12">
        <div class="pagination_item_block">
          <nav>
            <?php if (!isset($_POST["search"])) {
              include("pagination.php");
            } ?>
          </nav>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>



<?php include('includes/footer.php'); ?>

<script type="text/javascript">
  $(".toggle_btn_a").on("click", function(e) {
    e.preventDefault();

    var _for = $(this).data("action");
    var _id = $(this).data("id");
    var _column = $(this).data("column");
    var _table = 'tbl_users';

    $.ajax({
      type: 'post',
      url: 'processData.php',
      dataType: 'json',
      data: {
        id: _id,
        for_action: _for,
        column: _column,
        table: _table,
        'action': 'toggle_status',
        'tbl_id': 'id'
      },
      success: function(res) {
        console.log(res);
        if (res.status == '1') {
          location.reload();
        }
      }
    });

  });


  $(".btn_delete_a").click(function(e) {
    e.preventDefault();

    var _ids = $(this).data("id");

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
            type: 'post',
            url: 'processData.php',
            dataType: 'json',
            data: {
              id: _ids,
              'action': 'multi_delete',
              'tbl_nm': 'tbl_users'
            },
            success: function(res) {
              console.log(res);
              if (res.status == '1') {
                swal({
                  title: "Successfully",
                  text: "User is deleted...",
                  type: "success"
                }, function() {
                  location.reload();
                });
              }
            }
          });
        } else {
          swal.close();
        }
      });
  });

  $("button[name='delete_rec']").click(function(e) {
    e.preventDefault();

    var _ids = $.map($('.post_ids:checked'), function(c) {
      return c.value;
    });

    if (_ids != '') {
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
              type: 'post',
              url: 'processData.php',
              dataType: 'json',
              data: {
                id: _ids,
                'action': 'multi_delete',
                'tbl_nm': 'tbl_users'
              },
              success: function(res) {
                console.log(res);
                $('.notifyjs-corner').empty();
                if (res.status == '1') {
                  swal({
                    title: "Successfully",
                    text: "You have successfully done",
                    type: "success"
                  }, function() {
                    location.reload();
                  });
                }
              }
            });
          } else {
            swal.close();
          }

        });
    } else {
      swal("Sorry no users selected !!")
    }
  });


  $(".filter").on("change", function(e) {
    var _val = $(this).val();
    if (_val != '') {
      window.location.href = "manage_users.php?filter_type=" + _val;
    } else {
      window.location.href = "manage_users.php";
    }
  });

$("select[name='user_type']").on("change",function(e){
    if($(this).val()!='')
    {
      window.location.href="manage_users.php?user_type="+$(this).val();
    }else{
      window.location.href="manage_users.php";
    }
  });



  $("#checkall").click(function() {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });
</script>