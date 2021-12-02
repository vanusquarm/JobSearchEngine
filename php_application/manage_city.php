<?php $page_title="Manage City";

include('includes/header.php');

include('includes/function.php');
include('language/language.php');


if (isset($_POST['search'])) {

  $keyword = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);

  $city_qry = "SELECT * FROM tbl_city WHERE tbl_city.`city_name` LIKE '%$keyword%' ORDER BY tbl_city.`c_id` DESC";

  $result = mysqli_query($mysqli, $city_qry);
  
} else {

  $tableName = "tbl_city";
  $targetpage = "manage_city.php";
  $limit = 15;

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


  $city_qry = "SELECT * FROM tbl_city
		 ORDER BY tbl_city.`c_id` DESC LIMIT $start, $limit";

  $result = mysqli_query($mysqli, $city_qry);
}

?>


<div class="row">
  <div class="col-xs-12">
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Manage City</div>
        </div>
        <div class="col-md-7 col-xs-12">
          <div class="search_list">
            <div class="search_block">
              <form method="post" action="">
                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                <button type="submit" name="search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>
            </div>
            <div class="add_btn_primary"> <a href="add_city.php?add">Add City</a> </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th>City Name</th>
              <th>Status</th>
              <th class="cat_action_list">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {

            ?>
              <tr>
                <td><?php echo $row['city_name']; ?></td>

                <td>
                  <?php if ($row['status'] != "0") { ?>
                    <a title="Change Status" class="toggle_btn_a" href="javascript:void(0)" data-id="<?= $row['c_id'] ?>" data-action="deactive" data-column="status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Enable</span></span></a>

                  <?php } else { ?>
                    <a title="Change Status" class="toggle_btn_a" href="javascript:void(0)" data-id="<?= $row['c_id'] ?>" data-action="active" data-column="status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Disable </span></span></a>
                  <?php } ?>
                </td>
                <td>
                  <a href="add_city.php?city_id=<?php echo $row['c_id']; ?>" class="btn btn-primary btn_cust" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a>

                  <a href="" data-id="<?php echo $row['c_id']; ?>" data-toggle="tooltip" data-tooltip="Delete" class="btn btn-danger btn_delete"><i class="fa fa-trash"></i></a></a></td>
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
    var _table = 'tbl_city';

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
        'tbl_id': 'c_id'
      },
      success: function(res) {
        console.log(res);
        if (res.status == '1') {
          location.reload();
        }
      }
    });

  });


  // for deletion
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
              'id': _ids,
              'action': 'multi_delete',
              'tbl_nm': 'tbl_city'
            },
            success: function(res) {
              console.log(res);
              if (res.status == '1') {
                swal({
                  title: "Successfully",
                  text: "city is deleted...",
                  type: "success"
                }, function() {
                  location.reload();
                });
              } else if (res.status == '-2') {
                swal(res.message);
              }
            }
          });
        } else {
          swal.close();
        }
      });
  });
</script>