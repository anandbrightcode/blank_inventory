<?php
session_start();
include('connection.php');
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
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        <script>
				function readURL(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
		
						reader.onload = function (e) {
							$('#blah')
								.attr('src', e.target.result)
								.width(160)
								.height(180);
						};
		
						reader.readAsDataURL(input.files[0]);
					}
				}
		</script>
        
	</head>

    <body style="background-color:#F6F6F6">
    <?php include "../header.php";?>
    <div class="container">
   <div class="row" style="height:20px;">
     <div class="col-md-12">
       <div id="msg">
                 <div class="text-center text-success" style="font-size:18px;">
					   <?php if(isset($_GET['success'])){
                           echo $msg= $_GET['success'];
                           echo "<i class='fa fa-check'></i>";
                           
                       }?>
                    </div>
                    <div class="text-center text-danger" style="font-size:18px;">
                       <?php if(isset($_GET['err'])){
                           echo "Error:".$_GET['err'];
						   echo "<i class='fa fa-times'></i>";
                       }?>
                    </div>
                </div><!-- msg closed -->     
     </div><!-- end of column for message -->
   </div><!-- end of row for message -->
  <div class="row">
    <div class="col-md-2">
        <div style="margin-top:50px;">
            <a href="../staff?pagename=staff" class="btn btn-success" style="width:130px;">Add Staff</a><br /><br />
            <a href="staff_details.php?pagename=staff" class="btn btn-warning" style="width:130px;">Staff Details</a><br /><br />
            <a href="staff_salary.php?pagename=staff" class="btn btn-info"  style="width:130px;">Staff Salary</a><br /><br />
            <a href="salary_report.php?pagename=staff" class="btn btn-primary"  style="width:130px;">Salary Report</a><br />
        </div>
    </div><!-- end of sidebar column-->
    <div class="col-md-9">
         <div class="row">
           <div class="col-md-12">
              <span class="text-center text-danger"><h2><i class="fa fa-file-text-o"></i> Staff Information</h2></span>
           </div><!--end of column for staff -->
         </div><!-- end of row for heading staff -->
         <hr />
         <form method="post" action="addstaff.php" enctype="multipart/form-data">
             <div class="row">
                <div class="col-md-6">
                    <br/>
                    <div class="row">
                        <div class="col-md-4"><b>Staff Name:</b></div>
                        <div class="col-md-8"><input type="text" name="staff_name" id="staff_name"  class="form-control"/></div>
                    </div><br/>
                    <div class="row">
                        <div class="col-md-4"><b>Father's Name:</b></div>
                        <div class="col-md-8"><input type="text" name="staff_father_name" id="staff_father_name" class="form-control" /></div>
                    </div><br/>
                    <div class="row">
                        <div class="col-md-4"><b>Age:</b></div>
                        <div class="col-md-8"><input type="number" name="staff_age"  id="staff_age" class="form-control"/></div>
                    </div><br/>
                    <div class="row">
                        <div class="col-md-4"><b>Sex:</b></div>
                        <div class="col-md-8">
                            <input type="radio" name="sex" value="Male"  class="sexm"/>&nbsp;Male &nbsp;
                            <input type="radio" name="sex" value="Female"  class="sexf"/>&nbsp;Female
                        </div>
                    </div> <br/>
                    <div class="row">
                        <div class="col-md-4"><b>Address:</b></div>
                        <div class="col-md-8"><textarea name="staff_address" id="staff_address" class="form-control"/></textarea></div>
                    </div>
                    <br/>
                </div><!--end of name section-->
                <br/><br/><br/>
                <div class="col-md-2"></div>
                <div class="col-md-4">
                	<img id="blah" style="height:180px; width:160px;" />
                    <input type="file" name="photo" id="choose_photo_btn" onchange="readURL(this);" />
                </div><!--end of image section-->
             </div><!-- end of first row -->
             <div class="row">
             	<div class="col-md-6"> 
                    
                    <div class="row">
                        <div class="col-md-4"><b>City:</b></div>
                        <div class="col-md-8"><input type="text" name="city" id="staff_city" class="form-control"/></div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-4"><b>State:</b></div>
                        <div class="col-md-8">
                        	<select name="state" autocomplete="off" id="staff_state" class="form-control">
								<?php 
									$sql="select distinct city_state from cities";
									$rs=mysqli_query($link,$sql);
									while($result=mysqli_fetch_array($rs)){
								?>
                                <option value="<?php echo $result['city_state'];?>" <?php if($result['city_state']=='Jharkhand'){ echo 'selected';} ?>><?php echo $result['city_state'];?></option>
                                <?php 
									}
								?>
							</select>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-4"><b>Mobile:</b></div>
                        <div class="col-md-8"><input type="number" name="staff_mobile" id="staff_mobile_no" class="form-control"/></div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-4"><br /><br /><br /><br /><b>Salary:</b></div>
                        <div class="col-md-8" style="border:1px solid #c6c6c6; border-radius:3px; ">
                        	<br />
                            <div class="row">
                                <div class="col-md-6"><b>Basic Salary:</b></div>
                                <div class="col-md-6"><input type="number" name="basic_salary" id="basic_salary_value" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-6"><b>PF:</b></div>
                                <div class="col-md-6"><input type="number" id="pf" name="pf" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-6"><b>HRA:</b></div>
                                <div class="col-md-6"><input type="number" id="hra" name="hra" class="form-control"/></div>
                            </div><br />
                        </div>
                    </div><!--end of salary row-->
                    <br/>
                </div><!--end of left section-->
             	<div class="col-md-6">
                	<br /><br />
                    <div class="row" style="height:14px;"></div>
                	<div class="row" style="margin-left:0;">
                    	<div class="col-md-12" style="border:1px solid #c6c6c6; border-radius:3px; ">
                        	<br />
                            <div class="row">
                                <div class="col-md-5"><b>Company PF No.:</b></div>
                                <div class="col-md-7"><input type="text" id="company_pf_no" name="com_pf_no" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-5"><b>PF Account No.:</b></div>
                                <div class="col-md-7"><input type="text" id="pf_account_no" name="pf_acco_no" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-5"><b>Income Tax No.:</b></div>
                                <div class="col-md-7"><input type="text" id="income_tax_no" name="income_tax_no" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-5"><b>Deignation:</b></div>
                                <div class="col-md-7"><input type="text" name="designation" id="designation" class="form-control"/></div>
                            </div><br />
                        	<div class="row">
                                <div class="col-md-5"><b>Date of Joining:</b></div>
                                <div class="col-md-7"><input type="date" name="doj" id="date_of_joining" class="form-control"/></div>
                            </div><br />
                        </div>
                    </div><!--end of salary right row-->
                </div><!--end of right section-->
             </div><!--end of second row-->
             <div class="row">
             	<div class="col-md-6"></div>
             	<div class="col-md-2">
             		<input type="submit" name="submit" id="save_staff_form" class="btn btn-success" value="Save" />
                </div>
             	<div class="col-md-4"></div>
             </div><!--end of submit row-->
         </form><!-- end of form-->
   </div><!-- end of right column -->
  </div><!-- end of row -->
</div><!-- end of container -->
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
</body>
</html>
