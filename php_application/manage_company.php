<?php include('includes/header.php');

include('includes/function.php');
include('language/language.php');


if (isset($_POST['company_search'])) {


  $user_qry = "SELECT * FROM tbl_company 
				   WHERE tbl_company.`company_name` like '%" . addslashes($_POST['search_value']) . "%' or tbl_company.`company_email` like '%" . addslashes($_POST['search_value']) . "%' ORDER BY tbl_company.`id` DESC";

  $company_result = mysqli_query($mysqli, $user_qry);
} else {

  $tableName = "tbl_users";
  $targetpage = "manage_applied_users.php";
  $limit = 10;

  $query = "SELECT COUNT(*) as num FROM $tableName";
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


  $users_qry = "SELECT * FROM tbl_company
		           ORDER BY tbl_company.`id` DESC LIMIT $start, $limit";

  $company_result = mysqli_query($mysqli, $users_qry);
}

if (isset($_GET['company_id'])) {
	
  Delete('tbl_company', 'id=' . $_GET['company_id'] . '');

  $_SESSION['msg'] = "21";
  header("Location:manage_company.php");
  exit;
}

function get_user_name($user_id)
{
  global $mysqli;

  $qry_video = "SELECT * FROM tbl_users WHERE id='" . $user_id . "'";
  $query1 = mysqli_query($mysqli, $qry_video);
  $row_video = mysqli_fetch_array($query1);

  return $row_video['name'];
}
?>


<div class="row">
  <div class="col-xs-12">
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Manage Comapny</div>
        </div>
        <div class="col-md-7 col-xs-12">
          <div class="search_list">
            <div class="search_block">
              <form method="post" action="">
                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                <button type="submit" name="company_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>
            </div>

          </div>

        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
        <button class="btn btn-danger btn_cust btn_delete_all" style="margin-bottom:20px;"><i class="fa fa-trash"></i> Delete All</button>
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th style="width:40px">
                <div class="checkbox" style="margin: 0px">
                  <input type="checkbox" name="checkall" id="checkall" value="">
                  <label for="checkall"></label>
                </div>
              </th>
              <th>Users</th>
              <th>Comapny Name</th>
              <th>Company Email</th>
              <th>Phone</th>
              <th>Company Work Day</th>
              <th>Company Work Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ($company_row = mysqli_fetch_array($company_result)) {

            ?>
              <tr>
                <td>
                  <div class="checkbox">
                    <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i; ?>" value="<?php echo $company_row['id']; ?>" class="post_ids">
                    <label for="checkbox<?php echo $i; ?>"></label>
                  </div>
                </td>
                <td><?php echo get_user_name($company_row['user_id']); ?></td>
                <td><?php echo $company_row['company_name']; ?></td>
                <td><?php echo 'Only Admin Can See';?></td>
                <td><?php echo 'Only Admin Can See';?></td>
                <td><?php echo $company_row['company_work_day']; ?></td>
                <td><?php echo $company_row['company_work_time']; ?></td>
                <td>
                  <a href="javascript:void(0)" data-id="<?php echo $company_row['id']; ?>" class="btn btn-danger btn_delete" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
              </tr>
            <?php

              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 col-xs-12">
        <div class="pagination_item_block">
          <nav>
            <?php if (!isset($_POST["company_search"])) {
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
  // for multiple deletes
  $(".btn_delete_all").click(function(e) {
    var _ids = $.map($('.post_ids:checked'), function(c) {
      return c.value;
    });

    if (_ids != '') {
      swal({
          title: "Are you sure to delete this company?",
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
                ids: _ids,
                'action': 'removeCompany'
              },
              success: function(res) {
                console.log(res);
                if (res.status == '1') {
                  swal({
                    title: "Successfully",
                    text: "company has been deleted...",
                    type: "success"
                  }, function() {
                    location.reload();
                  });
                } else {
                  swal("Something went to wrong !");
                }
              }
            });
          } else {
            swal.close();
          }

        });
    } else {
      swal("Sorry no company selected !!")
    }
  });

  $(".btn_delete").click(function(e) {
    e.preventDefault();
    var _ids = $(this).data("id");
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
            type: 'post',
            url: 'processData.php',
            dataType: 'json',
            data: {
              ids: _ids,
              'action': 'removeCompany'
            },
            success: function(res) {
              console.log(res);
              if (res.status == '1') {
                swal({
                  title: "Successfully",
                  text: "company has been deleted...",
                  type: "success"
                }, function() {
                  location.reload();
                });
              } else {
                swal("Something went to wrong !");
              }
            }
          });
        } else {
          swal.close();
        }

      });

  });
</script>