<?php 
	require("includes/connection.php");
	require("includes/function.php");
	require("language/language.php");

	error_reporting(0);
   
    $file_path = getBaseUrl();

	$response=array();

	switch ($_POST['action']) {
		case 'toggle_status':
			$id=$_POST['id'];
			$for_action=$_POST['for_action'];
			$column=$_POST['column'];
			$tbl_id=$_POST['tbl_id'];
			$table_nm=$_POST['table'];
			
			if($for_action=='active'){
				$data = array($column  =>  '1');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
			    $_SESSION['msg']="13";
			}else{
				$data = array($column  =>  '0');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
			    $_SESSION['msg']="14";
			}
			
			//$_SESSION['msg']="21";
	      	$response['status']=1;
	      	echo json_encode($response);
			break;
		
		case 'multi_delete':
			
			$ids=implode(",", $_POST['id']);

			if($ids==''){
				$ids=$_POST['id'];
			}

			$tbl_nm=$_POST['tbl_nm'];

			if($tbl_nm=='tbl_category'){

				$sql="SELECT * FROM tbl_jobs WHERE `cat_id` IN ($ids)";
				$res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {
					if($row['job_image']!="")
					{
						unlink('images/'.$row['job_image']);
						unlink('images/thumbs/'.$row['job_image']);
					}

				}

				$deleteSql="DELETE FROM tbl_jobs WHERE `cat_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$sql="SELECT * FROM $tbl_nm WHERE `cid` IN ($ids)";
				$res=mysqli_query($mysqli, $sql);
				while ($row=mysqli_fetch_assoc($res)){
					if($row['category_image']!="")
					{
						unlink('images/'.$row['category_image']);
						unlink('images/thumbs/'.$row['category_image']);
					}

				}

				$deleteSql="DELETE FROM $tbl_nm WHERE `cid` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);
			}

			else if($tbl_nm=='tbl_jobs'){

				$sql="SELECT * FROM tbl_jobs WHERE `id` IN ($ids)";
				$res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {
					if($row['job_image']!="")
					{
						unlink('images/'.$row['job_image']);
						unlink('images/thumbs/'.$row['job_image']);
					}

				}

				$deleteSql="DELETE FROM tbl_jobs WHERE `id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_recent WHERE `job_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_saved WHERE `job_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_apply WHERE `job_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

			}

			else if($tbl_nm=='tbl_users'){
                 
                 $sql="SELECT * FROM tbl_users WHERE `id` IN ($ids)";
				 $res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {
					if($row['user_image']!="")
					{
						unlink('images/'.$row['user_image']);
					}
					if($row['user_resume']!="")
			            {
			           unlink('uploads/'.$row['user_resume']);
			            }

				}

				$sql="DELETE FROM tbl_users WHERE `id` IN ($ids)";
				mysqli_query($mysqli, $sql);

				$deleteSql="DELETE FROM tbl_saved WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_apply WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_recent WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_jobs WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_company WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM tbl_advertisements_request WHERE `user_id` IN ($ids)";

				mysqli_query($mysqli, $deleteSql);

			}
			else if($tbl_nm=='tbl_city'){
                 
                $sql="SELECT * FROM tbl_city WHERE `c_id` IN ($ids)";
				$res=mysqli_query($mysqli, $sql);

				$sql="DELETE FROM tbl_city WHERE `c_id` IN ($ids)";
				mysqli_query($mysqli, $sql);

				$deleteSql="DELETE FROM tbl_jobs WHERE `city_id` IN ($ids)";
				mysqli_query($mysqli, $deleteSql);

			}
			else if($tbl_nm=='tbl_saved'){
                 
                $sql="SELECT * FROM tbl_saved WHERE `sa_id` IN ($ids)";
				$res=mysqli_query($mysqli, $sql);

				$sql="DELETE FROM tbl_saved WHERE `sa_id` IN ($ids)";
				mysqli_query($mysqli, $sql);

			}
			
			$_SESSION['msg']="12";
			
	      	$response['status']=1;
	      	echo json_encode($response);
			break;

			case 'removeCompany':

			$ids=is_array($_POST['ids']) ? implode(',', $_POST['ids']) : $_POST['ids'];
			
			$sqlDelete="DELETE FROM tbl_company WHERE `id` IN ($ids)";
			
			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
			
			
			$response['status']=1;	
			$_SESSION['msg']="12";
	      	echo json_encode($response);
			break;

			case 'removeContact':
				
			$ids=is_array($_POST['ids']) ? implode(',', $_POST['ids']) : $_POST['ids'];

			$sqlDelete="DELETE FROM tbl_contact WHERE `id` IN ($ids)";
			
			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
			
			
			$response['status']=1;	
			$_SESSION['msg']="12";
	      	echo json_encode($response);
			break;

			case 'removeapply':
			
			$ids=is_array($_POST['ids']) ? implode(',', $_POST['ids']) : $_POST['ids'];
			
			$sqlDelete="DELETE FROM tbl_apply WHERE `ap_id` IN ($ids)";
			
			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
			
			
			$response['status']=1;	
			$_SESSION['msg']="21";
	      	echo json_encode($response);
			break;

			case 'removebuyads':
			
			$ids=is_array($_POST['ids']) ? implode(',', $_POST['ids']) : $_POST['ids'];

			$sql="SELECT * FROM tbl_advertisements_request WHERE `a_id` IN ($ids)";
			$res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {
					if($row['ads_image']!="")
					{
						unlink('../assets/img/'.$row['ads_image']);
					}
					if($row['ads_image_sidebar']!="")
			            {
			           unlink('../assets/img/'.$row['ads_image_sidebar']);
			        }
			    }    
			$sqlDelete="DELETE FROM tbl_advertisements_request WHERE `a_id` IN ($ids)";
			
			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
			
			$response['status']=1;	
			$_SESSION['msg']="12";
	      	echo json_encode($response);
			break;
			
		default:
			# code...
			break;
	}
