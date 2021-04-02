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
   if(isset($_GET['id']))
  {
	  $id=$_GET['id'];  
	  $get_invoice=$obj->get_details("`purchase`","`invoice`","`id`='$id'");
	  $inv_no=$get_invoice['invoice'];
  }
	$category=array();
	$gst=array();
	$selcat=$obj->get_rows("`category`","*","`shop`='$shop'","`name`");
	if(is_array($selcat)){
		foreach($selcat as $arr){
		  $category[$arr['id']]=$arr['name'];
		  $gst[$arr['id']]['cgst']=$arr['cgst'];
		  $gst[$arr['id']]['sgst']=$arr['sgst'];
		  $gst[$arr['id']]['igst']=$arr['igst'];
		}
	}
 $array=array();
 $suppliers=$obj->get_rows("`supplier`","`id`,`name`,`phone`,`state`,`advance`","`shop`='$shop'","`name`");
 $shop_details=$obj->get_details("`shop` t1, `state` t2","t2.`code` as code","t1.`id`='$shop' and t1.`state`=t2.`id`");
 $state_code=$shop_details['code'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Purchase</title>
        
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
                            <font size="+2">Edit Purchase</font>
                        </div>
                        <div class="panel-body">
                        	<form action="../action/insert.php" method="post" id="purchaseForm" onsubmit="return validate()">    
                                <div class="row">
                                    <div class="table-responsive col-md-12">      	
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="22%"><b>Date <span class="text-danger">*</span> :</b></td>
                                                <td width="28%"><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"/></td>
                                                <td width="22%"><b>Invoice No <span class="text-danger">*</span> :</b></td>
                                               
                                                <td width="28%"><input type="text" name="invoice" class="form-control" value="<?php echo $inv_no;?>" required="required"/></td>
                                                <input type="hidden" name="id" value="<?php echo $id;?>"/>
                                            </tr>
                                            <tr>
                                                <td><b>Supplier Name <span class="text-danger">*</span> :</b></td>
                                                <td>
                                                    <select name="supplier" class="form-control select2" required="required" onchange="getMobile(this.value)">
                                                        <option value="">Select</option>
                                                        <?php
                                                            foreach($suppliers as $supplier){
                                                                $array[$supplier['id']]['phone']=$supplier['phone'];
                                                                $array[$supplier['id']]['state']=$supplier['state'];
																if($supplier['advance']>0){
                                                                	$array[$supplier['id']]['advance']=$supplier['advance'];
																}else{
                                                                	$array[$supplier['id']]['advance']=0;
																}
                                                        ?>
                                                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td><b>Supplier Mobile:</b></td>
                                                <td>
                                                    <input type="text" name="mobile" id="mobile" class="form-control" readonly="readonly"/>
                                                    <input type="hidden" name="state" id="state" class="form-control"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Payment Mode:</b></td>
                                                <td>
                                                    <select name="payment_mode" id="payment_mode" class="form-control" onchange="getCheque(this.value)" >
                                                      <option value="">Select</option>
                                                      <option value="cash">Cash</option>
                                                      <option value="cheque">Cheque</option>
                                                      <option value="online">Online</option>
                                                      <option value="neft">NEFT</option>
                                                      <option value="rtgs">RTGS</option>
                                                      <option value="dd">Demand Draft</option>
                                                    </select>
                                                </td>
                                                <td><b>Type:</b></td>
                                                <td>
                                                    <select name="type" id="type" class="form-control">
                                                        <option value="cash">Cash</option>
                                                        <option value="credit">Credit</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="cheque" style="display:none;">
                                                <td><b>Cheque Date:</b></td>
                                                <td><input type="date" name="cheque_date" class="form-control"/></td>
                                                <td><b>Cheque No:</b></td>
                                                <td><input type="number" name="cheque_no" class="form-control"/></td>
                                            </tr>
                                            <tr class="cheque" style="display:none;">
                                                <td><b>Bank Name:</b></td>
                                                <td>
                                                    <input type="text" name="bank" class="form-control"/>
                                                    <input type="hidden" id="count" value="0" />
                                                    <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                                </td>
                                            </tr>
                                        </table>
                                	</div>
                                </div><!-- end of form row 1 -->
                                <div class="row">
                                    <div class="table-responsive col-md-12">
                                        <table class="table table-condensed" style="margin-bottom:0;">
                                            <tr>
                                                <td width="19%">
                                                    <b>Category</b>
                                                    <select name="category" id="category" class="form-control select2" onchange="getCompany(this.value)">
                                                        <option value="">Select</option>
                                                        <?php foreach($category as $key=>$value){?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="19%">
                                                    <b>Company</b>
                                                    <select name="company_id" id="company_id" class="form-control select2" onchange="getModel(this.value)">
                                                        <option value="">Select</option>
                                                    </select>
                                                </td>
                                                <td width="19%">
                                                    <b>Model</b>
                                                    <select name="model" id="model" class="form-control select2" onchange="selectModel(this.value)">
                                                        <option value="">Select</option>
                                                    </select>
                                                </td>
                                                <td width="19%">
                                                    <b>HSN Code</b>
                                                    <input type="text" name="hsn" id="hsn" class="form-control" />
                                                </td>
                                                <td width="19%">
                                                    <b>UOM</b>
                                                    <input type="text" name="uom" id="uom" class="form-control" />
                                                </td>
                                                <td align="center" style="vertical-align:middle;" rowspan="3" >
                                                    <button type="button" id="addbutton" class="btn btn-primary btn-sm" onclick="return validateAdd();">Add</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="15%">
                                                    <b>MRP</b>
                                                    <input type="text" name="mrp" id="mrp" class="form-control" />
                                                </td>
                                                <td>
                                                    <b>Quantity</b>
                                                    <input type="text" name="quantity" id="quantity" class="form-control" autocomplete="off" />
                                                </td>
                                                <td>
                                                    <b>Rate</b>
                                                    <input type="text" name="purchase" id="purchase" class="form-control" autocomplete="off" />
                                                    <input type="hidden" name="tempbutton" id="tempbutton" /><input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />
                                                </td>
                                                <td>
                                                    <b>Charity</b>
                                                    <input type="text" name="charity" id="charity" class="form-control" />
                                                </td>
                                                <td>
                                                    <b>Discount</b>
                                                    <input type="text" name="discount" id="discount" class="form-control" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Disc.(%)</b>
                                                    <input type="text" name="cust_discount" id="cust_discount" class="form-control" />
                                                </td>
                                                <td>
                                                    <b>Special Disc.(%)</b>
                                                    <input type="text" name="special_discount" id="special_discount" class="form-control" />
                                                </td>
                                                <td>
                                                    <b>Cash Disc.(%)</b>
                                                    <input type="text" name="cash_discount" id="cash_discount" class="form-control" />
                                                </td>
                                                <td align="center" style="vertical-align:middle;">
                                                    <div class="radio">
                                                        <label>
                                                        	<input type="radio" name="gst" id="cgst" value='cgst'><b>CGST <span id="cvalue"></span> &amp;<br />SGST<span id="svalue"></span></b>
                                                      	</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" name="gst" id="igst" value="igst"><b>IGST<span id="ivalue"></span></b></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <b>Tax</b>
                                                    <select name="tax" id="tax" class="form-control">
                                                        <option value="exclude">Excluding Tax</option>
                                                        <option value="include">Including Tax</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table><!-- end of product table -->
                                    </div><!-- end of table div  -->
                                </div><!-- end of form row 2 -->
                                <div class="row">
                                    <div class="text-center" id="response" style="display:none;"></div>
                                    <div class="col-md-12 table-responsive" id="purchase_temp" style="max-height:300px; background-color:#eeeeee; padding:0;">
                                        <?php include('purchase_temp.php'); ?>
                                    </div><!-- end of table div -->
                                </div><!-- end of form row 3 --><br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="14%">Gross Amount</th>
                                                <td width="20%">
                                                	<input type="text" name="gross_amount" id="gross_amount" class="form-control" readonly="readonly" value="<?php echo $amount; ?>" />
                                                </td>
                                                <th width="14%">Transport</th>
                                                <td width="20%">
                                                	<input type="number" name="transport" id="transport" class="form-control" autocomplete="off" onkeyup="calcTransport(this.value);" />
                                                </td>
                                                <th width="14%">Round Off</th>
                                                <td width="15%">
                                                	<input type="text" name="round" id="round" class="form-control" readonly="readonly" value="<?php echo $round; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount</th>
                                                <td>
                                                	<input type="text" name="total_amount" id="total_amount" class="form-control" readonly="readonly" value="<?php echo $total; ?>" />
                                                </td>
                                                <th>Advance</th>
                                                <td><input type="text" name="advance" id="advance" class="form-control" readonly="readonly"/></td>
                                                <th colspan="2" style="text-align:center;">
                                                    <label class="checkbox-inline"><input type="checkbox" name="check_advance" id="check_advance" value="1" />Use Advance</label>
                           						 </th>
                                            </tr>
                                            <tr>
                                                <th>Paid</th>
                                                <td><input type="number" name="paid" id="paid" class="form-control" autocomplete="off" onkeyup="calcDues(this.value)" /></td>
                                                <th>Dues</th>
                                                <td><input type="text" name="dues" id="dues" class="form-control" readonly="readonly" /></td>
                                                <td align="center" style="vertical-align:middle;" colspan="2">
                                                    <input type="submit" name="add_purchase" id="savebutton" value="Save" class="btn btn-success btn-sm" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div><!-- end of form row 4 -->
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
			
			$(document).ready(function(e) {
				$('#check_advance').click(function(){
					var total_amount=$('#total_amount').val();
					var advance=$('#advance').val();
					$('#paid').val('');
					if($(this).is(":checked")){
						var dues=total_amount-advance;
						if(dues<0){dues=0;}
					}
					else{dues=total_amount;}
					$('#dues').val(dues);
					if(dues==0){
						$('#next_payment').attr('readonly',true);
					}
					else{
						$('#next_payment').attr('readonly',false);
					}
				});
			});
	
        	function getMobile(str){
				var id=str;
				var code="<?php echo $state_code; ?>";
				$('#check_advance').attr("checked",false);
				$('#dues').val('');$('#paid').val('');
				if(id!=''){
					var numbers='<?php echo json_encode($array); ?>';	
					var mobiles=JSON.parse(numbers);
					$('#mobile').val(mobiles[id]['phone']);
					var state=mobiles[id]['state'];
					$('#advance').val(mobiles[id]['advance']);
					if(state==code){
						$("input[name=gst][value='cgst']").prop("checked",true);
					}else{
						$("input[name=gst][value='igst']").prop("checked",true);
					}
				}
				else{
					$('#mobile').val('');$('#advance').val(0);$('input[name=gst]').prop("checked",false);
				}
			}
			
			function getCheque(str){
				var mode=str;
				if(mode=='cheque'){
					$('.cheque').show();
				}else{
					$('.cheque').hide();
				}
			}
			
			function resetFields(str){
				if(str=='category'){
					var company="<option value=''>Select Company</option>";
					$('#company_id').html(company);
					$('#cvalue').html(""); $('#svalue').html(""); $('#ivalue').html("");
				}
				if(str=='category' || str=='company'){
					var models="<option value=''>Select Model</option>";
					$('#model').html(models);
				}
				$('#hsn').val('');
				$('#mrp').val('');
				$('#uom').val('');
				$('#purchase').val('');
				$('#quantity').val('');
				$('#charity').val('');
				$('#discount').val('');
				$('#cust_discount').val('');
				$('#special_discount').val('');
				$('#cash_discount').val('');
				$('#tempbutton').val('');
			}
			
			function resetAmount(){
				$('#gross_amount').val($('#temp_amount').val());
				$('#round').val($('#temp_round').val());
				$('#total_amount').val($('#temp_total').val());
				$('#check_advance').attr("checked",false);
				$('#transport').val('');$('#paid').val('');$('#dues').val('');
			}
	
			function getCompany(str){
				var category=str;
				resetFields('category');
				resetAmount();
				var shop='<?php echo $shop; ?>';
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{category:category,shop:shop,get_company:'get_company',page:'purchase'},
						success: function(data){
							$("#company_id").html(data);
							var array='<?php echo json_encode($gst); ?>';	
							var gst=JSON.parse(array);
							var cgst=gst[category]['cgst'];
							var sgst=gst[category]['sgst'];
							var igst=gst[category]['igst'];
							$('#cvalue').html("@ "+cgst+"%"); $('#svalue').html("@ "+sgst+"%"); $('#ivalue').html("@ "+igst+"%");
							
						}	
					});
				}
			}
			
			function getModel(str){
				var company_id=str;
				var category=$('#category').val();
				var shop='<?php echo $shop; ?>';
				resetFields('company');
				resetAmount();
				$.ajax({
					type:'POST',
					url:"../ajax_returns.php",
					data:{company_id:company_id,category:category,get_model:'get_model',page:'purchase',shop:shop},
					success: function(data){
						$('#model').html(data);
					}	
				});
			}
			
			function selectModel(str){
				resetFields('');
				resetAmount();
			}
			function validateAdd(){   //add product
				var model= document.getElementById("model");
				var quantity=document.getElementById("quantity");
				var price=document.getElementById("purchase");
				var shop='<?php echo $shop; ?>';
				$('#addbutton').addClass("disabled");
				if(model.value==''){
					$('#addbutton').removeClass("disabled");
					alert("Select an item!!");
					return false;
				}
				if(quantity.value==''){
					$('#addbutton').removeClass("disabled");
					alert("Enter Quantity!!");
					$('#quantity').focus();
					return false;
				}
				if(price.value=='' || price.value==0){
					$('#addbutton').removeClass("disabled");
					alert("Enter Rate!!");
					$('#purchase').focus();
					return false;
				} 
				else{
					$('#tempbutton').val('add');
					$.ajax({
					   type: "POST",
					   url: "../action/insert.php",
					   data: $("#purchaseForm").serialize(), // serializes the form's elements.
					   success: function(data)
					   {
							$('#category').val('').trigger("change");
							//alert(data); // show response from the php script.
							$('#response').html("<h4 class='text-success'>"+data+"</h4>");
							$('#response').show();
							$('#response').hide(5000);
							$('#addbutton').removeClass("disabled");
							$.ajax({
								type:"GET",
								url:"purchase_temp.php",
								data:{shop:shop},
								success: function(data){
									$('#purchase_temp').html(data);
									resetAmount();
								}
							});
					   }
					 });
					 //e.preventDefault(); // avoid to execute the actual submit of the form.
				}
			}     //add product

			function deleteTemp(str){
				var id=str;
				var shop='<?php echo $shop; ?>';
				if(confirm("Are you sure you want to Delete this?")){
					$.ajax({
						type: 'GET',
						url: '../action/delete.php',
						data: {
							id:id,shop:shop,del_purchase_temp:'del_purchase_temp'
						},
						success: function (data) //on recieve of reply
						{
							//alert(data); // show response from the php script.
							$('#response').html("<h4 class='text-success'>"+data+"</h4>");
							$('#response').show();
							$('#response').hide(5000);
							$.ajax({
								type:"GET",
								url:"purchase_temp.php",
								data:{shop:shop},
								success: function(data){
									$('#purchase_temp').html(data);
									resetAmount();
								}
							});
						}
					});
				}
			}
			
			function calcTransport(str){
				var transport=parseFloat(str);	
				if(isNaN(transport)){transport=0;}
				var amount = parseFloat($('#temp_total').val());
				var total= amount+ transport;
				$('#next_payment').attr('readonly',false);
				$('#dues').val('');$('#paid').val('')
				$('#total_amount').val(total);
				$('#check_advance').attr("checked",false);
				$('#paid').val('');$('#dues').val('');
				
			}
		
			function calcDues(str){
				var paid=parseFloat(str);
				if(isNaN(paid)){paid=0;}
				var total=parseFloat($('#total_amount').val());
				if($('#check_advance').is(":checked")){
					var advance=parseFloat($('#advance').val());
					if(isNaN(advance)){advance=0;}
				}
				else{
					var advance=0;
				}
				var dues=total-advance-paid;
				$('#dues').val(dues)
				if(dues<0){
					d=0-dues;
					alert("Return Rs."+d);
					$('#next_payment').attr('readonly',true);
				}
				else if(dues>0){
					$('#next_payment').attr('readonly',false);
				}
				else{
					$('#next_payment').attr('readonly',true);
				}
			}
	
			
			function validate(){
				var type=$('#type').val();
				var paid=$('#paid').val();
				var final=$('#total_amount').val();
				if(final==0){
					alert("Add Product");
					return false;	
				}
				if(type=='credit'){
					$('#payment_mode').val('');
					$('#paid').val('0');
					$('#dues').val(final);
					$('#check_advance').attr("checked",false);
				}	
				else{
					var mode=$('#payment_mode').val();
					var paid=$('#paid').val();
					if(mode==''){
						if(paid!='' && paid!=0){
							alert("Select Payment Mode");
							$('#payment_mode').focus();
							return false;
						}
						else if($('#check_advance').is(":checked")){
						}
						else{
							$('#type').val('credit');
							$('#paid').val('0');
							$('#dues').val(final);
						}
					}
					else if(paid==''){
						alert("Enter Paid Amount!");
						$('#paid').focus();
						return false;	
					}
				}
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
