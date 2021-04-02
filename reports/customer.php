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
	include_once "../action/class.php";
	$obj=new database();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Customer Report</title>
        
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
                <div class="col-md-2" style="margin-top:50px;">
                	<?php include('sidebar.php'); ?>
                </div><!-- end of col-md-3 -->
                <div class="col-md-10">
                	<center><font size="+2">Customer Wise Report</font></center><hr />
                    <div class="row">
                    	<div class="col-md-3">
                        	<select id="customer" class="form-control" onchange="getCustomer(this.value)">
                            	<option value="">Select Customer</option>
                                <?php
                                	$arr=$obj->get_rows("`customer`","`id`,`name`","`shop`='$shop' and `id` in (SELECT `customer_id` from invoice where `shop`='$shop')");
									if(is_array($arr)){
										foreach($arr as $customer){
								?>
                                    <option value="<?php echo $customer['id']; ?>" <?php if(isset($_GET['customer']) && $_GET['customer']==$customer['id']){echo "selected";} ?>>
                                    	<?php echo $customer['name']; ?>
                                    </option>
                                <?php
										}	
									}
								?>
                            </select>
                        </div>
                    	<div class="col-md-1"></div>
                    	<div class="col-md-3">
                        	<input type="text" id="query" placeholder="Enter Invoice No to Search" class="form-control" onkeyup="getInvoiceData(this.value)"
                            	value="<?php if(isset($_GET['query'])){echo $_GET['query'];} ?>" />
                        </div>
                        <div class="col-md-1"></div>
                    	<div class="col-md-3"></div>
                    </div><br />
                    <div class="row">
                        <div id="query_result" class="table-responsive col-md-12">
                            <?php include "custlist.php" ;?>	  
                        </div>
                    </div>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
			
			function getCustomer(str){
				var customer=str;
				var shop='<?php echo $shop; ?>';
				$('#query').val('');
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "custlist.php?customer="+customer+"&shop="+shop, true);
	 			xhttp.send();	
			}
			function getInvoiceData(str){
				var query=str;
				$('#customer').val('');
				var shop='<?php echo $shop; ?>';
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "custlist.php?query="+query+"&shop="+shop, true);
	 			xhttp.send();	
			}
			
			
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
