<?php
session_start();
  if(isset($_SESSION['user'])){
	   	$role=$_SESSION['role'];
	   	$user=$_SESSION['user'];
	   	$shop=$_SESSION['shop'];
			$cookie_name = 'userdata';
			$cookie_value = $user.",".$role.",".$shop;
		if((isset($_SESSION['check']) && $_SESSION['check']==1) && !isset($_COOKIE[$cookie_name])){
			if(setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/')) // 86400 = 1 day
			{	
				unset($_SESSION['check']);
			}
		}
  }
  else{
	   header("Location:index.php");
	   echo "<script>location='index.php'</script>";
	 
  }
	include('action/class.php');
	$obj=new database();
  $array=$obj->get_details("`shop`","`name`","`id`='$shop'");
  $_SESSION['shop_name']=$array['name'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Home</title>
        
        <link href="custom/style.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		<style>
			
        </style>
		<script src="bootstrap/js/jquery.js" type="text/javascript"></script>
        
	</head>

    <body>
    <?php include "demoheader.php";?>
   		<div class="container">
     		<div class="row">
                <div class="col-md-12">
                    <center>
                        <h1 class="text-success">Welcome to Inventory Management System</h1>
                        <br />
                        <h3 class="text-danger">Powered By @Brightcode Software Services Pvt Ltd</h3>
                        <br />
                        <h4 class="text-warning">+919386806214, +919304748714</h4>
                        <br />
                        <h4 class="text-info"><a href="https://brightcodess.com">www.brightcodess.com</a></h4>
                    </center>
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
  		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   
  		<script src="custom/custom.js" type="text/javascript"></script>     
        <script>
        	$(document).ready(function(e) {
				var shop='suntech';
                $.ajax({
					type:"GET",
					url:"backup/backup.php",
					data:{time:"login",shop:shop},
					success: function(data){}
				});
            });
        	
        </script>             
    </body>
</html>
