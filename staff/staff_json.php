<?php
session_start();
if(isset($_SESSION['user'])){
	  $role=$_SESSION['role'];
	  $user=$_SESSION['user'];
	  $id=$_POST['id'];
  }
  else{
	  header("Location:index.php");
  }
   
include('connection.php');
$year=date('Y');
$month=date('m');
    $sql="select * from staff where staff_id='$id'";
   $result = mysqli_query($link,$sql);
    $array = mysqli_fetch_array($result);
    $sql1="SELECT id as payId FROM `salary` WHERE staff_id='$id' and month(date) in('$month') and year(date) in ('$year')";
   $result1 = mysqli_query($link,$sql1);
   $p=0;
   if(mysqli_num_rows($result1)>0){
	  $array1=mysqli_fetch_array($result1);
   }else{
		$array1 = array("payId"=>"0");
   }
		$array=array_merge($array,$array1);
	  /*foreach($array as $index=> $val){
	   echo $index."=".$val;
	   echo "<br>";  
	  }*/
	  
	  if(sizeof($array)==0){
		$array=array("staff_name"=>"");  
		}
   echo json_encode($array);
   
   
 //  echo $array['created_on'];
	//"SELECT numcode,phonecode FROM country WHERE name= '".$p."'"
?>