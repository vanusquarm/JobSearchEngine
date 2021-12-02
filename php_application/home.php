<?php  $page_title="Dashboard";

include("includes/header.php");

$qry_cat = "SELECT COUNT(*) as num FROM tbl_category";
$total_category = mysqli_fetch_array(mysqli_query($mysqli, $qry_cat));
$total_category = $total_category['num'];

$qry_jobs = "SELECT COUNT(*) as num FROM tbl_jobs";
$total_jobs = mysqli_fetch_array(mysqli_query($mysqli, $qry_jobs));
$total_jobs = $total_jobs['num'];

$qry_city = "SELECT COUNT(*) as num FROM tbl_city";
$total_city = mysqli_fetch_array(mysqli_query($mysqli, $qry_city));
$total_city = $total_city['num'];

$qry_users = "SELECT COUNT(*) as num FROM tbl_users WHERE id <> 0";
$total_user = mysqli_fetch_array(mysqli_query($mysqli, $qry_users));
$total_user = $total_user['num'];

$sql_apply = "SELECT COUNT(*) as num FROM tbl_apply";
$total_apply = mysqli_fetch_array(mysqli_query($mysqli, $sql_apply));
$total_apply = $total_apply['num'];

$countStr = '';

$no_data_status = false;
$count = $monthCount = 0;

for ($mon = 1; $mon <= 12; $mon++) {

  if (date('n') < $mon) {
    break;
  }

  $monthCount++;

  if (isset($_GET['filterByYear'])) {

    $year = $_GET['filterByYear'];
    $month = date('M', mktime(0, 0, 0, $mon, 1, $year));

    $sql_user = "SELECT `id` FROM tbl_users WHERE `register_date` <> 0  AND id <> 0 AND DATE_FORMAT(FROM_UNIXTIME(`register_date`), '%c') = '$mon' AND DATE_FORMAT(FROM_UNIXTIME(`register_date`), '%Y') = '$year'";

    $totalcount = mysqli_num_rows(mysqli_query($mysqli, $sql_user));
  } else {
    $month = date('M', mktime(0, 0, 0, $mon, 1, date('Y')));

    $sql_user = "SELECT `id` FROM tbl_users WHERE `register_date` <> 0  AND id <> 0 AND DATE_FORMAT(FROM_UNIXTIME(`register_date`), '%c') = '$mon'";

    $totalcount = mysqli_num_rows(mysqli_query($mysqli, $sql_user));
  }

  $countStr .= "['" . $month . "', " . $totalcount . "], ";

  if ($totalcount == 0) {
    $count++;
  }
}

if ($monthCount > $count) {
  $no_data_status = false;
} else {
  $no_data_status = true;
}

$countStr = rtrim($countStr, ", ");

function array_msort($array, $cols)
{
  $colarr = array();
  foreach ($cols as $col => $order) {
    $colarr[$col] = array();
    foreach ($array as $k => $row) {
      $colarr[$col]['_' . $k] = strtolower($row[$col]);
    }
  }
  $eval = 'array_multisort(';
  foreach ($cols as $col => $order) {
    $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
  }
  $eval = substr($eval, 0, -1) . ');';
  eval($eval);
  $ret = array();
  foreach ($colarr as $col => $arr) {
    foreach ($arr as $k => $v) {
      $k = substr($k, 1);
      if (!isset($ret[$k])) $ret[$k] = $array[$k];
      $ret[$k][$col] = $array[$k][$col];
    }
  }
  return $ret;
}


?>
<style type="text/css">
  .table>tbody,
  .table>tbody>tr,
  .table>tbody>tr>td {
    display: block !important;
  }
</style>

<div class="btn-floating" id="help-actions">
  <div class="btn-bg"></div>
  <button type="button" class="btn btn-default btn-toggle" data-toggle="toggle" data-target="#help-actions"> <i class="icon fa fa-plus"></i> <span class="help-text">Shortcut</span> </button>
  <div class="toggle-content">
    <ul class="actions">
      <li><a href="http://www.viaviweb.com" target="_blank">Website</a></li>
      <li><a href="Documentation/index.html" target="_blank">Documentation</a></li>
      <li><a href="https://codecanyon.net/user/viaviwebtech?ref=viaviwebtech" target="_blank">About</a></li>
    </ul>
  </div>
</div>
  
<?php 
  $sql_smtp="SELECT * FROM tbl_smtp_settings WHERE id='1'";
  $res_smtp=mysqli_query($mysqli,$sql_smtp);
  $row_smtp=mysqli_fetch_assoc($res_smtp);

  $smtp_warning=true;

  if(!empty($row_smtp))
  {

    if($row_smtp['smtp_type']=='server'){
      if($row_smtp['smtp_host']!='' AND $row_smtp['smtp_email']!=''){
        $smtp_warning=false;
      }
      else{
        $smtp_warning=true;
      }  
    }
    else if($row_smtp['smtp_type']=='gmail'){
      if($row_smtp['smtp_ghost']!='' AND $row_smtp['smtp_gemail']!=''){
        $smtp_warning=false;
      }
      else{
        $smtp_warning=true;
      }  
    }
  }

  if($smtp_warning)
  {
?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
    <h4 id="oh-snap!-you-got-an-error!"><i class="fa fa-exclamation-triangle"></i> SMTP Setting is not config<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
        <p style="margin-bottom: 10px">Config the smtp setting otherwise <strong>forgot password</strong> OR <strong>email</strong> feature will not be work.</p> 
    </div>
  </div>
</div>
<?php } ?>


<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_category.php" class="card card-banner card-green-light">
      <div class="card-body"> <i class="icon fa fa-sitemap fa-4x"></i>
        <div class="content">
          <div class="title">Categories</div>
          <div class="value"><span class="sign"></span><?php echo $total_category; ?></div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_city.php" class="card card-banner card-blue-light">
      <div class="card-body"> <i class="icon fa fa-list fa-4x"></i>
        <div class="content">
          <div class="title">City</div>
          <div class="value"><span class="sign"></span><?php echo $total_city; ?></div>
        </div>
      </div>
    </a>
  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_jobs.php" class="card card-banner card-yellow-light">
      <div class="card-body"> <i class="icon fa fa-black-tie fa-4x"></i>
        <div class="content">
          <div class="title">Jobs</div>
          <div class="value"><span class="sign"></span><?php echo $total_jobs; ?></div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12"> <a href="manage_users.php" class="card card-banner card-orange-light">
      <div class="card-body"> <i class="icon fa fa-users fa-4x"></i>
        <div class="content">
          <div class="title">Users</div>
          <div class="value"><span class="sign"></span><?php echo $total_user; ?></div>
        </div>
      </div>
    </a>
  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mr_bot60">
    <a href="manage_applied_users.php" class="card card-banner card-aliceblue-light">
      <div class="card-body"> <i class="icon fa fa-check-square-o fa-4x"></i>
        <div class="content">
          <div class="title">Applied Users</div>
          <div class="value"><span class="sign"></span><?php echo $total_apply; ?></div>
        </div>
      </div>
    </a>
  </div>
 
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="container-fluid" style="background: #FFF;box-shadow: 0px 5px 10px 0px #CCC;border-radius: 2px;width: 100%;display: inline-block;">
      <div class="col-lg-10">
        <h3>Users Analysis</h3>
      </div>
      <div class="col-lg-2" style="padding-top: 20px">
        <form method="get" id="graphFilter">
          <select class="form-control" name="filterByYear" style="box-shadow: none;height: auto;border-radius: 0px;font-size: 16px;">
            <?php
            $currentYear = date('Y');
            $minYear = 2018;

            for ($i = $currentYear; $i >= $minYear; $i--) {
            ?>
              <option value="<?= $i ?>" <?= (isset($_GET['filterByYear']) && $_GET['filterByYear'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
            <?php
            }
            ?>
          </select>
        </form>
      </div>
      <div class="col-lg-12">
        <?php
        if ($no_data_status) {
        ?>
          <h3 class="text-muted text-center" style="padding-bottom: 2em">No data found !</h3>
        <?php
        } else {
        ?>
          <div id="registerChart"></div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<?php
if (!$no_data_status) {
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {
      packages: ['corechart', 'line']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Month');
      data.addColumn('number', 'Total Users');

      data.addRows([<?= $countStr ?>]);

      var options = {
        curveType: 'function',
        fontSize: 15,
        hAxis: {
          title: "Months of <?= (isset($_GET['filterByYear'])) ? $_GET['filterByYear'] : date('Y') ?>",
          titleTextStyle: {
            color: '#000',
            bold: 'true',
            italic: false
          },
        },
        vAxis: {
          title: "Nos. of Users",
          titleTextStyle: {
            color: '#000',
            bold: 'true',
            italic: false,
          },
          gridlines: {
            count: -1
          },
          format: '#',
          viewWindowMode: "explicit",
          viewWindow: {
            min: 0,
            max: 'auto'
          },
        },
        height: 400,
        chartArea: {
          left: 50,
          top: 20,
          width: '100%',
          height: 'auto'
        },
        legend: {
          position: 'bottom'
        },
        colors: ['#3366CC', 'green', 'red'],
        lineWidth: 4,
        animation: {
          startup: true,
          duration: 1200,
          easing: 'out',
        },
        pointSize: 5,
        pointShape: "circle",

      };
      var chart = new google.visualization.LineChart(document.getElementById('registerChart'));

      chart.draw(data, options);
    }


    $(document).ready(function() {
      $(window).resize(function() {
        drawChart();
      });
    });
  </script>
<?php
}
?>
<script type="text/javascript">
  // filter of graph
  $("select[name='filterByYear']").on("change", function(e) {
    $("#graphFilter").submit();
  });
</script>