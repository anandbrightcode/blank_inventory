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
        <title>Customer</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		<style>
			
        </style>
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
	</head>

    <body>
    <?php include "../header.php";?>
   		<div class="container">
     		<div class="row">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
                <div class="col-md-12">
                    <div id="formPanel" class="panel panel-primary" style="display:none;">
                        <div class="panel-heading">
                            <font size="+2">Add Customer</font>
                            <button class="btn btn-warning pull-right" onclick="showThis('list');">View Customers</button>
                        </div>
                        <div class="panel-body">
                            <form action="../action/insert.php" method="post" style="font-size:16px;">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Customer Name</b></div>
                                    <div class="col-md-6"><input type="text" name="name" class="form-control" required="required" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Phone</b></div>
                                    <div class="col-md-6"><input type="text" name="phone" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Email</b></div>
                                    <div class="col-md-6"><input type="email" name="email" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>GSTIN</b></div>
                                    <div class="col-md-6"><input type="text" name="gst" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Address</b></div>
                                    <div class="col-md-6">
                                        <textarea name="address" style="resize:vertical;" class="form-control"></textarea>
                                        <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                    </div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <center>
                                        <input type="submit" name="save_cust" value="Save" class="btn btn-success" />
                                    </center>
                                </div><br />
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                    <div class="panel panel-danger" id="listPanel">
                        <div class="panel-heading">
                            <font size="+2">Customer List</font>
                            <button class="btn btn-info pull-right" onclick="showThis('form');">Add Customer</button>
                        </div><!-- panel-heading closed-->
                        <div class="panel-body">
                            <input type="text" placeholder="Search customer By Name Or Id" class="form-control" onkeyup="getCustomerData(this.value)" id="c_search">
                            <br>
                            <div id="query_result">
                                <?php include "customerlist.php" ;?>	  
                            </div>
                        </div>
                    </div><!-- list panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
			function showThis(str){
				var div=str;
				if(div=='form'){
					$('#formPanel').show();
					$('#listPanel').hide();
				}
				else{
					$('#formPanel').hide();
					$('#listPanel').show();
				}
			}
			
			function getCustomerData(str){
				var query=str;
				var shop='<?php echo $shop; ?>';
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "customerlist.php?query="+query+"&shop="+shop, true);
	 			xhttp.send();	
			}
			
			function navigate(str1,str2){
				var query=str1;
				var page=str2;
				var shop='<?php echo $shop; ?>';
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "customerlist.php?query="+query+"&shop="+shop+"&page="+page, true);
	 			xhttp.send();	
			}
			
			function confirmDel(){
				if(confirm("Are sure to Delete this?")){
					return true;
				}else{
					return false;
				}
			}
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
