<?php
session_start();
  if(isset($_SESSION['user'])){
	   	$role=$_SESSION['role'];
	   	$user=$_SESSION['user'];
	   	$shop=$_SESSION['shop'];
  }
  else{
	   header("Location:../index.php");
	   echo "<script>location='../index.php'</script>";
	 
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Staff</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
<style>
 input:-moz-read-only { /* For Firefox */
    background-color: yellow;
}

input:read-only { 
    background-color: #ffffff;
}
.sexy_line { 
   margin-right:100px;
    height: 1px;
    background: black;
    background: -webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
}
</style>
 
 <script>
 	window.onload=function(){
		var name='<?php echo $_GET['name'] ?>';	
		if(name!=''){
			$('#name').val(name);
		}
	}
 	
   function getbyName(str){
   		var name=str;
	  
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
		 document.getElementById("staff_data").innerHTML = xhttp.responseText;
		}
	  };
	  xhttp.open("GET", "salary_report_ajax.php?name="+name, true);
	  xhttp.send();
	
	}
 
  </script>
 
	</head>

    <body style="background-color:#F6F6F6">
    <?php include "../header.php";?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
        <div style="margin-top:50px;">
            <a href="../staff?pagename=staff" class="btn btn-success" style="width:130px;">Add Staff</a><br /><br />
            <a href="staff_details.php?pagename=staff" class="btn btn-warning" style="width:130px;">Staff Details</a><br /><br />
            <a href="staff_salary.php?pagename=staff" class="btn btn-info"  style="width:130px;">Staff Salary</a><br /><br />
            <a href="salary_report.php?pagename=staff" class="btn btn-primary"  style="width:130px;">Salary Report</a><br />
        </div>
    </div><!-- end of sidebar column-->
    <div class="col-md-10">
         <div class="row">
           <div class="col-md-12">
              <span class="text-center text-danger"><h2><i class="fa fa-file-text-o"></i>Salary Report</h2></span>
           </div><!--end of column for staff -->
         </div><!-- end of row for heading staff -->
         <hr />
         <div class="row">
         	<div class="col-md-2"><b>Enter Name:</b></div>
         	<div class="col-md-2"><input type="text" name="name" id="name" onkeyup="getbyName(this.value)" class="form-control" /></div>
         	<div class="col-md-3"></div>
         	<div class="col-md-3"></div>
         </div><br />
         <div class="row" id="staff_data" style="margin-left:0">
		 	<?php include "salary_report_ajax.php";?>
         </div><!--end of table-->
    </div><!--end of right column-->
  </div><!--end of row-->
 </div><!--end of container-->
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   
</body>
</html>