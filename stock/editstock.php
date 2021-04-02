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
  	if(!isset($_GET['id'])){
	   header("Location:../stock?pagename=stock");
	}
	$id=$_GET['id'];
  include('../action/class.php');
  $obj=new database();
  $array=$obj->get_details("`stock`","*","`id`='$id' and `shop`='$shop'");
	$comp=$obj->get_details("`company`","`name`","`shop`='$shop' and `id`='".$array['company_id']."'");
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Stock</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
		<style>
        
        .sexy_line { 
           margin-right:100px;
            height: 1px;
            background: black;
            background: -webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
        }
        </style>
	</head>

    <body>
<?php include "../header.php";?>
<div class="container">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
         <div class="row">
           <div class="col-md-12">
           
                <form action="../action/update.php" method="post" onsubmit="return validate()">
                        	<fieldset style="padding-left:6px; border-radius:5px;">
                            	<h4 class="text-center text-info" style="padding-right:50px;">Update Item</h4>
                                <div class="sexy_line"></div><br />
                                <table class="table table-bordered">
									<tr>
										
										<td><b>Date:</b></td>
										<td><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"	/></td>
									</tr>
									<tr>	
										<td><b>Category:</b></td>
										<td>
                                        	<select class="form-control" readonly disabled="disabled">
                                            	<option><?php  $cat=$obj->get_details("`category`","`name`","`id`='".$array['category']."'"); echo $cat['name'];?></option>
                                            </select>
                                            <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                            <input type="hidden" name="id" value="<?php echo $id; ?>"  />
                                        </td>
									</tr>
									<tr>	
										<td><b>Company:</b></td>
										<td><input type="text" name="company" class="form-control" readonly="readonly" value="<?php echo $comp['name']; ?>" /></td>
									</tr>
									<tr>	
										<td><b>Model:</b></td>
										<td><input type="text" name="model" class="form-control" readonly="readonly" value="<?php echo $array['model']; ?>" /></td>
									</tr>
									<tr>	
										<td><b>MRP:</b></td>
										<td><input type="text" name="mrp" class="form-control" value="<?php echo $array['mrp']; ?>" /></td>
									</tr>
									<tr>	
										<td><b>UOM:</b></td>
										<td><input type="text" name="uom" class="form-control" value="<?php echo $array['uom']; ?>" /></td>
									</tr>
									<tr>
										<td ><b>Quantity:</b></td>
										<td >
                                        	<input type="text" name="quantity" id="quantity" class="form-control" value="<?php echo $array['quantity']; ?>" />
                                        	<input type="hidden" name="hquantity" id="hquantity" value="<?php echo $array['quantity']; ?>" />
                                        </td>
									</tr>
									<tr style="display:none;">	
										<td><b>Serial No.:</b></td>
										<td><input type="text" name="slno" id="slno" class="form-control" value="<?php echo $array['slno']; ?>"  /></td>
									</tr>
									<tr>
										<td ><b>HSN/SAC :</b></td>
										<td ><input type="text" name="hsn" id="hsn" class="form-control" value="<?php echo $array['hsn']; ?>" /></td>
									</tr>
									<tr>
										<td ><b>Purchase Price :</b></td>
										<td><input type="text" name="purchase" id="purchase" class="form-control" value="<?php echo $array['purchase']; ?>" readonly="readonly"/></td>
									</tr>
									<tr>
										<td ><b>Base Price :</b></td>
										<td><input type="text" name="base_price" id="base_price" class="form-control" value="<?php echo $array['base_price']; ?>" readonly="readonly" /></td>
									</tr>
									<tr>
										<td ><b>Selling Price :</b></td>
										<td><input type="text" name="selling_price" id="selling_price" class="form-control" value="<?php echo $array['selling_price']; ?>" /></td>
									</tr>
									<tr>
										<td ><b>Description:</b></td>
										<td><textarea name="description" class="form-control" style="resize:vertical"><?php echo $array['description']; ?></textarea></td>
									</tr>
									<tr align="center">
										<td colspan="2" >
                                        	<input type="submit" name="update_stock" value="Update"  class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
                                        </td>
									</tr>
								</table>
                            </fieldset>
                        </form>
           
           </div><!-- end of column for form -->
         </div><!-- end of row for form --> 
    </div><!-- end of row for main forms -->
  </div><!-- end of row -->
</div><!-- end of container -->
<script>
	function validate(){	
		if(confirm("Click Ok to Submit. \nClick Cancel to Edit.")){
			return true;
		}
		else{
			return false;
		}
	}
	
</script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
</body>
</html>
