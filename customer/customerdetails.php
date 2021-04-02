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
                    <div id="formPanel" class="panel panel-info">
                        <div class="panel-heading">
                            <font size="+2"><?php echo $array['name'];?></font>
                        </div>
                        <div class="panel-body">
                        	<div class="col-md-1"></div>
                            <div class="col-md-10 table-responsive">
                                <table class="table" align="center" >
                                    <tr>
                                        <td>Name : </td>
                                        <td><?php echo $array['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone : </td>
                                        <td><?php echo $array['phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>E-mail : </td>
                                        <td><?php echo $array['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>GSTIN : </td>
                                        <td><?php echo $array['gst']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address : </td>
                                        <td><?php echo $array['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php if($array['advance']<0){echo "Dues"; $advance=0-$array['advance'];}else{echo "Advance"; $advance=$array['advance'];} ?> : </td>
                                        <td><?php echo toDecimal($advance); ?></td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2">
                                        	<a href="customeredit.php?id=<?php echo $id."&pagename=customer";?>" class="btn btn-info btn-sm">Edit <i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                </table>
                         	</div>
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
