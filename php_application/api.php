<?php 
      include("includes/connection.php");
 	  include("includes/function.php");
 	  include("language/app_language.php"); 	
	  include("smtp_email.php");
	
    error_reporting(0);

    date_default_timezone_set("Asia/Kolkata");

    $file_path = getBaseUrl();
    
	define("PACKAGE_NAME",$settings_details['package_name']);
    define("HOME_LIMIT",$settings_details['api_home_limit']);
	define("API_PAGE_LIMIT",$settings_details['api_page_limit']);

	function get_user_status($user_id)
	{
		global $mysqli;

		$user_qry="SELECT * FROM tbl_users where id='".$user_id."'";
		$user_result=mysqli_query($mysqli,$user_qry);
		$user_row=mysqli_fetch_assoc($user_result);

		if(mysqli_num_rows($user_result) > 0){
			if($user_row['status']==0){
				return 'false';
			}
			else if($user_row['status']==1){
				return 'true';
			}
		}
		else{
			return 'false';
		}

		
	}

   

	function generateRandomPassword($length = 10) {

	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

    function apply_job_count($user_id)
	{ 
    
	global $mysqli;   

    $qry_applied="SELECT COUNT(*) as num FROM tbl_apply WHERE `user_id`='".$user_id."'";
     
    $total_applied = mysqli_fetch_array(mysqli_query($mysqli,$qry_applied));
    $total_applied = $total_applied['num'];
     
    return $total_applied;

  }

  function get_saved_info($user_id,$job_id) 
	 {
	 	
	 	global $mysqli;

	    $sql="SELECT * FROM tbl_saved WHERE tbl_saved.`user_id`='$user_id' AND tbl_saved.`job_id`='$job_id'";
	 	$res=mysqli_query($mysqli,$sql);
 				 	
         return ($res->num_rows == 1) ? 'true' : 'false';
	  } 


   function saved_job_count($user_id) 
	 {
	 	global $mysqli;

        $qry_saved="SELECT COUNT(*) as num FROM tbl_saved WHERE `user_id` ='".$user_id."' ";

		$total_saved = mysqli_fetch_array(mysqli_query($mysqli,$qry_saved));
		$total_saved = $total_saved['num'];

		return $total_saved;
	 }

   function get_job_info($job_id,$field_name) 
	 {
	 	global $mysqli;

	 	$qry_job="SELECT * FROM tbl_jobs WHERE `id`='".$job_id."'";
	 	$query1=mysqli_query($mysqli,$qry_job);
		$row_job = mysqli_fetch_array($query1);
        
            $num_rows1 = mysqli_num_rows($query1);
 		
            if ($num_rows1 > 0)
		    {		 	
				return $row_job[$field_name];
			}
			else
			{
				return "";
			}
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

    function get_apply_count($job_id)
    {
      global $mysqli;

      $qry_apply="SELECT COUNT(*) as num FROM tbl_apply WHERE `job_id`='".$job_id."'";
      $total_apply= mysqli_fetch_array(mysqli_query($mysqli,$qry_apply));
      $total_apply = $total_apply['num'];

      if($total_apply)
      {
        return $total_apply;
      }
      else
      {
        return 0;
      }
      
    }
	
   function get_city_name($city_id) 
	 {
	 	global $mysqli;

	 	 $qry_video="SELECT * FROM tbl_city WHERE `c_id`='".$city_id."'";
	 	$query1=mysqli_query($mysqli,$qry_video);
		$row_video = mysqli_fetch_array($query1);

			return $row_video['city_name'];
	 }
	 
	$get_method = checkSignSalt($_POST['data']);	 

  if($get_method['method_name']=="get_home")
  	{
       	$user_id=$get_method['user_id'];

       	$jsonObj3= array();	
   
		$query3="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`status`= 1 AND tbl_category.`status`= 1
		ORDER BY tbl_jobs.`id` DESC LIMIT ".HOME_LIMIT;
      
		$sql3 = mysqli_query($mysqli,$query3)or die(mysqli_error($mysqli));

		while($data3 = mysqli_fetch_assoc($sql3))
		{ 
		    $row3['id'] = $data3['id'];
			$row3['cat_id'] = $data3['cat_id'];
			$row3['city_id'] = $data3['city_id'];
			$row3['job_type'] = $data3['job_type'];
			$row3['job_name'] = $data3['job_name'];
			$row3['job_designation'] = $data3['job_designation'];
			$row3['job_desc'] = $data3['job_desc'];
			$row3['job_salary'] = $data3['job_salary'];
			$row3['job_company_name'] = $data3['job_company_name'];
			$row3['job_company_website'] = $data3['job_company_website'];
			$row3['job_phone_number'] = $data3['job_phone_number'];
			$row3['job_mail'] = $data3['job_mail'];
			$row3['job_vacancy'] = $data3['job_vacancy'];
			$row3['job_address'] = $data3['job_address'];
			$row3['job_qualification'] = $data3['job_qualification'];
			$row3['job_skill'] = $data3['job_skill'];
			$row3['job_experince'] = $data3['job_experince'];
			$row3['job_work_day'] = $data3['job_work_day'];
			$row3['job_work_time'] = $data3['job_work_time'];
			$row3['job_map_latitude'] = $data3['job_map_latitude'];
			$row3['job_map_longitude'] =$data3['job_map_longitude'];
			$row3['job_image'] = $data3['job_image'];
			$row3['job_image'] = $file_path.'images/'.$data3['job_image'];
			$row3['job_image_thumb'] = $file_path.'images/thumbs/'.$data3['job_image'];
			$row3['job_date'] = date('m/d/Y',$data3['job_date']);
 
			$row3['cid'] = $data3['cid'];
			$row3['category_name'] = $data3['category_name'];
		    $row3['category_image'] = $file_path.'images/'.$data3['category_image'];
			$row3['category_image_thumb'] = $file_path.'images/thumbs/'.$data3['category_image'];

			$row3['is_favourite']=get_saved_info($user_id,$data3['id']);

			 
			array_push($jsonObj3,$row3);
		
			}

        $row['latest_job']=$jsonObj3; 

	    $jsonObj1= array();	
   
      	$query1="SELECT * FROM tbl_jobs 
      			 LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` WHERE
	       		 FIND_IN_SET(tbl_jobs.`id`,(SELECT `job_id` FROM tbl_recent WHERE tbl_recent.`user_id` = '".$user_id."')) AND tbl_jobs.`status`= 1 AND tbl_category.`status`= 1 ORDER BY tbl_jobs.`id` DESC LIMIT ".HOME_LIMIT;
		$sql1 = mysqli_query($mysqli,$query1)or die(mysqli_error($mysqli));

		while($data1 = mysqli_fetch_assoc($sql1))
		{ 
		    $row1['id'] = $data1['id'];
			$row1['cat_id'] = $data1['cat_id'];
			$row1['city_id'] = $data1['city_id'];
			$row1['job_type'] = $data1['job_type'];
			$row1['job_name'] = $data1['job_name'];
			$row1['job_designation'] = $data1['job_designation'];
			$row1['job_desc'] = $data1['job_desc'];
			$row1['job_salary'] = $data1['job_salary'];
			$row1['job_company_name'] = $data1['job_company_name'];
			$row1['job_company_website'] = $data1['job_company_website'];
			$row1['job_phone_number'] = $data1['job_phone_number'];
			$row1['job_mail'] = $data1['job_mail'];
			$row1['job_vacancy'] = $data1['job_vacancy'];
			$row1['job_address'] = $data1['job_address'];
			$row1['job_qualification'] = $data1['job_qualification'];
			$row1['job_skill'] = $data1['job_skill'];
			$row1['job_experince'] = $data1['job_experince'];
			$row1['job_work_day'] = $data1['job_work_day'];
			$row1['job_work_time'] = $data1['job_work_time'];
			$row1['job_map_latitude'] = $data1['job_map_latitude'];
			$row1['job_map_longitude'] =$data1['job_map_longitude'];
			$row1['job_image'] = $data1['job_image'];
			$row1['job_image'] = $file_path.'images/'.$data1['job_image'];
			$row1['job_image_thumb'] = $file_path.'images/thumbs/'.$data1['job_image'];
			$row1['job_date'] = date('m/d/Y',$data1['job_date']);
 
			$row1['cid'] = $data1['cid'];
			$row1['category_name'] = $data1['category_name'];
		    $row1['category_image'] = $file_path.'images/'.$data1['category_image'];
			$row1['category_image_thumb'] = $file_path.'images/thumbs/'.$data1['category_image'];

		    $row1['is_favourite']=get_saved_info($user_id,$data1['id']);

			array_push($jsonObj1,$row1);
		
			}

        $row['recent_job']=$jsonObj1; 

		$jsonObj_2= array();	

        $cid=API_CAT_ORDER_BY;

	    $query2="SELECT `cid`,`category_name`,`category_image` FROM tbl_category ORDER BY tbl_category.".$cid." DESC LIMIT 5";
		$sql2 = mysqli_query($mysqli,$query2)or die(mysqli_error($mysqli));

		while($data2 = mysqli_fetch_assoc($sql2))
		{
			$row2['cid'] = $data2['cid'];
			$row2['category_name'] = $data2['category_name'];
			$row2['category_image'] = $file_path.'images/'.$data2['category_image'];
			$row2['category_image_thumb'] = $file_path.'images/thumbs/'.$data2['category_image'];

			array_push($jsonObj_2,$row2);
		
		}
            $row['cat_list']=$jsonObj_2; 

		    $set['JOBS_APP'] = $row;
			
		  header( 'Content-Type: application/json; charset=utf-8' );
	      echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		  die();
  }	 

  else if($get_method['method_name']=="get_category")
 	{
 	    if(isset($get_method['page']))
 	     
 	      {
 	        $query_rec = "SELECT COUNT(*) as num FROM tbl_category WHERE `status`= 1";
    		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
    		
    		$page_limit=API_PAGE_LIMIT;
    			
    		$limit=($get_method['page']-1) * $page_limit;
     	    
     		$jsonObj= array();
    		
    		$cat_order=API_CAT_ORDER_BY;
    
    		$query="SELECT cid,category_name,category_image FROM tbl_category 
    		WHERE `status`=1 ORDER BY tbl_category.".$cat_order." LIMIT $limit, $page_limit";
    		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli)); 
    		
    		$total_item =$total_pages['num'];
 	        
 	    }
 	    else
 	    {
 	        $jsonObj= array();
    		
    		$cat_order=API_CAT_ORDER_BY;
    
    		$query="SELECT `cid`,`category_name`,`category_image` FROM tbl_category WHERE `status`=1 ORDER BY tbl_category.".$cat_order."";
    		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));     
    		
    		$total_item =0;
 	    }
 	    
		while($data = mysqli_fetch_assoc($sql))
		{
            $row['total_item'] = $total_item;
			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
 
			array_push($jsonObj,$row);
		
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
 	else if($get_method['method_name']=="get_city")
 	{
 		$jsonObj= array();
		
		$query="SELECT `c_id`,`city_name` FROM tbl_city WHERE tbl_city.`status`=1 ORDER BY tbl_city.`c_id` DESC";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['c_id'] = $data['c_id'];
			$row['city_name'] = $data['city_name'];
 
			array_push($jsonObj,$row);
		
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}

  	else if($get_method['method_name']=="get_list")
  	{	
       	
       	$jsonObj0= array();
		
		$query0="SELECT * FROM tbl_city WHERE tbl_city.`status`=1 ORDER BY tbl_city.`c_id` DESC";
		$sql0 = mysqli_query($mysqli,$query0)or die(mysqli_error($mysqli));

		while($data0 = mysqli_fetch_assoc($sql0))
		{
			 
			$row0['c_id'] = $data0['c_id'];
			$row0['city_name'] = $data0['city_name'];
 
			array_push($jsonObj0,$row0);
		
		}

        $row['city_list']=$jsonObj0; 

	    $jsonObj1= array();
		
		$query1="SELECT * FROM tbl_jobs WHERE tbl_jobs.`status`=1
		        ORDER BY tbl_jobs.`id` DESC ";

		$sql1 = mysqli_query($mysqli,$query1)or die(mysql_error($mysqli));
		
		while($data1 = mysqli_fetch_assoc($sql1))
		{	
			$row1['id'] = $data1['id'];
			$row1['job_company_name'] = $data1['job_company_name'];

			array_push($jsonObj1,$row1);
		
		}

        $row['company_list']=$jsonObj1; 

		$jsonObj_2= array();	

        $cid=API_CAT_ORDER_BY;

	    $query2="SELECT * FROM tbl_category ORDER BY tbl_category.".$cid." DESC";
		$sql2 = mysqli_query($mysqli,$query2)or die(mysqli_error($mysqli));

		while($data2 = mysqli_fetch_assoc($sql2))
		{
			$row2['cid'] = $data2['cid'];
			$row2['category_name'] = $data2['category_name'];
			$row2['category_image'] = $file_path.'images/'.$data2['category_image'];
			$row2['category_image_thumb'] = $file_path.'images/thumbs/'.$data2['category_image'];

			array_push($jsonObj_2,$row2);
		
		}
            $row['cat_list']=$jsonObj_2; 

		    $set['JOBS_APP'] = $row;
			
		  header( 'Content-Type: application/json; charset=utf-8' );
	      echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		  die();
  }	 
 else if($get_method['method_name']=="get_job_by_cat_id")
	{
		$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=$get_method['cat_id'];	

		$query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`cat_id`='".$cat_id."' AND tbl_jobs.`status`=1";
		
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;

		$jsonObj= array();	
	
	    $query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		WHERE tbl_jobs.`cat_id`='".$cat_id."' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`id` ".$post_order_by." LIMIT $limit, $page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
		    $row['total_item'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('m/d/Y',$data['job_date']);
 
			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$row['is_favourite']=get_saved_info($get_method['user_id'],$data['id']);
			 
			array_push($jsonObj,$row);
		
		}
		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
		
	}		 
  else if($get_method['method_name']=="get_latest_job")
	{
		$latest_limit=API_LATEST_LIMIT;
  		$jsonObj= array();

  		$page_limit=API_PAGE_LIMIT;

  		$total_pages=round($latest_limit/$page_limit);
			
		$limit=($get_method['page']-1) * $page_limit;

		$actual_limit=$get_method['page']*$page_limit;

		if($actual_limit <= $latest_limit){
			$page_limit=API_PAGE_LIMIT;
		}
		else if($get_method['page'] <= $total_pages){
			$page_limit=$latest_limit-$page_limit;
		}
		else{
			$page_limit=0;	
		}
		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`status`= 1 ORDER BY tbl_jobs.`id` DESC LIMIT $limit,$page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));
		
		while($data = mysqli_fetch_assoc($sql))
			{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
				
			$row['is_favourite']=get_saved_info($get_method['user_id'],$data['id']);

			array_push($jsonObj,$row);
		
		}
	

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	  }
   else if($get_method['method_name']=="get_recent_job")
		{
	   $user_id=$get_method['user_id'];

	   $query_rec="SELECT COUNT(*) as num FROM tbl_jobs
      			 LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` WHERE
	       		 FIND_IN_SET(tbl_jobs.`id`,(SELECT `job_id` FROM tbl_recent WHERE tbl_recent.`user_id` = '".$user_id."')) AND tbl_jobs.`status`= 1 AND tbl_category.`status`= 1 ORDER BY tbl_jobs.`id`";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));	 

		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
		$jsonObj= array();	
 
		 $query="SELECT * FROM tbl_jobs 
      			 LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` WHERE
	       		 FIND_IN_SET(tbl_jobs.`id`,(SELECT `job_id` FROM tbl_recent WHERE tbl_recent.`user_id` = '".$user_id."')) AND tbl_jobs.`status`= 1 AND tbl_category.`status`= 1 ORDER BY tbl_jobs.`id` DESC LIMIT $limit ,$page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$row['is_favourite']=get_saved_info($user_id,$data['id']);
			
			array_push($jsonObj,$row);
		
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	  }
  else if($get_method['method_name']=="get_search_job")
		{
		
		$job_search=$get_method['search_text'];	
		
		$cat_id=$get_method['cat_id'];	

		$city_id=$get_method['city_id'];

		$job_type=$get_method['job_type'];
		
		$job_company_name=$get_method['job_company_name'];
			
		$jsonObj= array();	
	    
		if($cat_id!='' or  $city_id!='' or  $job_type!='' or  $job_company_name!='')
		{	
		
		 $query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`cat_id` LIKE '%".$cat_id."%' AND tbl_jobs.`city_id`LIKE '%".$city_id."%' AND tbl_jobs.`job_type` LIKE '%".$job_type."%' AND tbl_jobs.`job_company_name` LIKE '%".$job_company_name."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
	    $query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`cat_id` LIKE '%".$cat_id."%' AND tbl_jobs.`city_id` LIKE '%".$city_id."%' AND tbl_jobs.`job_type` LIKE '%".$job_type."%' AND tbl_jobs.`job_company_name` LIKE '%".$job_company_name."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
       
	   	}
        else if($cat_id)
		{		
		
		$query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`cat_id` LIKE '%".$cat_id."%'  AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`cat_id` LIKE '%".$cat_id."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
		
		}
		else if($job_company_name)
		{		
		
		$query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`job_company_name` LIKE '%".$job_company_name."%'  AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`job_company_name` LIKE '%".$job_company_name."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
		
		}
		else if($job_type)
		{	
		
		$query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`job_type` LIKE '%".$job_type."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
	    $query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		WHERE tbl_jobs.`job_type` LIKE '%".$job_type."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
       	
	   	}
        else if($city_id)
		{		
	    
	    $query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`city_id` LIKE '%".$city_id."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
				
		$limit=($get_method['page']-1) * $page_limit;
	   	
	    $query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`city_id` LIKE '%".$city_id."%' AND tbl_jobs.`job_name` LIKE '%".$job_search."%' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
		
		}
	    else 
	    {
        
        $query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`status`=1 AND tbl_jobs.`job_name` LIKE '%".$job_search."%'";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
        	
		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		LEFT JOIN tbl_city ON tbl_jobs.`city_id`= tbl_city.`c_id`
		WHERE tbl_jobs.`status`=1 AND tbl_jobs.`job_name` LIKE '%".$job_search."%' ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";
       	
		}
	   	
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
		    $row['total_item'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('m/d/Y',$data['job_date']);
 
			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$row['is_favourite']=get_saved_info($get_method['user_id'],$data['id']);

			array_push($jsonObj,$row);
		
		}
 		
		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

		
	}
	else if($get_method['method_name']=="search_by_keyword")
	{
		
		$job_search=$get_method['search_text'];	
		
		$query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`status`=1 AND tbl_jobs.`job_name` LIKE '%".$job_search."%'";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		
		$jsonObj= array();	  		 

		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`status`=1 AND tbl_jobs.`job_name` LIKE '%".$job_search."%' ORDER BY tbl_jobs.`job_name` LIMIT $limit, $page_limit";       	
		 
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
		    $row['total_item'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);
 
			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

			$row['is_favourite']=get_saved_info($get_method['user_id'],$data['id']);
			 

			array_push($jsonObj,$row);
		
		}
 
		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
		
	}	 
  else if($get_method['method_name']=="get_single_job")
	{
		$user_id=$get_method['user_id'];
		$job_id=$get_method['job_id'];

		$jsonObj= array();	

		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		WHERE tbl_jobs.`id`='".$job_id."'";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['city_name'] =get_city_name($data['city_id']);
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 
   
			array_push($jsonObj,$row);
		
		}

		 	$qry_saved = "SELECT * FROM tbl_saved WHERE `user_id`= '".$user_id."' AND `job_id` = '".$job_id."'"; 
			$result_saved = mysqli_query($mysqli,$qry_saved);
			$num_rows = mysqli_num_rows($result_saved);
			//$row = mysqli_fetch_assoc($result_saved);	 

			if($num_rows > 0)
			{
				$set['already_saved'] = 'true';
			}
			else
			{
				$set['already_saved'] = 'false';
			}
			
			$sql_recent="SELECT * FROM tbl_recent WHERE `user_id`= '".$user_id."'";
            $res_recent=mysqli_query($mysqli, $sql_recent);
            $num_rows = mysqli_num_rows($res_recent);

                if ($num_rows == 0 && $user_id !='')
					{

                    $data_log = array(
                        'user_id'  =>  $user_id,
                        'job_id'  =>$row['id'],
                        'recent_date' => date('Y-m-d')
                    );
                    
                    $qry = Insert('tbl_recent',$data_log);
                    
                }
                else{
                   $recent_row = mysqli_fetch_assoc($res_recent);

				   $job_id =explode(',', $recent_row['job_id']); 

				   if(!in_array($row['id'],$job_id)){

			    		$data1= array( 
						    'job_id'  => $recent_row['job_id'].','.$row['id']
						);		

    			$qry=Update('tbl_recent', $data1, "WHERE id = '".$recent_row['id']."'");

     			}
             }

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
 

	}	
	else if($get_method['method_name']=="get_similar_jobs")
	{

		//Get cat id using job id
		$query_job="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid`
		WHERE tbl_jobs.`id`='".$get_method['job_id']."' AND tbl_jobs.`status`=1";
		$sql_job = mysqli_query($mysqli,$query_job)or die(mysqli_error($mysqli));
		$row_job=mysqli_fetch_assoc($sql_job);
        
        
        $query_rec = "SELECT COUNT(*) as num FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`cat_id`='".$row_job['cat_id']."' AND tbl_jobs.`id` !='".$get_method['job_id']."' AND tbl_jobs.`status`=1";
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;
			
		$limit=($get_method['page']-1) * $page_limit;
		 
		$jsonObj= array();	
 
		$query="SELECT * FROM tbl_jobs
		LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
		WHERE tbl_jobs.`cat_id`='".$row_job['cat_id']."' AND tbl_jobs.`id` !='".$get_method['job_id']."' AND tbl_jobs.`status`=1 ORDER BY tbl_jobs.`id` DESC LIMIT $limit, $page_limit";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
		    $row['total_item'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];

		    $row['is_favourite']=get_saved_info($get_method['user_id'],$data['id']);

			array_push($jsonObj,$row);
		
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	  }	
  else if($get_method['method_name']=="apply_job_add")
	{
	
	$apply_user_id=$get_method['apply_user_id'];
	$apply_job_id=$get_method['apply_job_id'];

	$qry = "SELECT * FROM tbl_apply WHERE `user_id` = '".$apply_user_id."' AND `job_id` = '".$apply_job_id."'"; 
	$result = mysqli_query($mysqli,$qry);
	$num_rows = mysqli_num_rows($result);
	$row = mysqli_fetch_assoc($result);	

	$user_qry="SELECT * FROM tbl_users WHERE `id` = '".$apply_user_id."'";
	$user_result=mysqli_query($mysqli,$user_qry);
	$user_row=mysqli_fetch_assoc($user_result);

	$qry_job="SELECT * FROM tbl_jobs WHERE `id`='".$apply_job_id."'";
	$job_result=mysqli_query($mysqli,$qry_job);
	$job_row=mysqli_fetch_assoc($job_result);

	

	if($num_rows==0){	
			if($user_row['user_resume']!=''){
			//Insert data
			$data_apply = array( 
					    'user_id'  =>  $apply_user_id,
					    'job_id'  =>  $apply_job_id,
					    'apply_date' => date('Y-m-d H:i:s')
					    );		

		 	$qry_apply = Insert('tbl_apply',$data_apply);

 				
				
		 			if(get_user_info($job_row['user_id'],'email')!='')
					{	
						
		 				$to = (get_user_info($job_row['user_id'],'email'));
						$recipient_name=$job_row['job_name'];

						$path='uploads/'.$user_row['user_resume'];     
		    
					    $user_resume=rand(0,99999)."_".str_replace(" ", "-", $_FILES['user_resume']['name']);
					    $tmp = $_FILES['user_resume']['tmp_name'];
					    move_uploaded_file($tmp, $path.$user_resume);
					    $icon_path = 'uploads/'.$user_row['user_resume'];

						// subject
						$subject = '[IMPORTANT] '.APP_NAME.'  New apply details';
		 			
						$message='<div style="background-color: #f9f9f9;" align="center"><br />
							  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
							    <tbody>
							     <tr>
							        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.$file_path.'images/'.APP_LOGO.'" alt="header" width="120"/></td>
							      </tr>
							      <tr>
							        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
							          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
							            <tbody>
							              <tr>
							                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
							                    <tbody>
							                      <tr>
							                        <td>
							                        <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500; margin-bottom:0px;"> 
							                           <h1> '.$job_row['job_name'].'</h1></p>
							                         <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                          '.$job_row['job_company_name'].'</p>

							                        <h4 style="color: #262626; font-size: 18px; margin-top:0px;">Apply User Details</h4>
							                        <hr>
							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                            Name: '.$user_row['name'].'</p>
							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                            Email: '.$user_row['email'].'</p>
							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                            Phone: '.$user_row['phone'].'</p>
							                           
							                        </td>
							                      </tr>
							                    </tbody>
							                  </table>
							                </td>
							              </tr>
							            </tbody>
							          </table>
							        </td>
							      </tr>
							      <tr>
							        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
							      </tr>
							    </tbody>
							  </table>
							</div>';
							
					    send_email($to,$recipient_name,$subject,$message,$icon_path);

					    $to1 = $user_row['email'];
						$recipient_name1=$user_row['name'];
						// subject
						$subject1 = $subject = '[IMPORTANT] '.APP_NAME.'  Job Information';
		 			
						$message1='<div style="background-color: #f9f9f9;" align="center"><br />
							  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
							    <tbody>
							      <tr>
							        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.$file_path.'images/'.APP_LOGO.'" alt="header" width="120"/></td>
							      </tr>
							      <tr>
							        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
							          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
							            <tbody>
							              <tr>
							                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
							                    <tbody>
							                      <tr>
							                        <td>
							                        <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500; margin-bottom:0px;"> 
							                           <h1> '.$job_row['job_name'].'</h1></p>
							                         <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                          '.$job_row['job_company_name'].'</p>

							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                            '.$job_row['job_type'].'</p>
							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                           '.$job_row['job_address'].'</p>
							                          <p style="color:#262626; font-size:16px; line-height:10px;font-weight:500;"> 
							                            '.$job_row['job_company_website'].'</p>

							                          <h4 style="color: #262626; font-size: 18px; margin-top:0px;">You have successfully Apply</h4>
							                        </td>
							                      </tr>
							                    </tbody>
							                  </table>
							                </td>
							              </tr>
							            </tbody>
							          </table>
							        </td>
							      </tr>
							      <tr>
							        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
							      </tr>
							    </tbody>
							  </table>
							</div>';

					    send_email($to1,$recipient_name1,$subject1,$message1);


					$set['JOBS_APP'][]=array('msg' => $app_lang['job_applied'],'success'=>'1');
					
				  }
				}else{

						$set['JOBS_APP'][]=array('msg' => $app_lang['upload_resume'],'status' => -1,'success'=>'0');
					}
				}
				else{
					$set['JOBS_APP'][]=array('msg' => $app_lang['already_applied'],'success'=>'0');
					
				}
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
 

	}	
  else if($get_method['method_name']=="saved_job_add")
	{
	
	$saved_user_id =$get_method['saved_user_id'];
	$saved_job_id =$get_method['saved_job_id'];

	$sql ="SELECT * FROM tbl_saved WHERE `user_id` = '$saved_user_id' AND `job_id` = '$saved_job_id'"; 
	$res = mysqli_query($mysqli,$sql);
	
	if($res->num_rows == 0){
	
			//Inser data
			$data = array( 
					    'user_id'  =>  $saved_user_id,
					    'job_id'  =>  $saved_job_id,
					    'created_at' => date('Y-m-d H:i:s')
					    );		

		 	 $qry_apply = Insert('tbl_saved',$data);
		 
		     $set['JOBS_APP'][]=array('msg' => $app_lang['add_favourite'],'success'=>'1');
		 
		}else{
			// remove to favourite list
			$deleteSql="DELETE FROM tbl_saved WHERE `user_id`='$saved_user_id' AND `job_id`='$saved_job_id'";
			if(mysqli_query($mysqli, $deleteSql)){
				$set['JOBS_APP'][]=array('msg' => $app_lang['remove_favourite'],'success'=>'0');
			}
			else{

				$set['JOBS_APP'][] = array('msg'=>$app_lang['error_msg'],'success'=>'0');
			}
		}
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
 
	}	
  else if($get_method['method_name']=="delete_job")
	{
		  
	
		Delete('tbl_jobs','id='.$get_method['delete_job_id'].''); 

		Delete('tbl_apply','job_id='.$get_method['delete_job_id'].''); 
			 
  				 
  		$set['JOBS_APP'][]=array('msg' => $app_lang['delete_job'],'success'=>'1');
			  
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
 

	}	
    else if($get_method['method_name']=="job_list")
 	{

    $jsonObj= array();

    $page_limit=API_PAGE_LIMIT;

	$limit=($get_method['page']-1) * $page_limit;
		
	$user_id=$get_method['user_id'];  

    $jsonObj= array();  
  
    $query="SELECT * FROM tbl_jobs
           LEFT JOIN tbl_category ON tbl_jobs.`cat_id`= tbl_category.`cid` 
           WHERE tbl_jobs.`user_id`='".$user_id."' AND tbl_jobs.`status`= 1
           ORDER BY tbl_jobs.`id` DESC LIMIT $limit, $page_limit";

    $sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

    while($data = mysqli_fetch_assoc($sql))
    {
            $row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['city_id'] = $data['city_id'];
			$row['job_type'] = $data['job_type'];
			$row['job_name'] = $data['job_name'];
			$row['job_designation'] = $data['job_designation'];
			$row['job_desc'] = $data['job_desc'];
			$row['job_salary'] = $data['job_salary'];
			$row['job_company_name'] = $data['job_company_name'];
			$row['job_company_website'] = $data['job_company_website'];
			$row['job_phone_number'] = $data['job_phone_number'];
			$row['job_mail'] = $data['job_mail'];
			$row['job_vacancy'] = $data['job_vacancy'];
			$row['job_address'] = $data['job_address'];
			$row['job_qualification'] = $data['job_qualification'];
			$row['job_skill'] = $data['job_skill'];
			$row['job_experince'] = $data['job_experince'];
			$row['job_work_day'] = $data['job_work_day'];
			$row['job_work_time'] = $data['job_work_time'];
			$row['job_map_latitude'] = $data['job_map_latitude'];
			$row['job_map_longitude'] =$data['job_map_longitude'];
			$row['job_image'] = $data['job_image'];
			$row['job_image'] = $file_path.'images/'.$data['job_image'];
			$row['job_image_thumb'] = $file_path.'images/thumbs/'.$data['job_image'];
			$row['job_date'] = date('d-m-Y',$data['job_date']);

            $row['job_apply_total'] = get_apply_count($data['id']);
 
	        $row['cid'] = $data['cid'];
	        $row['category_name'] = $data['category_name'];
	        $row['category_image'] = $file_path.'images/'.$data['category_image'];
	        $row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];	

	        $row['is_favourite']=get_saved_info($user_id,$data['id']);

		   array_push($jsonObj,$row);
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	} 
  else if($get_method['method_name']=="user_job_apply_list")
 	{
 		
    $jsonObj= array();  
 
    $query="SELECT * FROM tbl_users
             LEFT JOIN tbl_apply ON tbl_users.`id`= tbl_apply.`user_id` 
             WHERE tbl_apply.`job_id`=".$get_method['apply_job_id']." ORDER BY tbl_users.`id` DESC";

    $sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

    while($data = mysqli_fetch_assoc($sql))
    {
      $row['user_id'] = $data['id'];
      $row['name'] = $data['name'];
      $row['email'] = $data['email'];
      $row['phone'] = $data['phone'];
      $row['city'] = $data['city'];

      if($data['user_image'])
      {
        $user_image=$file_path.'images/'.$data['user_image'];
      } 
      else
      {
        $user_image='';
      }

      $row['user_image'] = $user_image;

      if($data['user_resume'])
      {
        $user_resume=$file_path.'uploads/'.$data['user_resume'];
      } 
      else
      {
        $user_resume='';
      }
  
      $row['user_resume'] = $user_resume;
       
      array_push($jsonObj,$row);
    
     }
 
        $set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}   
   else if($get_method['method_name']=="user_apply_list")
 	{
 	 
 	 $query_rec ="SELECT COUNT(*) as num FROM tbl_apply  
 	 			  WHERE  tbl_apply.`user_id`=".$get_method['user_id']."";
	 $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
	 $page_limit=API_PAGE_LIMIT;
	 $limit=($get_method['page']-1) * $page_limit;   
 		
     $jsonObj= array(); 
     
	 $query="SELECT * FROM tbl_apply  WHERE  tbl_apply.`user_id`=".$get_method['user_id']."
	 		 ORDER BY tbl_apply.`ap_id` DESC LIMIT $limit, $page_limit";
   	 $sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

    while($data = mysqli_fetch_assoc($sql))
    {       
           
            $row['total_item'] = $total_pages['num'];
	        $row['apply_id'] = $data['ap_id'];   			
			$row['user_id'] = $data['user_id'];
            $row['job_id'] =$data['job_id'];
			
			$row['id'] =get_job_info($data['job_id'],'id');
		    $row['cat_id'] =get_job_info($data['job_id'],'cat_id');
		    $row['city_id'] =get_job_info($data['job_id'],'city_id');
		    $row['job_type'] =get_job_info($data['job_id'],'job_type');
		    $row['job_name'] =get_job_info($data['job_id'],'job_name');
		    $row['job_designation'] =get_job_info($data['job_id'],'job_designation');
		    $row['job_desc'] =get_job_info($data['job_id'],'job_desc');
		    $row['job_salary'] =get_job_info($data['job_id'],'job_salary');
		    $row['job_company_name'] =get_job_info($data['job_id'],'job_company_name');
		    $row['job_company_website'] =get_job_info($data['job_id'],'job_company_website');
		    $row['job_phone_number'] =get_job_info($data['job_id'],'job_phone_number');
		    $row['job_mail'] =get_job_info($data['job_id'],'job_mail');
		    $row['job_vacancy'] =get_job_info($data['job_id'],'job_vacancy');
		    $row['job_address'] =get_job_info($data['job_id'],'job_address');
		    $row['job_qualification'] =get_job_info($data['job_id'],'job_qualification');
		    $row['job_skill'] =get_job_info($data['job_id'],'job_skill');
		    $row['job_experince'] = get_job_info($data['job_id'],'job_experince');
			$row['job_work_day'] = get_job_info($data['job_id'],'job_work_day');
			$row['job_work_time'] = get_job_info($data['job_id'],'job_work_time');
			$row['job_map_latitude'] = get_job_info($data['job_id'],'job_map_latitude');
			$row['job_map_longitude'] = get_job_info($data['job_id'],'job_map_longitude');
			$row['job_image'] = $file_path.'images/'.get_job_info($data['job_id'],'job_image');
			$row['job_image_thumb'] =$file_path.'images/thumbs/'.get_job_info($data['job_id'],'job_image');
			$row['job_date'] = date('d-m-Y',get_job_info($data['job_id'],'job_date'));

			$row['apply_date'] = date('Y-m-d',strtotime($data['apply_date']));

              if($data['seen']==1)
		      {
		      	$row['seen'] = 'true';
		      }
		      else
		      {
		      	$row['seen'] = 'false';
		      }
 
		    array_push($jsonObj,$row);
    
    		}
 
         $set['JOBS_APP'] = $jsonObj;
		 
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
 	else if($get_method['method_name']=="user_apply_job_seen")
 	{

 		$data = array('seen'  =>  '1');		
		$edit_status=Update('tbl_apply', $data, "WHERE user_id = '".$get_method['apply_user_id']."' AND job_id = '".$get_method['job_id']."'");

 		$set['JOBS_APP'][]=array('msg' => $app_lang['job_seen'],'success'=>'1');
		 
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
  else if($get_method['method_name']=="user_saved_list")
 	{
 		
    $query_rec = "SELECT COUNT(*) as num FROM tbl_saved  WHERE  tbl_saved.`user_id`=".$get_method['user_id']."";
	$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
	
	$page_limit=API_PAGE_LIMIT;
	$limit=($get_method['page']-1) * $page_limit;
		
    $jsonObj= array();  
 
	$query="SELECT * FROM tbl_saved  WHERE  tbl_saved.`user_id`=".$get_method['user_id']."
		   ORDER BY tbl_saved.`sa_id` DESC LIMIT $limit, $page_limit";
    $sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

    while($data = mysqli_fetch_assoc($sql))
    {
            
            $row['total_item'] = $total_pages['num']; 
            $row['user_id'] = $data['user_id'];
            $row['job_id'] =$data['job_id'];
		    $row['id'] =get_job_info($data['job_id'],'id');
		    $row['cat_id'] =get_job_info($data['job_id'],'cat_id');
		    $row['city_id'] =get_job_info($data['job_id'],'city_id');
		    $row['job_type'] =get_job_info($data['job_id'],'job_type');
		    $row['job_name'] =get_job_info($data['job_id'],'job_name');
		    $row['job_designation'] =get_job_info($data['job_id'],'job_designation');
		    $row['job_desc'] =get_job_info($data['job_id'],'job_desc');
		    $row['job_salary'] =get_job_info($data['job_id'],'job_salary');
		    $row['job_company_name'] =get_job_info($data['job_id'],'job_company_name');
		    $row['job_company_website'] =get_job_info($data['job_id'],'job_company_website');
		    $row['job_phone_number'] =get_job_info($data['job_id'],'job_phone_number');
		    $row['job_mail'] =get_job_info($data['job_id'],'job_mail');
		    $row['job_vacancy'] =get_job_info($data['job_id'],'job_vacancy');
		    $row['job_address'] =get_job_info($data['job_id'],'job_address');
		    $row['job_qualification'] =get_job_info($data['job_id'],'job_qualification');
		    $row['job_skill'] =get_job_info($data['job_id'],'job_skill');
		    $row['job_experince'] = get_job_info($data['job_id'],'job_experince');
			$row['job_work_day'] = get_job_info($data['job_id'],'job_work_day');
			$row['job_work_time'] = get_job_info($data['job_id'],'job_work_time');
			$row['job_map_latitude'] = get_job_info($data['job_id'],'job_map_latitude');
			$row['job_map_longitude'] = get_job_info($data['job_id'],'job_map_longitude');
		    $row['job_image'] =get_job_info($data['job_id'],'job_image');
			$row['job_image'] = $file_path.'images/'.get_job_info($data['job_id'],'job_image');
			$row['job_image_thumb'] =$file_path.'images/thumbs/'.get_job_info($data['job_id'],'job_image');
			$row['job_date'] = date('m/d/Y',get_job_info($data['job_id'],'job_date'));
            
            $row['is_favourite']=get_saved_info($get_method['user_id'],$data['job_id']);

            array_push($jsonObj,$row);
    
    	}
 
        $set['JOBS_APP'] = $jsonObj;
     
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	} 
 else if($get_method['method_name']=="job_add")
 	{
 		
          $job_image=rand(0,99999)."_".$_FILES['job_image']['name'];

	      $ext = pathinfo($_FILES['job_image']['name'], PATHINFO_EXTENSION);

	      $job_image=rand(0,99999).".".$ext;
	      //Main Image
	      $tpath1='images/'.$job_image;   

	      $tmp = $_FILES['job_image']['tmp_name'];
	      move_uploaded_file($tmp, $tpath1);
	   
	      //Thumb Image 
	      $thumbpath='images/thumbs/'.$job_image;   
	      $thumb_pic1=create_thumb_image($tpath1,$thumbpath, '200' ,'200');    
      
       $data = array( 
		 'user_id'  =>  $get_method['user_id'],
		 'cat_id'  =>  $get_method['cat_id'],
		 'city_id'  =>  $get_method['city_id'],
		 'job_type'  =>  $get_method['job_type'],
         'job_name'  =>  addslashes($get_method['job_name']),
         'job_designation'  =>  addslashes($get_method['job_designation']),
         'job_desc'  =>  addslashes($get_method['job_desc']),
         'job_salary'  =>  $get_method['job_salary'],
         'job_company_name'  =>  $get_method['job_company_name'],
         'job_company_website'  =>  $get_method['job_company_website'],
         'job_phone_number'  =>  $get_method['job_phone_number'],
         'job_mail'  =>  $get_method['job_mail'],
         'job_vacancy'  =>  $get_method['job_vacancy'],
         'job_address'  =>  addslashes($get_method['job_address']),
         'job_qualification'  =>  addslashes($get_method['job_qualification']),
         'job_skill'  =>  addslashes($get_method['job_skill']),
         'job_experince'  =>  addslashes($get_method['job_experince']),
         'job_work_day'  =>  addslashes($get_method['job_work_day']),
         'job_work_time'  =>  addslashes($get_method['job_work_time']),
         'job_map_latitude'  =>  addslashes($get_method['job_map_latitude']),
         'job_map_longitude'  =>  addslashes($get_method['job_map_longitude']),
         'job_image'  =>  $job_image,
         'job_date'  =>  strtotime($get_method['job_date']),
         'status' => 0
         
		);		

 		$qry = Insert('tbl_jobs',$data);

        $set['JOBS_APP'][]=array('msg' => $app_lang['add_job'],'success'=>'1');
			  
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}

  else if($get_method['method_name']=="edit_job")
 	{
 		if($_FILES['job_image']['name']!="")
     {

          $job_image=rand(0,99999)."_".$_FILES['job_image']['name'];

	      $ext = pathinfo($_FILES['job_image']['name'], PATHINFO_EXTENSION);

	      $job_image=rand(0,99999).".".$ext;
	      //Main Image
	      $tpath1='images/'.$job_image;   

	      $tmp = $_FILES['job_image']['tmp_name'];
	      move_uploaded_file($tmp, $tpath1);
	   
	      //Thumb Image 
	      $thumbpath='images/thumbs/'.$job_image;   
	      $thumb_pic1=create_thumb_image($tpath1,$thumbpath, '200' ,'200');    
       
       $data = array(
           'user_id'  =>  $get_method['user_id'], 
           'cat_id'  =>  $get_method['cat_id'],
           'city_id'  =>  $get_method['city_id'],
           'job_type'  =>  $get_method['job_type'],
           'job_name'  =>  addslashes($get_method['job_name']),
           'job_designation'  =>  addslashes($get_method['job_designation']),
           'job_desc'  =>  addslashes($get_method['job_desc']),
           'job_salary'  =>  $get_method['job_salary'],
           'job_company_name'  =>  $get_method['job_company_name'],
           'job_company_website'  =>  $get_method['job_company_website'],
           'job_phone_number'  =>  $get_method['job_phone_number'],
           'job_mail'  =>  $get_method['job_mail'],
           'job_vacancy'  =>  $get_method['job_vacancy'],
           'job_address'  =>  addslashes($get_method['job_address']),
           'job_qualification'  =>  addslashes($get_method['job_qualification']),
           'job_skill'  =>  addslashes($get_method['job_skill']),
           'job_experince'  =>  addslashes($get_method['job_experince']),
           'job_work_day'  =>  addslashes($get_method['job_work_day']),
           'job_work_time'  =>  addslashes($get_method['job_work_time']),
           'job_map_latitude'  =>  addslashes($get_method['job_map_latitude']),
           'job_map_longitude'  =>  addslashes($get_method['job_map_longitude']),
           'job_image'  =>  $job_image,
           'job_date'  =>  strtotime($get_method['job_date'])
            ); 

      }
      else
      {
          $data = array( 
           'user_id'  =>  $get_method['user_id'],
           'cat_id'  =>  $get_method['cat_id'],
           'city_id'  =>  $get_method['city_id'],
           'job_type'  =>  $get_method['job_type'],
           'job_name'  =>  addslashes($get_method['job_name']),
           'job_designation'  =>  addslashes($get_method['job_designation']),
           'job_desc'  =>  addslashes($get_method['job_desc']),
           'job_salary'  =>  $get_method['job_salary'],
           'job_company_name'  =>  $get_method['job_company_name'],
           'job_company_website'  =>  $get_method['job_company_website'],
           'job_phone_number'  =>  $get_method['job_phone_number'],
           'job_mail'  =>  $get_method['job_mail'],
           'job_vacancy'  =>  $get_method['job_vacancy'],
           'job_address'  =>  addslashes($get_method['job_address']),
           'job_qualification'  =>  addslashes($get_method['job_qualification']),
           'job_skill'  =>  addslashes($get_method['job_skill']),
           'job_experince'  =>  addslashes($get_method['job_experince']),
           'job_work_day'  =>  addslashes($get_method['job_work_day']),
           'job_work_time'  =>  addslashes($get_method['job_work_time']),
           'job_map_latitude'  =>  addslashes($get_method['job_map_latitude']),
           'job_map_longitude'  =>  addslashes($get_method['job_map_longitude']),
           'job_date'  =>  strtotime($get_method['job_date'])
            ); 
      }

 
	    $job_edit=Update('tbl_jobs', $data, "WHERE id = '".$get_method['job_id']."'");
				  
		$set['JOBS_APP'][]=array('msg' => $app_lang['edit_job'],'success'=>'1');	 
  
		 
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	} 
 	else if ($get_method['method_name']=="get_company_details") {

		$jsonObj= array();	
 		
		$user_id=$get_method['user_id'];

		$query="SELECT * FROM tbl_company  WHERE tbl_company.`user_id`='$user_id' ORDER BY tbl_company.`id` DESC";

		$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

			while($data = mysqli_fetch_assoc($sql))
			{

				$row['id'] = $data['id'];
				$row['company_name'] = $data['company_name'];
				$row['company_email'] = $data['company_email'];
				$row['mobile_no'] = $data['mobile_no'];
				$row['company_address'] = $data['company_address'];
				$row['company_desc'] = $data['company_desc'];
				$row['company_website'] = $data['company_website'];
				$row['company_work_day'] = $data['company_work_day'];
				$row['company_work_time'] = $data['company_work_time'];
				$row['company_logo'] = $file_path.'images/'.$data['company_logo'];

				array_push($jsonObj,$row);
					
			}	
		
		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
		
	}

   else if($get_method['method_name']=="user_register")
	{
		$register_date=strtotime(date('d-m-Y h:i A'));

		$user_type=$get_method['user_type'];

		$name=filter_var($get_method['name'], FILTER_SANITIZE_STRING);
		$email=filter_var($get_method['email'], FILTER_SANITIZE_STRING);
		$password=md5(trim($get_method['password']));
		$phone=filter_var($get_method['phone'], FILTER_SANITIZE_STRING);

	    $qry = "SELECT * FROM tbl_users WHERE `email` = '".$email."'"; 
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
		{
			$set['JOBS_APP'][]=array('msg' => $app_lang['invalid_email_format'],'success'=>'0');
		}
		else if($row['email']!="")
		{
			$set['JOBS_APP'][]=array('msg' => $app_lang['email_exist'],'success'=>'0');
		}
		else{

			$data = array(
 				    'user_type'  => $user_type,
				    'name'  => $name,				    
					'email'  =>  $email,
					'password'  =>  $password,
					'phone'  =>  $phone,
					'register_date'  =>  $register_date,
					'status'  =>  '1'
					);		
 			 	
			$qry = Insert('tbl_users',$data);

			$user_id=mysqli_insert_id($mysqli);

			if($user_type=='2' AND strcmp($get_method['register_as'],'company')==0){

				//registation as company
				$sql="SELECT * FROM tbl_company WHERE `user_id`='$user_id'";
	            $res=mysqli_query($mysqli, $sql);

                $data = array(
			            'user_id'=>$user_id,
				        'company_name'  =>  addslashes(trim($get_method['company_name'])),
				        'company_email'  =>  cleanInput(trim($get_method['company_email'])),
				        'mobile_no'  =>  cleanInput(trim($get_method['mobile_no'])),
				        'company_address'  =>  addslashes(trim($get_method['company_address'])),
				        'company_desc'  =>  addslashes($get_method['company_desc']),
				        'company_work_day'  =>  addslashes(trim($get_method['company_work_day'])),
				        'company_work_time'  => (trim($get_method['company_work_time'])),
				        'company_website'  =>  (trim($get_method['company_website']))
				        
                );

                $qry = Insert('tbl_company',$data);

			}
				$to = $get_method['email'];
				$recipient_name=$get_method['name'];
				// subject

				$subject = str_replace('###', APP_NAME, $app_lang['register_mail_lbl']);

				$message='<div style="background-color: #eee;" align="center"><br />
							  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
							    <tbody>
							      <tr>
							        <td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
							      </tr>
							      <br>
							      <br>
							      <tr>
							        <td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
							          <img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
							        </td>
							      </tr>
							      <tr>
							        <td width="600" valign="top" bgcolor="#FFFFFF">
							          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
							            <tbody>
							              <tr>
							                <td valign="top">
							                  <table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
							                    <tbody>
							                      <tr>
							                        <td>
							                        	<p style="color: #717171; font-size: 24px; margin-top:0px; margin:0 auto; text-align:center;"><strong>'.$app_lang['welcome_lbl'].', '.addslashes(trim($get_method['name'])).'</strong></p>
							                          	<br>
							                          	<p style="color:#15791c; font-size:18px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['normal_register_msg'].'<br /></p>
							                          	<br/>
							                          	<p style="color:#999; font-size:17px; line-height:32px;font-weight:500;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>
							                            </td>
							                      </tr>
							                    </tbody>
							                  </table>
							                </td>
							              </tr>
							            </tbody>
							          </table>
							        </td>
							      </tr>
							      <tr>
							        <td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">'.$app_lang['email_copyright'].' '.APP_NAME.'.</td>
							      </tr>
							    </tbody>
							  </table>
							</div>';

						send_email($to,$recipient_name,$subject,$message);

		   $set['JOBS_APP'][]=array('msg' => $app_lang['register_success'],'success'=>'1');
		 }
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
  else if($get_method['method_name']=="user_login")
	{
		
	    $email = cleanInput($get_method['email']);
 		$password = trim($get_method['password']);

		$qry = "SELECT * FROM tbl_users WHERE `email` = '$email'"; 
		$result = mysqli_query($mysqli,$qry) or die('Error in fetch data ->'.mysqli_error($mysqli));


   		if(mysqli_num_rows($result) > 0){
			$row=mysqli_fetch_assoc($result);	
		       	 if($row['user_image'])
                  {
                    $user_image=$file_path.'images/'.$row['user_image'];
                  } 
                  else
                  {
                    $user_image='';
                  }
				 if($row['status']=='1'){  

				 	if($row['password']==md5($password)){

			        $set['JOBS_APP'][]=array('user_type' => $row['user_type'],'user_id' => $row['id'],'name'=>$row['name'],'user_image'=>$user_image,'success'=>'1');
                  }
              	
                  else
                  {
                     $set['JOBS_APP'][]=array('msg' =>$app_lang['invalid_password'],'success'=>'0');
                  }
		}	
		else{
				// account is deactivated
				$set['JOBS_APP'][]=array('msg' =>$app_lang['account_deactive'],'success'=>'0');
			}
		}		
		else
		{
				 
 				$set['JOBS_APP'][]=array('msg' =>$app_lang['email_not_found'],'success'=>'0');
		}

	    header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
 else if($get_method['method_name']=="user_profile")
	{
		
	$qry = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['id']."'"; 
	$result = mysqli_query($mysqli,$qry);
	$row = mysqli_fetch_assoc($result);

	if($row['user_image'])
	{
		$user_image=$file_path.'images/'.$row['user_image'];
	} 
	else
	{
		$user_image='';
	}
	
	if($row['user_resume'])
	{
		$user_resume=$file_path.'uploads/'.$row['user_resume'];
	} 
	else
	{
		$user_resume='';
	}
    
    $skills=$row['skills']?$row['skills']:'';
  	$gender=$row['gender']?$row['gender']:'';

  	if(is_null($row['date_of_birth'])){

  		$date_of_birth='';

  	}else{

  		$date_of_birth=date('d-m-Y',$row['date_of_birth']);
  	}

	    $qry_company = "SELECT * FROM tbl_company WHERE `user_id` = '".$get_method['id']."'"; 
		$result_company = mysqli_query($mysqli,$qry_company);
		$num_rows = mysqli_num_rows($result_company);
        $row_company = mysqli_fetch_assoc($result_company);

	   if($row_company['company_logo'])
		{
			$company_logo=$file_path.'images/'.$row_company['company_logo'];
		} 
		else
		{
			$company_logo='';
		}

		if($num_rows > 0){
		 	$register_as='company';
		 }else{
		 	$register_as='individual';
		 }
		
		 $company_name=$row_company['company_name']?$row_company['company_name']:'';
		 $company_email=$row_company['company_email']?$row_company['company_email']:'';
		 $mobile_no=$row_company['mobile_no']?$row_company['mobile_no']:'';
		 $company_address=$row_company['company_address']?$row_company['company_address']:'';
		 $company_website=$row_company['company_website']?$row_company['company_website']:'';
		 $company_work_day=$row_company['company_work_day']?$row_company['company_work_day']:'';
	     $company_work_time=$row_company['company_work_time']?$row_company['company_work_time']:'';
	     $company_desc=$row_company['company_desc']?$row_company['company_desc']:'';

        $set['JOBS_APP'][]=array('user_id' => $row['id'],'user_type' => $row['user_type'],'name'=>$row['name'],'email'=>$row['email'],'phone'=>$row['phone'],'date_of_birth'=>$date_of_birth,'gender'=>$gender,'city'=>$row['city'],'address'=>stripslashes($row['address']),'current_company_name'=>stripslashes($row['current_company_name']),'experiences'=>stripslashes($row['experiences']),'skills' => $skills,'user_image'=>$user_image,'user_resume'=>$user_resume,'total_apply_job'=>apply_job_count($row['id']),'total_saved_job'=>saved_job_count($row['id']),'register_as' =>$register_as,'company_name'=>$company_name,'company_email'=>$company_email,'mobile_no' => $mobile_no,'company_address'=>$company_address,'company_desc'=>$company_desc,'company_website'=>$company_website,'company_work_day'=>$company_work_day,'company_work_time'=>$company_work_time,'company_logo'=>$company_logo,'success'=>'1');
		

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
   else if($get_method['method_name']=="user_profile_update")
	{
		$path='';

		$qry = "SELECT * FROM tbl_users WHERE `email` = '".$get_method['email']."'"; 
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		$user_id=$get_method['user_id'];

 	 	if($_FILES['user_image']['name']!='')
         {

          if($row['user_image']!="")
            {
              unlink('images/'.$row['user_image']);
           }

 	     $user_image=rand(0,99999)."_".$_FILES['user_image']['name'];
	 	
	     //Main Image
		 $tpath1='images/'.$user_image; 			 
	     $pic1=compress_image($_FILES["user_image"]["tmp_name"], $tpath1, 80);
	     $path=$file_path.$tpath1;
		 }
		 else
		 {
		 	$user_image='';
		 }

		 if($_FILES['user_resume']['name']!='')
         {

          $img_res1=mysqli_query($mysqli,'SELECT * FROM tbl_users WHERE `id`='.$user_id.'');
          $img_res_row1=mysqli_fetch_assoc($img_res1);

          if($img_res_row1['user_resume']!="")
            {
              unlink('uploads/'.$img_res_row1['user_resume']);
           }
 	     $user_resume=rand(0,99999)."_".$_FILES['user_resume']['name'];
	 	
	     //Main Image
		 $tpath1='uploads/'.$user_resume; 			 
	      move_uploaded_file($_FILES["user_resume"]["tmp_name"],"uploads/" . $user_resume);
			
		 }
		 else
		 {
		 	$user_resume=$row['user_resume'];
		 } 


 	 	if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
		{
			$set['JOBS_APP'][]=array('msg' => $app_lang['invalid_email_format'],'success'=>'0');

			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);
			echo $json;
			 exit;
		}
		else if($row['email']==$get_method['email'] AND $row['id']!=$user_id)
		{
			$set['JOBS_APP'][]=array('msg' => $app_lang['email_exist'],'success'=>'0');

			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);
			echo $json;
			 exit;
		}
 	 	else if($get_method['password']!="")
		{
			$data = array(
 			'name'  =>  $get_method['name'],
			'email'  =>  $get_method['email'],
			'phone'  =>  $get_method['phone'],
			'city'  =>  $get_method['city'],
			'address'  =>  addslashes($get_method['address']),
			'user_image' => $user_image,
			'user_resume' => $user_resume,
			'current_company_name'  =>  addslashes($get_method['current_company_name']),
	        'experiences'  =>  addslashes($get_method['experiences']),
	        'skills'  =>  $get_method['skills'],
	        'gender'  =>  $get_method['gender'],
	        'date_of_birth'  => strtotime($get_method['date_of_birth'])
			);
		}
		else
		{
			$data = array(
 			'name'  =>  $get_method['name'],
			'email'  =>  $get_method['email'],			 
			'phone'  =>  $get_method['phone'],
			'city'  =>  $get_method['city'],
			'address'  =>  addslashes($get_method['address']),
			'user_image' => $user_image,
			'user_resume' => $user_resume,
			'current_company_name'  =>  addslashes($get_method['current_company_name']),
	        'experiences'  =>  addslashes($get_method['experiences']),
	        'skills'  =>  $get_method['skills'],
	        'gender'  =>  $get_method['gender'],
	        'date_of_birth'  => strtotime($get_method['date_of_birth'])
			);
		}
		
	    if($get_method['password']!=""){
				$data = array_merge($data, array("password" => md5(trim($get_method['password']))));
			}
 	
	  	 $user_edit=Update('tbl_users', $data, "WHERE `id` = '".$user_id."'");

	  	  if($_FILES['company_logo']['name']!="")
    		{

	    	$img_res_company=mysqli_query($mysqli,'SELECT * FROM tbl_company WHERE `id`='.$user_id.'');
	        $img_res_row_company=mysqli_fetch_assoc($img_res_company);

	          if($img_res_row_company['company_logo']!="")
	            {
	              unlink('images/'.$img_res_row_company['company_logo']);
	           }

		    $ext = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);

			$company_logo=rand(0,99999).".".$ext;

			//Main Image
			$tpath='images/'.$company_logo;

			if($ext!='png') {
			$pic1=compress_image($_FILES["company_logo"]["tmp_name"], $tpath, 80);
			}
			else{
			$tmp = $_FILES['company_logo']['tmp_name'];
			move_uploaded_file($tmp, $tpath);
			} 
			           
	       $data = array(
		        'company_name'  =>  addslashes(trim($get_method['company_name'])),
		        'company_email'  =>  $get_method['company_email'],
		        'mobile_no'  =>  $get_method['mobile_no'],
		        'company_address'  =>  addslashes($get_method['company_address']),
		        'company_desc'  =>  addslashes($get_method['company_desc']),
		        'company_website'  =>  $get_method['company_website'],
		         'company_work_day'  =>  addslashes(trim($get_method['company_work_day'])),
		        'company_work_time'  => $get_method['company_work_time'],
		        'company_logo' => $company_logo

	             );
	   
	   }else{
	     
	     	$data = array(
		        'company_name'  =>  addslashes(trim($get_method['company_name'])),
		        'company_email'  =>  $get_method['company_email'],
		        'mobile_no'  =>  $get_method['mobile_no'],
		        'company_address'  =>  addslashes($get_method['company_address']),
		        'company_desc'  =>  addslashes($get_method['company_desc']),
		        'company_work_day'  =>  addslashes(trim($get_method['company_work_day'])),
		        'company_work_time'  => $get_method['company_work_time'],
		        'company_website'  =>  $get_method['company_website']
	        	 );
	      	 }
	        
	        $user_edit=Update('tbl_company', $data, " WHERE `user_id` = '".$user_id."'");
	       		 
			$set['JOBS_APP'][]=array('user_image'=>$path,'msg'=>$app_lang['update_success'],'success'=>'1');

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	
	}
	
	
 else if($get_method['method_name']=="forgot_pass")	
	{
		
		$email=htmlentities(trim($get_method['email']));
	 	 
		$qry = "SELECT * FROM tbl_users WHERE `email` = '$email'"; 
		$result = mysqli_query($mysqli,$qry);
		$row = mysqli_fetch_assoc($result);

		if($result->num_rows > 0)
		{
 			$password=generateRandomPassword(7);

			$new_password=md5($password);

			$to = $row['email'];
			$recipient_name=$row['name'];
			// subject
			$subject = '[IMPORTANT] '.APP_NAME.' Forgot Password Information';
 			
			$message='<div style="background-color: #f9f9f9;" align="center"><br />
					  <table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
					    <tbody>
					      <tr>
					        <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.$file_path.'images/'.APP_LOGO.'" alt="header" width="120"/></td>
					      </tr>
					      <tr>
					        <td width="600" valign="top" bgcolor="#FFFFFF"><br>
					          <table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					            <tbody>
					              <tr>
					                <td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
					                    <tbody>
					                      <tr>
					                        <td><p style="color: #262626; font-size: 28px; margin-top:0px;"><strong>Dear '.$row['name'].'</strong></p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;">Thank you for using '.APP_NAME.',<br>
					                            Your password is: '.$password.'</p>
					                          <p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px;">Thanks you,<br />
					                            '.APP_NAME.'.</p></td>
					                      </tr>
					                    </tbody>
					                  </table></td>
					              </tr>
					               
					            </tbody>
					          </table></td>
					      </tr>
					      <tr>
					        <td style="color: #262626; padding: 20px 0; font-size: 20px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">Copyright © '.APP_NAME.'.</td>
					      </tr>
					    </tbody>
					  </table>
					</div>';
					
			send_email($to,$recipient_name,$subject,$message);

			$sql="UPDATE tbl_users SET `password`='$new_password' WHERE `id`='".$row['id']."'";
			      	mysqli_query($mysqli,$sql);
 			  
			$set=array('msg' => $app_lang['password_sent_mail'],'success'=>'1');
		}
		else
		{  	 	
			$set=array('msg' => $app_lang['email_not_found'],'success'=>'0');		
		}
	
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
	}
	 else if($get_method['method_name']=="get_app_update")		
		{
			  
			$jsonObj= array();	

			$query="SELECT * FROM tbl_settings WHERE id='1'";
			$sql = mysqli_query($mysqli,$query);

			$data = mysqli_fetch_assoc($sql);

			$row['update_status'] = $data['update_status'];
			$row['cancel_status'] = $data['cancel_status'];
			$row['new_app_version'] = $data['new_app_version'];
			$row['app_link'] = $data['app_link'];
			$row['app_update_desc'] = $data['app_update_desc'];
			
			array_push($jsonObj,$row);
			
			$set['JOBS_APP'] = $jsonObj;
				
			header( 'Content-Type: application/json; charset=utf-8' );
		    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();	
		}
   		
    else if($get_method['method_name']=="get_app_details")
	    {
	    //User status
	    $qry_user = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['user_id']."'";
        $result_user = mysqli_query($mysqli,$qry_user);
        $num_rows = mysqli_num_rows($result_user);
        $row1 = mysqli_fetch_assoc($result_user);
        		
        if ($num_rows > 0)
        { 
        		if($row1['status']=='1')
                {
                	$set['user_status'] ='true';	
                }
                else
                {
                	$set['user_status'] ='false';	
                } 	
        }
        else
        {
        	$set['user_status'] ='false';
        }
	    	
	    //App settings 
		$jsonObj= array();	

		$query="SELECT * FROM tbl_settings WHERE `id`='1'";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		$row['package_name'] = $settings_details['package_name']; 

		while($data = mysqli_fetch_assoc($sql))
		{
		    
			$row['app_name'] = $data['app_name'];
			$row['app_logo'] = $data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = $data['app_description'];
 			$row['app_developed_by'] = $data['app_developed_by'];
 			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);
 			
 			$row['publisher_id'] = $data['publisher_id'];
 			$row['interstital_ad'] = $data['interstital_ad'];
 			$row['interstital_ad_type'] = $data['interstital_ad_type'];

			$row['interstital_ad_id'] = ($data['interstital_ad_type']=='facebook') ? $data['interstital_facebook_id'] : $data['interstital_ad_id'];

			$row['interstital_ad_click'] = $data['interstital_ad_click'];

 			$row['banner_ad'] = $data['banner_ad'];
 			$row['banner_ad_type'] = $data['banner_ad_type'];

 			$row['banner_ad_id'] = ($data['banner_ad_type']=='facebook') ? $data['banner_facebook_id'] : $data['banner_ad_id'];
 			

 			$row['update_status'] = $data['update_status'];
			$row['cancel_status'] = $data['cancel_status'];
			$row['new_app_version'] = $data['new_app_version'];
			$row['app_link'] = $data['app_link'];
			$row['app_update_desc'] = $data['app_update_desc'];

			array_push($jsonObj,$row);
		
		}

		$set['JOBS_APP'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
	}		
	 else
  {
  		$get_method = checkSignSalt($_POST['data']);
  }

