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
	$category=$obj->get_rows("`category`","`id`,`name`","`shop`='$shop'" ,'name asc');
  
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
         <link href="../custom/DataTables/datatables.min.css" rel="stylesheet" />
        <script src="../custom/DataTables/datatables.min.js" type="text/javascript"></script>
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
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
    <div class="col-md-4">
         <div class="row">
           <div class="col-md-12">
           
                <form action="../action/insert.php" method="post" onsubmit="return validate()">
                        	<fieldset style="padding-left:6px; border-radius:5px;">
                            	<h4 class="text-center text-info" style="padding-right:50px;">Add Items</h4>
                                <div class="sexy_line"></div><br />
                                <table class="table table-bordered">
									<tr>
										
										<td><b>Date:</b></td>
										<td><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"	/></td>
									</tr>
									<tr>	
										<td><b>Category:</b></td>
										<td>
                                        	<select name="category" id="category" class="form-control" onchange="getCompany(this.value)" required="required">
                                            	<option value="">Select</option>
                                                <?php foreach($category as $value){?>
                                            	<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
									</tr>
									<tr>	
										<td><b>Company:</b></td>
										<td>
                                        	<select name="company_id" id="company_id" class="form-control" onchange="getModel(this.value)" required="required">
                                            	<option value="">Select</option>
                                            </select>
                                        </td>
									</tr>
									<tr>	
										<td><b>Model:</b></td>
										<td>
                                        	<select name="model" id="model" class="form-control" onchange="selectModel(this.value)" required="required">
                                            	<option value="">Select Model</option>
                                            </select>
                                        </td>
									</tr>
									<tr>
										<td ><b>MRP:</b></td>
										<td ><input type="text" name="mrp" id="mrp" class="form-control" required/></td>
									</tr>
									<tr>
										<td ><b>UOM:</b></td>
										<td ><input type="text" name="uom" id="uom" class="form-control" required/></td>
									</tr>
									<tr>
										<td ><b>Quantity:</b></td>
										<td ><input type="text" name="quantity" id="quantity" class="form-control" /></td>
									</tr>
									<tr style="display:none;">	
										<td><b>Serial No.:</b></td>
										<td><input type="text" name="slno" id="slno" class="form-control"  /></td>
									</tr>
									<tr>
										<td ><b>HSN/SAC:</b></td>
										<td ><input type="text" name="hsn" id="hsn" class="form-control" /></td>
									</tr>
									<tr>
										<td ><b>Purchase Price :</b></td>
										<td><input type="text" name="purchase" id="purchase" class="form-control"  autocomplete="off"/></td>
									</tr>
									<tr>
										<td ><b>Base Price :</b></td>
										<td><input type="text" name="base_price" id="base_price" class="form-control"/></td>
									</tr>
									<tr>
										<td ><b>Selling Price:</b></td>
										<td><input type="text" name="selling_price" id="selling_price" class="form-control"/></td>
									</tr>
									<tr>
										<td ><b>Description:</b></td>
										<td><textarea name="description" class="form-control" style="resize:vertical"></textarea></td>
									</tr>
									<tr align="center">
										<td colspan="2" >
                                        	<input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                        	<input type="submit" name="add_stock" value="Add"  class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
                                        </td>
									</tr>
								</table>
                            </fieldset>
                        </form>
           
           </div><!-- end of column for form -->
         </div><!-- end of row for form --> 
    </div><!-- end of row for main forms -->
    <div class="col-md-8">
      <span><h4 class="text-center text-primary">Stock Details</h4></span>
       <div class="sexy_line"></div><br />
       <!-- <div class="row">
            <div class="col-md-4">
            	<input type="text" id="query" class="form-control" placeholder="Enter Model to Search" value="<?php //if(isset($_GET['query'])){echo $_GET['query'];}?>" />
            </div>
             <div class="col-md-4">
            	<input type="text" id="query" class="form-control" placeholder="Enter Company to Search" value="<?php //if(isset($_GET['query'])){echo $_GET['query'];}?>" />
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4"><a href="stocktoxsl.php" class="btn btn-warning pull-right">Export to Excel</a></div>
        </div><br />-->
        <div class="container-fluid">
       	<div class="row table-responsive" id="stock_table">
        	<?php include('stock_table.php'); ?>
   	 	</div>
    </div><!-- end of row -->
    </div><!-- end of stock details -->
  </div><!-- end of row -->
</div><!-- end of container -->
<script>
	
	function confirmDel(){
		if(confirm("Are you sure you want to Delete this?")){
			return true;
		}	
		else{
			return false;	
		}
	}
	
	$(function(){
		$('#query').keyup(function(){
			var shop='<?php echo $shop; ?>';
			$.ajax({
				type: 'GET',
				url: 'stock_table.php',
				data: {
					query:$(this).val(),shop:shop
				},
				success: function (data) //on recieve of reply
				{
					$('#stock_table').html(data)
				}
			});
		});	
	});
	
	function getCompany(str){	
		var category=str;
		var shop='<?php echo $shop; ?>';
		var models="<option value=''>Select Model</option>";
		var comp="<option value=''>Select Company</option>";
		$('#slno').val('');$('#hsn').val('');
		$('#quantity').val('');$('#purchase').val('');
		$('#base_price').val('');
		$('#model').html(models);
		if(category!=''){
			$.ajax({
				type:'POST',
				url:"../ajax_returns.php",
				data:{shop:shop,category:category,get_company:'get_company',page:'stock'},
				success: function(data){
					$('#company_id').html(data);
				}	
			});
		}
		else{
			$('#company_id').html(comp);
		}
	}
	function getModel(str){
		var company_id=str;
		var shop='<?php echo $shop; ?>';
		var category=$('#category').val();
		$('#slno').val('');$('#hsn').val('');
		$('#quantity').val('');$('#purchase').val('');
		$('#base_price').val('');
		if(category!=''){
			$.ajax({
				type:'POST',
				url:"../ajax_returns.php",
				data:{company_id:company_id,shop:shop,category:category,get_model:'get_model',page:'stock'},
				success: function(data){
					$('#model').html(data);
				}	
			});
		}
	}
	
	function selectModel(str){
		$('#slno').val('');$('#hsn').val('');
		$('#quantity').val('');$('#purchase').val('');
		$('#base_price').val('');	
		
	}
	
	function validate(){	
		if(confirm("Click Ok to Submit. \nClick Cancel to Edit.")){
			return true;
		}
		else{
			return false;
		}
	}
$(document).ready( function () {
	$('.datatable').DataTable( {
	   "language": {
	  	"search": "Enter Company Or Model:"
			}
 	 } );
} );   
	
</script>
    
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
</body>
</html>
