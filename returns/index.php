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
  include_once("../action/class.php");
  $obj=new database();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Returns</title>
        
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
                            <font size="+2">Add Defective Product</font>
                            <button type="button" class="btn btn-warning pull-right" onclick="showThis('list');">View Defective Products</button>
                        </div>
                        <div class="panel-body">
                            <form action="../action/insert.php" method="post" style="font-size:16px;">
                                <table class="table table-bordered">
                                    <tr>	
                                        <td><b>Date :</b> </td>
                                        <td><input type="date" name="date" id="date"  class="form-control" required="required"/></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td ><b>Invoice No:</b> </td>
                                        <td>
                                        	<select name="prefix" id="prefix" class="form-control" style="float:left; position:relative; width:40%;">
												<?php 
                                                    $selpre=$obj->get_rows("`invoice`","distinct(`prefix`)","`shop`='$shop'","id desc");
                                                    if(is_array($selpre)){
                                                        foreach($selpre as $prefix){
                                                ?>
                                                <option value="<?php echo $prefix['prefix']; ?>"><?php echo $prefix['prefix']; ?></option>
                                                <?php 
                                                        } 
                                                    }
                                                    else{echo "<option value=''>No Invoice</option>";}
                                                ?>
                                            </select>
                                            <input type="text" name="invoice_no" value="" id="invoice_no" class="form-control" autocomplete="off" 
                                                    			style="float:left; position:relative; width:60%;"/></td>
                                        <td><b>Customer Name : </b></td>
                                        <td>
                                            <input type="text" id="customer" name="customer" readonly="readonly" class="form-control" />
                                            <input type="hidden" id="invoice" name="invoice" />
                                            <input type="hidden" id="customer_id" name="customer_id" />
                                            <input type="hidden" id="count" name="count" />
                                            <input type="hidden" id="shop" name="shop" value="<?php echo $shop; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Customer Mobile : </b></td>
                                        <td><input type="text" id="mobile" name="mobile" readonly="readonly" class="form-control" /></td>
                                        <td><b>Customer Address : </b></td>
                                        <td>
                                            <textarea name="address" id="address" class="form-control" style="resize:vertical"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <table class="table" id="products">
                                                <tr>
                                                    <th style="text-align:center;">Category</th>
                                                    <th style="text-align:center;">Company</th>
                                                    <th style="text-align:center;">Model</th>
                                                    <th style="text-align:center;">Quantity</th>
                                                    <th style="text-align:center;">HSN/SAC</th>
                                                    <th style="text-align:center;">Price</th>
                                                    <th style="text-align:center;">Defect</th>
                                                    <th style="text-align:center;">Return Quantity</th>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr> 
                                    <tr align="center">
                                      <td colspan="4"> <input type="submit" value="Save" name="add_defective" class="btn btn-success" /></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                    <div class="panel panel-danger" id="listPanel" style="display:none;">
                        <div class="panel-heading">
                            <font size="+2">Defective Products</font>
                            <button type="button" class="btn btn-info pull-right" onclick="showThis('form');">Add Defective Product</button>
                        </div><!-- panel-heading closed-->
                        <div class="panel-body">
                        	<div class="row">
                                <div class="col-md-4">
                                	<select name="cust" id="cust" class="form-control" onchange="getDefectiveData()">
                                    	<option value="">Select Customer</option>
                                        <?php 
											$customers=$obj->get_rows("`customer`","*","`shop`='$shop'");
											if(is_array($customers)){
												foreach($customers as $customer){
										?>
                                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                                        <?php
												}
											}
										?>
                                    </select>
                                </div>
                            	<div class="col-md-4">
                            		<input type="text" placeholder="Search Product By Name Or Invoice No" class="form-control" onkeyup="getDefectiveData()" id="query">
                                </div>
                            </div>
                            <br>
                            <div id="query_result">
                                <?php include "returnslist.php" ;?>	  
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
			
			$(function () {
    			$('#invoice_no').keyup(function () {
					resetFields();
					var prefix=$('#prefix').val();
					var shop=$('#shop').val();
					$.ajax({
						type: 'POST',
						url: '../ajax_returns.php',
						data: {
							invoice_no: $(this).val(),
							prefix:prefix,
							shop:shop,
							getReturns:'getReturns'
						},
						dataType: 'json',
						success: function (data) //on recieve of reply
						{
							//alert(data)
							var id=data['id'];
							var customer_id=data['customer_id'];
							var customer_name=data['customer_name'];
							var customer_mobile=data['customer_mobile'];
							var add_to=data['add_to'];
							$('#invoice').val(id);
							$('#customer_id').val(customer_id);
							$('#customer').val(customer_name);
							$('#mobile').val(customer_mobile);
							$('#address').val(add_to);
							
							var l=parseInt(Object.keys(data).length)-5;
							$('#count').val(l);
							for(i=0;i<l;i++){
								var col1="<td><input type='text' name='category"+i+"' value='"+data[i]['category']+"' class='form-control' readonly='readonly'>"; 
								var hid="<input type='hidden' name='product_id"+i+"' value='"+data[i]['product_id']+"'></td>"; 
								var col2="<td><input type='text' name='company"+i+"' value='"+data[i]['company']+"' class='form-control' readonly='readonly'></td>"; 
								var col3="<td><input type='text' name='model"+i+"' value='"+data[i]['model']+"' class='form-control' readonly='readonly'></td>"; 
								var col4="<td><input type='text' name='quantity"+i+"' value='"+data[i]['quantity']+"' class='form-control' readonly='readonly'></td>"; 
								var col5="<td><input type='text' name='hsn"+i+"' value='"+data[i]['hsn']+"' class='form-control' readonly='readonly'></td>"; 
								var col6="<td><input type='text' name='price"+i+"' value='"+data[i]['price']+"' class='form-control' ></td>"; 
								var col7="<td><input type='text' name='defect"+i+"' class='form-control'></td>"; 
								var col8="<td><input type='number' name='return"+i+"' max='"+data[i]['quantity']+"' value='0' class='form-control'></td>"; 
								$('#products tr:last').after("<tr>"+col1+hid+col2+col3+col4+col5+col6+col7+col8+"</tr>");
							}
						}
					});
				}); 
				$('#prefix').change(function(){
					resetFields();
					$('#invoice_no').val('');
				}); 
			});
			
			function resetFields(){
				var header="<tr><th style='text-align:center;'>Category</th><th style='text-align:center;'>Company</th><th style='text-align:center;'>Model</th><th style='text-align:center;'>Quantity</th><th style='text-align:center;'>HSN/SAC</th><th style='text-align:center;'>Price</th><th style='text-align:center;'>Defect</th><th style='text-align:center;'>Return Quantity</th></tr>";
				$('#products').html('');$('#customer_id').val('');$('#customer').val('');$('#mobile').val('');$('#address').val('');
				$('#products').html(header);
			}
			
			function getDefectiveData(){
				var query=$('#query').val();
				var customer=$("#cust").val();
				var shop='<?php echo $shop; ?>';
				$.ajax({
					type:"GET",
					url:"returnslist.php",
					data:{customer:customer,query:query,shop:shop},
					success: function(data){
						$('#query_result').html(data);
					}	
				});
				
			}
			
			function navigate(str1,str2,str3){
				var customer=str1;
				var query=str2;
				var page=str3;
				var shop='<?php echo $shop; ?>';
				$.ajax({
					type:"GET",
					url:"returnslist.php",
					data:{customer:customer,query:query,shop:shop,page:page},
					success: function(data){
						$('#query_result').html(data);
					}	
				});
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
