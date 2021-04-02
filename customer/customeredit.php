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
  include('../action/class.php');
  $obj=new database();
  if(isset($_GET['id'])){
	  $id=$_GET['id'];
	  $array=$obj->get_details("`customer`","*","`id`='$id' and `shop`='$shop'");
	  //print_r($array);
  }else{header("Location:../customer?pagename=customer");}
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
                    <div id="formPanel" class="panel panel-primary">
                        <div class="panel-heading">
                            <font size="+2">Update Customer</font>
                        </div>
                        <div class="panel-body">
                            <form action="../action/update.php" method="post" style="font-size:16px;">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Customer Name</b></div>
                                    <div class="col-md-6"><input type="text" name="name" class="form-control" value="<?php echo $array['name'];?>" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Phone</b></div>
                                    <div class="col-md-6"><input type="text" name="phone" class="form-control" value="<?php echo $array['phone'];?>" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Email</b></div>
                                    <div class="col-md-6"><input type="email" name="email" class="form-control" value="<?php echo $array['email'];?>" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>GSTIN</b></div>
                                    <div class="col-md-6"><input type="text" name="gst" class="form-control" value="<?php echo $array['gst'];?>" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Dues/Advance</b></div>
                                    <div class="col-md-2">
                                    	<select name="type" id="type" class="form-control">
                                        	<option value="advance" <?php if($array['advance']>=0){echo "selected"; $advance=$array['advance'];} ?>>Advance</option>
                                        	<option value="dues" <?php if($array['advance']<0){echo "selected"; $advance=0-$array['advance'];} ?>>Dues</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                    	<input type="text" name="advance" class="form-control" value="<?php echo $advance; ?>">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Address</b></div>
                                    <div class="col-md-6">
                                        <textarea name="address" style="resize:vertical;" class="form-control"><?php echo $array['address'];?></textarea>
                                        <input type="hidden" name="shop" value="<?php echo $shop; ?>" /><input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    </div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <center>
                                        <input type="submit" name="update_cust" value="Save" class="btn btn-success" />
                                    </center>
                                </div><br />
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
