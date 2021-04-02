<?php
error_reporting(0);
session_start();
  if(isset($_SESSION['user'])){
	  $role=$_SESSION['role'];
      $user=$_SESSION['user'];
      $shop=$_SESSION['shop'];
	  $id=$_GET['id'];
	  $pre=$_GET['page'];
  }
  else{
	  header("Location:index.php");
  }
  
include('connection.php');
include('notowords.php');
$select=mysqli_query($link,"select * from salary where id='$id'");
$data=mysqli_fetch_array($select);
$array=mysqli_fetch_assoc(mysqli_query($link,"SELECT * from `shop` where `id`='$shop'"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bootstrap/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css" />
<script src="jquery-3.1.0.js"></script>
<title>Pay</title>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin:0;  /* this affects the margin in the printer settings */
}
@media print{
	#buttons{
			display:none;
		}
	#invoice{
    width: 950px;
    /* -ms-transform: rotate(90deg); IE 9 
    -webkit-transform: rotate(90deg); /* Safari
    transform: rotate(90deg);
    margin-top:-170px;
    margin-right: 40px; */
  }
	}
</style>
</head>

<body>
    <div class="container" id="invoice">
     <div class="row">
       <div class="col-md-12">
			<center>
                	<font size="+3" style="letter-spacing:2px"><?php echo strtoupper($array['name']); ?><br /></font>
                    <font size="+1"><?php echo $array['address'];if($array['address2']!=''){echo ", ".$array['address2'] ;}; ?><br />
                    <?php echo $array['district']; ?><br />
                    Ph : <?php echo $array['phone']; ?><br />
                    </font>
			</center>
       </div><!-- col-md-12 closed -->
     </div><!-- End of row-->
   	<table width="100%" border="0">
    	<tr height="40">
        	<td width="10%" align="left" style="padding-left:20px"><span style="font-size:16px; font-weight:500">Receipt No.</span></td>
        	<td width="10%" align="center"><span style="font-size:18px; font-weight:400"><?php echo $data['id']; ?></span></td>
        	<td></td>
        	<td width="10%" align="center" style="padding-left:20px"><span style="font-size:16px; font-weight:500">Date:</span></td>
        	<td width="20%" align="center"><span style="font-size:18px; font-weight:400"><?php if($data['date']!='')echo date('d-m-Y',strtotime($data['date'])); ?></span></td>
        </tr>
        <tr height="40">
        	<td align="left" style="padding-left:20px"><span style="font-size:16px; font-weight:500">Name :</span></td>
            <td colspan="4" align="center"><span style="font-size:18px; font-weight:400"><?php echo $data['name']; ?></span></td>
        </tr>
        <tr height="40">
        	<td align="left" colspan="2"  style="padding-left:20px"><span style="font-size:16px; font-weight:500">Amount (In Words) :</span></td>
            <td colspan="3" align="center"><span style="font-size:18px; font-weight:400"><?php echo convertNum($data['net_payment'])." Only"; ?></span></td>
        </tr>
        <tr height="40">
        	<td align="left" style="padding-left:20px" ><span style="font-size:16px; font-weight:500">Amount :</span></td>
            <td align="center"><span style="font-size:18px; font-weight:400"><?php echo "₹ ".$data['net_payment']; ?></span></td>
            <td colspan="3"></td>
        </tr>
    </table>
     <br /><div id="buttons">
     <center>
      <button type="button" class="btn btn-danger" onclick="window.print();" >Print</button>
     <button type="button" onclick="closeThis('<?php echo $pre; ?>');" class="btn btn-default">Close</button>
     </center></div>
   </div><!-- coantainer closed-->
<script>
  var a=$("#content").val();
 // alert(a);
 
 function closeThis(data){
	 page=data;
	 if(page==""){
		 window.location="salary_report.php?pagename=staff";
	 }
	 else{
		 window.location="staff_salary.php?pagename=staff";
	 }
 }
</script>
</body>
</html>