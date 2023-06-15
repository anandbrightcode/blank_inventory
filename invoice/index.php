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
	$category=array();
	$selcat=$obj->get_rows("`category`","*","`shop`='$shop' and `id` in (select `category` from stock)","name ASC");
	if(is_array($selcat)){
		foreach($selcat as $arr){
		  $category[$arr['id']]['cgst']=$arr['cgst'];
		  $category[$arr['id']]['sgst']=$arr['sgst'];
		  $category[$arr['id']]['igst']=$arr['igst'];
		}
	}
	$selstate=$obj->get_rows("`state`","`state`,`code`");
	foreach($selstate as $val){
		$states[$val['state']]['code']=$val['code'];
	}
 $shop_details=$obj->get_details("`shop` t1, `state` t2","t1.*,t2.`code` as code","t1.`id`='$shop' and t1.`state`=t2.`id`");
 $state_code=$shop_details['code'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Invoice</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
<script>
	
	function validatePrintdata()
	{
		$('#serbutton').addClass("disabled");
		var sgross_amount= document.getElementById("sgross_amount");
		var stotal_amount= document.getElementById("stotal_amount");
		var type=document.getElementById("type");
		if(billing_mode.value==''){
			$('#serbutton').removeClass("disabled");
			alert("Select Billing Mode!!");
			return false;
		}
		//if(invoice_date.value==''){
//			alert("Select Date!!");
//			return false;
//		}
		if(sgross_amount.value==0){
			$('#savebutton').removeClass("disabled");
			alert("Select Product!");
			return false;
		}
	}

	function validatePrint()
	{
		$('#savebutton').addClass("disabled");
		var gross_amount= document.getElementById("gross_amount");
		var total_amount= document.getElementById("total_amount");
		var paid_amount= document.getElementById("paid");
		var dues_amount= document.getElementById("dues");
		var next_payment=document.getElementById("next_payment");
		var type=document.getElementById("type");
		if(billing_mode.value==''){
			$('#savebutton').removeClass("disabled");
			alert("Select Billing Mode!!");
			return false;
		}
		//if(invoice_date.value==''){
//			alert("Select Date!!");
//			return false;
//		}
	//	if(gross_amount.value==0){
		//	$('#savebutton').removeClass("disabled");
		//	alert("Select Product!");
		//	return false;
	//	}
		if(type.value!='credit'){
			if($('#check_advance').is(":checked")){
			}
			else if(paid_amount.value==''){
				$('#savebutton').removeClass("disabled");
				alert("Enter Paid Amount!!");
				return false;
			}
			else{
				//$('#type').val('credit');
			}
		}
		if(type.value=='credit'){
			$('#paid').val('0');
			$('#dues').val(total_amount.value);
			if(next_payment.value==''){
				$('#savebutton').removeClass("disabled");
				alert("Select Next Payment Date!!");
				return false;
			}
		}
		if(dues_amount.value>0){
			if(next_payment.value==''){
				$('#savebutton').removeClass("disabled");
				alert("Select Next Payment Date!!");
				return false;
			}
		}
		
	}
	

</script>
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
</head>

<body style="background-color:#F6F6F6">
<?php include "../header.php";?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
        <div style="margin-top:25px; z-index:10 " class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
        	<a href="../invoice?pagename=invoice" class="btn btn-danger" style="width:130px;">New Invoice</a>
        </div>
<!--         <div style="margin-top:25px; z-index:10 " class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
        	<a href="../invoice/performa.php?pagename=invoice" class="btn btn-danger" style="width:130px;">Performa Invoice</a>
        </div>
        <div style="margin-top:25px; z-index:10 " class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
        	<a href="../invoice/quotation.php?pagename=invoice" class="btn btn-danger" style="width:130px;">New Quotation</a>
        </div> -->
        <div style="margin-top:25px; z-index:10" class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
        	<a href="dues_payment.php?pagename=invoice" class="btn btn-warning"  style="width:130px;">Dues Payment</a>
        </div>
    </div><!-- end of sidebar column-->
    <div class="col-md-10">
         <div class="row">
           <div class="col-md-12">
              <span class="text-center text-danger"><h2><i class="fa fa-file-text-o"></i> Invoice</h2></span>
              
           </div><!--end of column for invoice -->
         </div><!-- end of row for heading invoice -->
         <br />
        <form action="../action/insert.php" method="post" style="padding-top:5px;" class="row" id="idForm">
        	<div class="row">
            	
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                <b style="padding-left:5px;">Date</b>
                	<input type="date" name="date" id="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>" />
                </div>
            
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                <b>Billing Mode</b>
                	<select name="billing_mode" id="billing_mode" onchange="reset_item(this.value);" class="form-control">
                    	<!--<option value="retail" selected="selected">Retail Invoice</option>-->
                    	<option value="tax">Tax Invoice</option>
                    </select>
                </div>
            	
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                <b>Type</b>
                	<select name="type" id="type" class="form-control">
                    	<option value="debit">Debit</option>
                    	<option value="credit">Credit</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Payment Mode:</b>
                    <select name="payment_mode" id="payment_mode" class="form-control" onchange="getCheque(this.value)">
                    	<option value="">Select</option>
                    	<option value="cash">Cash</option>
                    	<option value="cheque">Cheque</option>
                    	<option value="cash_cheque">Cash + Cheque</option>
                    	<option value="post">POS</option>
                    	<option value="neft">NEFT</option>
                    	<option value="rtgs">RTGS</option>
                    </select>
                </div>
            </div><!-- end of form row 1 -->
            <br />
            <div class="row">
                  <div class="col-lg-4 cheque" style="display:none;">
                     <b>Cheque Date:</b>
                     <input type="date" name="cheque_date" class="form-control"/>
                  </div>
                  <div class="col-lg-4 cheque" style="display:none;">
                     <b>Cheque No:</b>
                     <input type="number" name="cheque_no" class="form-control"/>
                  </div>
                   <div class="col-lg-4 cheque" name="bank" style="display:none;">
                      <b>Bank Name:</b>
                    
                          <input type="text" name="bank" class="form-control"/>
                          <input type="hidden" id="count" value="0" />
                          <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                  </div>
              </div>
                  
              <div class="row">
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Customer Name:</b>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" placeholder="Enter Customer Name" >
                    <div id="cust_suggestion" style="position:absolute; top:40px; width:90%; margin:14px 0; z-index:100 "></div>
                </div>
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Customer Mobile:</b>
                    <input type="text" name="customer_mobile" id="customer_mobile" class="form-control" autocomplete="off" />
                    <input type="hidden" name="customer_id" id="customer_id" class="form-control" />
                    <input type="hidden" id="position" class="form-control" />
                    <input type="hidden" id="direction" class="form-control" />
                </div>
                <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11 hidden" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">From:</b>
                    <textarea name="add_from" id="add_from" class="form-control" style="resize:vertical"><?php echo $shop_details['name'].", ".$shop_details['address'].", ".$shop_details['district']; ?></textarea>
                </div>
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Address:</b>
                    <textarea name="add_to" id="add_to" class="form-control" style="resize:vertical"></textarea>
                </div>
            </div><!-- end of form row 2 -->
            <br />
            <div class="row">
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">GSTIN:</b>
                    <input type="text" name="gst" id="gst" class="form-control" />
                </div>
            	
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">State:</b>
                    <select name="state" id="state" class="form-control" onchange="getCode(this.value,'')" required="required">
                    	<option value="">Select</option>
                        <?php
                        	foreach($states as $index=>$state ){?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                        <?php } ?>
                    </select>
                </div>
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">Code:</b>
                    <input type="text" name="code" id="code" class="form-control" readonly="readonly" />
                </div>
            </div><!-- end of form row 3 -->
                  <br />
        <?php /*?>    <div class="row">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label><input type="checkbox" value="" id="sameas">Consignee same as Customer</label>
                    </div>
                </div>
            </div>
              <div class="row">
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Consignee Name:</b>
                    <input type="text" name="consignee_name" id="consignee_name" class="form-control" autocomplete="off" placeholder="Enter Consignee Name" >
                </div>
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Consignee Mobile:</b>
                    <input type="text" name="consignee_mobile" id="consignee_mobile" class="form-control" autocomplete="off" />
                </div>
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Consignee Address:</b>
                    <textarea name="consignee_address" id="consignee_address" class="form-control" style="resize:vertical"></textarea>
                </div>
            </div><!-- end of form row 2 -->
            <br />
            <div class="row">
            	<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">GSTIN:</b>
                    <input type="text" name="consignee_gst" id="consignee_gst" class="form-control" />
                </div>
                <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Transport Mode:</b>
                    <input type="text" name="transport_mode" id="transport_mode" class="form-control" />
                </div>
				<div class="col-lg-4 col-md-4 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">Order Reference:</b>
                    <input type="text" name="po" id="po" class="form-control" />
                </div>
            </div><!-- end of form row 3 -->
            
            <div class="row">
            	
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;">
                	<b style="padding-left:5px">State:</b>
                    <select name="consignee_state" id="consignee_state" class="form-control" onchange="getCode(this.value,'consignee_')" required="required">
                    	<option value="">Select</option>
                        <?php
                        	foreach($states as $index=>$state ){?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                        <?php } ?>
                    </select>
                </div>
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">Code:</b>
                    <input type="text" name="consignee_code" id="consignee_code" class="form-control" readonly="readonly" />
                </div>
            	<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">Reverse Charge (Y/N):</b>
                    <select name="reverse" class="form-control">
                    	<option>No</option>
                    	<option>Yes</option>
                    </select>
                </div>
				<div class="col-lg-3 col-md-3 col-sm-11 col-xs-11" style="margin-bottom:4px; margin-top:4px;" >
                	<b style="padding-left:5px">Type</b>
                    <select name="stype" id="stype" class="form-control">
						<option value="select">Select</option>
                    	<option value="product">Product</option>
                    	<option value="service">Service</option>
                    </select>
                </div>

            </div><!-- end of form row 4 -->
            <?php */?>
            <br />
             <div class="row" id="productdiv">
            	<div class="table-responsive">
                	<table class="table table-condensed" style="margin-bottom:0;">
                    	<tr>
                            <td width="20%">
                            	<b>Category</b>
                            	<select name="category" id="category" class="form-control" onchange="getCompany(this.value)">
                                    <option value="">Select</option>
                                    <?php if(is_array($selcat))foreach($selcat as $value){?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td width="20%">
                            	<b>Company</b>
                                <select name="company_id" id="company_id" class="form-control" onchange="getModel(this.value)">
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td width="20%">
                            	<b>Model</b>
                                <select name="model" id="model" class="form-control" onchange="selectModel(this.value)">
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td width="20%">
                            	<b>HSN Code</b>
                            	<input type="text" name="hsn" id="hsn" class="form-control" />
                            </td>
                            <td width="20%">
                            	<b>MRP</b>
                            	<input type="text" name="mrp" id="mrp" class="form-control" />
                            </td>
                        	<td align="center" style="vertical-align:middle;" rowspan="3">
                            	<button type="button" id="addbutton" class="btn btn-primary btn-sm" onclick="return validateAdd();">Add</button>
                            </td>
                        </tr>
                    	<tr>
                            <td>
                            	<b>UOM</b>
                            	<input type="text" name="uom" id="uom" class="form-control" />
                            </td>
                            <td>
                            	<b>Sl. No</b>
                            	<input type="text" name="slno" id="slno" class="form-control" />
                            </td>
                            <td>
                            	<b>Available Quantity</b>
                            	<input type="text" name="avl_quantity" id="avl_quantity" class="form-control" readonly="readonly" />
                            </td>
                            <td>
                            	<b>Rate</b>
                            	<input type="text" name="price" id="price" class="form-control" />
                            </td>
                            <td>
                            	<b>Quantity</b>
                            	<input type="text" name="quantity" id="quantity" class="form-control" autocomplete="off" onkeyup="checkQuantity(this.value);" />
                            	<input type="hidden" name="sbutton" id="sbutton" /><input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />
                            </td>
                       	</tr>
                        <tr>
                            <td>
                            	<b>Charity</b>
                            	<input type="text" name="charity" id="charity" class="form-control" />
                            </td>
                            <td>
                            	<b>Discount</b>
                            	<input type="text" name="discount" id="discount" class="form-control" />
                            </td>
                            <td>
                            	<b>Customer Disc(%)</b>
                            	<input type="text" name="custdiscount" id="custdiscount" class="form-control" />
                            </td>
                        	<td align="center" style="vertical-align:middle;">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="gstval" id="cgst" value='cgst'><b>CGST <span id="cvalue"></span> &amp;<br />SGST<span id="svalue"></span></b>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="gstval" id="igst" value="igst"><b>IGST<span id="ivalue"></span></b></label>
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
            </div><!-- end of form row 5 -->
				</br>
			
            <div class="row" style="display:none" id="servicediv">
            	<div class="table-responsive">
                	<table class="table table-condensed" style="margin-bottom:0;">
                    	<tr>
                            <td width="20%">
                            	<b>Description</b>
								<textarea name="description" id="description" class="form-control" /></textarea>
                            </td>
                            <td width="20%">
                            	<b>HSN Code</b>
								<input type="text" name="shsn" id="shsn" class="form-control" />
								<input type="hidden" name="servicebutton" id="servicebutton" /><input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />

                            </td>
                            <td width="20%">
                            	<b>UOM</b>
                            	<input type="text" name="suom" id="suom" class="form-control" />
                            </td>
                            <td width="20%">
                            	<b>Quantity</b>
                            	<input type="text" name="squantity" id="squantity" class="form-control" />
                            </td>
                        	<td align="center" style="vertical-align:middle;" rowspan="3">
                            <button type="button" id="addservicebutton" class="btn btn-primary btn-sm" onclick="return servicevalidateAdd();">Add</button>
                            </td>
                        </tr>
                    	<tr>
                            <td>
                            	<b>Rate</b>
                            	<input type="text" name="srate" id="srate" class="form-control" />
                            </td>
                            <td>
                            	<b>GST(%)</b>
                            	<input type="text" name="ser_gst" id="ser_gst" class="form-control" />
                            </td>
                       	</tr>
                    </table><!-- end of product table -->
                </div><!-- end of table div  -->
            </div><!-- end of form row 5 -->
			</br>
            <div class="row" id="producttemp">
            	<div class="text-center" id="response" style="display:none;"></div>
            	<div class="col-md-12 table-responsive" id="invoice_temp" style="max-height:300px; background-color:#FFFFFF; padding:0;">
                	<?php include('invoice_temp.php'); ?>
                </div><!-- end of table div -->
            </div><!-- end of form row 6 -->
  			 </br>
            <div class="row" id="servicetemp"  style="display:none;">
            	<div class="text-center" id="response" style="display:none;"></div>
            	<div class="col-md-12 table-responsive" id="service_temp" style="max-height:300px; background-color:#FFFFFF; padding:0;">
                	<?php include('service_temp.php'); ?>
                </div><!-- end of table div -->
            </div><!-- end of form row 6 -->
            <br />          
			<div class="row" id="productpay">
            	<div class="col-md-12">
                	<table class="table table-bordered">
                    	<tr>
                        	<th width="14%">Gross Amount</th>
                            <td width="20%"><input type="text" name="gross_amount" id="gross_amount" class="form-control" readonly="readonly" value="<?php echo $amount; ?>" /></td>
                        	<th width="14%">Transport</th>
                            <td width="20%"><input type="number" name="transport" id="transport" class="form-control" autocomplete="off" onkeyup="calcTransport(this.value);" /></td>
                        	<th width="14%">Round Off</th>
                            <td width="15%"><input type="text" name="round" id="round" class="form-control" readonly="readonly" value="<?php echo $round; ?>" /></td>
                        </tr>
                    	<tr>
                        	<th>Total Amount</th>
                            <td><input type="text" name="total_amount" id="total_amount" class="form-control" readonly="readonly" value="<?php echo $total; ?>" /></td>
                        	<th>Advance</th>
                            <td><input type="text" name="advance" id="advance" class="form-control" readonly="readonly"/></td>
                        	<th colspan="2" style="text-align:center;">
                            	<label class="checkbox-inline"><input type="checkbox" name="check_advance" id="check_advance" value="1" disabled="disabled" />Use Advance</label>
                            </th>
                        </tr>
                        <tr>
                        	<th>Paid</th>
                            <td><input type="number" name="paid" id="paid" class="form-control" autocomplete="off" onkeyup="calcDues(this.value)" /></td>
                        	<th>Dues</th>
                            <td><input type="text" name="dues" id="dues" class="form-control" readonly="readonly" /></td>
                        	<th>Next Payment</th>
                            <td><input type="date" name="next_payment" id="next_payment" class="form-control" /></td>
                        </tr>
                        <tr>
                            <td align="center" style="vertical-align:middle;" colspan="6">
                            	<input type="submit" name="save_invoice" id="savebutton" value="Save &amp; Print" class="btn btn-success btn-sm" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- end of form row 7 -->
			<div class="row" id="servicepay" style="display:none;">
            	<div class="col-md-12">
                	<table class="table table-bordered">
                    	<tr>
                        	<th width="14%">Gross Amount</th>
                            <td width="20%"><input type="text" name="sgross_amount" id="sgross_amount" class="form-control" readonly="readonly" value="<?php echo $samount; ?>" /></td>
							<th>Total Amount</th>
                            <td><input type="text" name="stotal_amount" id="stotal_amount" class="form-control" readonly="readonly" value="<?php echo $stotal; ?>" /></td>
                        	<th width="14%">Round Off</th>
                            <td width="15%"><input type="text" name="sround" id="sround" class="form-control" readonly="readonly" value="<?php echo $sround; ?>" /></td>
                        </tr>
                        <tr>
                            <td align="center" style="vertical-align:middle;" colspan="6">
                            	<input type="submit" name="service_invoice" id="serbutton" value="Save &amp; Print" class="btn btn-success btn-sm" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- end of form row 8 -->

       	</form><!-- end of form -->
    </div><!-- end of col-md-10 for main content -->
  </div><!-- end of row -->
</div><!-- end of container --><br /><br>
<script type="text/javascript">
    $(function () {
        $("#stype").change(function () {
            if ($(this).val() == "service") {
				$("#servicediv").show();
				$("#productdiv").hide();
				$("#productpay").hide();
				$("#producttemp").hide();
				$("#servicetemp").show();
				$("#servicepay").show();
				}
				if ($(this).val() == "product") {
				$("#productdiv").show();
                $("#servicediv").hide();
				$("#servicetemp").hide();
				$("#productpay").show();
				$("#producttemp").show();
				$("#servicepay").hide();
				}
        });
    });
</script>

<script type="text/javascript" language="javascript"> 

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
        $('body').on('change','#sameas', function(e) {
            if(this.checked){
                $('#consignee_name').val($('#customer_name').val());
                $('#consignee_mobile').val($('#customer_mobile').val());
                $('#consignee_address').val($('#add_to').val());
                $('#consignee_gst').val($('#gst').val());
                $('#consignee_state').val($('#state').val());
                $('#consignee_code').val($('#code').val());
            }else{
                $('#consignee_name').val('');
                $('#consignee_mobile').val('');
                $('#consignee_address').val('');
                $('#consignee_gst').val('');
                $('#consignee_state').val('');
                $('#consignee_code').val('');
            }
        });
    });
	
	function getCheque(str){
				var mode=str;
				if(mode=='cheque'){
					$('.cheque').show();
				}else{
					$('.cheque').hide();
				}
			}
	function getCode(str,id){
		var code='<?php echo $state_code; ?>';
		var state=str;	
		var array='<?php echo json_encode($states); ?>';	
		var codes=JSON.parse(array);	
        id='#'+id+'code';
		if(state!=''){
			$(id).val(codes[state]['code']);
			if(codes[state]['code']==code){
				$("input[name=gstval][value='cgst']").prop("checked",true);
			}else{
				$("input[name=gstval][value='igst']").prop("checked",true);
			}
		}
		else{ $("input[name=gstval]").prop("checked",false); $(id).val('');}
	}
	
	$('#customer_name').bind('keyup', function(e) {
		$("#customer_id").val("");
		$('#customer_mobile').val('');	
		$("textarea#add_to").val('');
		$("#gst").val('');
		$("#advance").val('');
		$("#dues").val('');
		$('#check_advance').attr("disabled","true");
		$('#position').val('0');
		$('#check_advance').trigger("click");
		$('#check_advance').attr("checked",false);
		if(e.keyCode==38 || e.keyCode==40){
			var position=1;
			var id="#list"+position;
			$('.btns').removeClass("active");
			 $(id).focus().addClass("active");
			$('#position').val(position);
			$('#direction').val('down');
		}
		else if($(this).val()!=''){
			$('#position').val('0');
			$('#direction').val('');
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data:{keyword:$(this).val(),get_customer:'get_customer'},
				beforeSend: function(){
					$("#name").css("background","#FFF url(LoaderIcon.gif) no-repeat 250px");
				},
				success: function(data){
					$("#cust_suggestion").show();
					$("#cust_suggestion").html(data);
					$("#name").css("background","#FFF");
				}
			});		
		}
		else{
			$("#cust_suggestion").hide();
			$('#position').val('0');
			$('#direction').val('');
		}
	});
	$('body').bind('keyup', function(e) {
		var position=$('#position').val();
		var count=$('#count').val();
		var direction=$('#direction').val();
		if(position>=1){
			if(e.keyCode==40 && position!=count+1){
				if(direction=='up'){position++;}
				var id="#list"+position;
				$('.btns').removeClass("active");
				 $(id).focus().addClass("active");
				 position++;
				$('#position').val(position);
				$('#direction').val('down');
			}
			else if(e.keyCode==38){
				if(direction=='down'){position--;}
				position--;
				if(position<=0){
					$('#customer_name').focus();
					$('.btns').removeClass("active");
					$('#position').val('0');
					$('#direction').val('up');
				}
				else{
					var id="#list"+position;
					$('.btns').removeClass("active");
					$(id).focus().addClass("active");
					$('#position').val(position);
					$('#direction').val('up');
				}
			}
			else if(e.keyCode==37 || e.keyCode==39 ){
				$('#customer_name').focus();
				$('.btns').removeClass("active");
				$('#position').val('0');
				$('#direction').val('');
			}
		}
	});
	
	 
	function selectCustomer(id) { 	//select customer
		$('#position').val('0');
		$("#customer_id").val(id);
		$.ajax({
			type:"POST",
			url:"../ajax_returns.php",
			data:{id:id,getCustomer:'getCustomer'},
			dataType:"json",
			success: function(data){
				$("#customer_name").val(data['name']);
				$('#customer_mobile').val(data['phone']);	
				$("textarea#add_to").val(data['address']);	
				if(data['advance']>0){
					$("#advance").val(data['advance']);
				}
				else{
					$("#advance").val(0);
				}
				$("#gst").val(data['gst']);
				$("#cust_suggestion").hide();
				$('#check_advance').removeAttr("disabled");
			}
		});
		
	} //select customer
	
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
		$('#slno').val('');
		$('#avl_quantity').val('');
		$('#price').val('');
		$('#quantity').val('');
		$('#charity').val('');
		$('#discount').val('');
		$('#custdiscount').val('');
		$('#description').val('');
		$('#shsn').val('');
		$('#suom').val('');
		$('#srate').val('');
		$('#squantity').val('');
		$('#ser_gst').val('');
		$('#servicebutton').val('');
		$('#sbutton').val('');
		
	}
	
	function resetAmount(){
		$('#gross_amount').val($('#temp_amount').val());
		$('#round').val($('#temp_round').val());
		$('#total_amount').val($('#temp_total').val());
		$('#check_advance').attr("checked",false);
		$('#transport').val('');$('#paid').val('');$('#dues').val('');
		$('#sgross_amount').val($('#temp_samount').val());
		$('#sround').val($('#temp_sround').val());
		$('#stotal_amount').val($('#temp_stotal').val());
	}
	
	function getCompany(str){	
		var category=str;
		var shop='<?php echo $shop; ?>';
		resetFields('category');
		resetAmount();
		if(category!=''){
			var array='<?php echo json_encode($category); ?>';	
			var gst=JSON.parse(array);
			var cgst=gst[category]['cgst'];
			var sgst=gst[category]['sgst'];
			var igst=gst[category]['igst'];
			$('#cvalue').html("@ "+cgst+"%"); $('#svalue').html("@ "+sgst+"%"); $('#ivalue').html("@ "+igst+"%");
			$.ajax({
				type:'POST',
				url:"../ajax_returns.php",
				data:{category:category,shop:shop,get_company:'get_company',page:'invoice'},
				success: function(data){
					$('#company_id').html(data);
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
		if(company_id!=''){
			$.ajax({
				type:'POST',
				url:"../ajax_returns.php",
				data:{company_id:company_id,shop:shop,category:category,get_model:'get_model',page:'invoice'},
				success: function(data){
					$('#model').html(data);
				}	
			});
		}
	}
	
	function selectModel(str){
		var model=str;
		var category=$('#category').val();
		var company_id=$('#company_id').val();
		var shop='<?php echo $shop; ?>';
		resetFields('');
		resetAmount();
		if(model!=''){
			$.ajax({
				type:'POST',
				url:"../ajax_returns.php",
				data:{company_id:company_id,category:category,model:model,shop:shop,select_model:'select_model',page:'invoice'},
				dataType:"json",
				success: function(data){
					var hsn=data['hsn'];
					var mrp=data['mrp'];
					var uom=data['uom'];
					var price=data['selling_price'];
					var quantity=data['quantity'];
					$('#hsn').val(hsn);
					$('#mrp').val(mrp);
					$('#uom').val(uom);
					$('#avl_quantity').val(quantity);
					$('#quantity').val('');
					$('#price').val(price);
				}	
			});
		}
	}
	
	function checkQuantity(data){
		var quantity=parseInt(data);
		var avl=parseInt($('#avl_quantity').val());
		if(quantity>avl){
			alert("Quantity not available!");	
			$('#quantity').val('');
		}
	}
	
	
	function servicevalidateAdd(){   //add product
	var squantity=document.getElementById("squantity");
	
		var shop='<?php echo $shop; ?>';
		$('#addservicebutton').addClass("disabled");
		if(squantity.value==''){
			$('#addservicebutton').removeClass("disabled");
			alert("Enter Desired Quantity!!");
			$('#squantity').focus();
			return false;
		}
		else{
			$('#servicebutton').val('add');
			$.ajax({
			   type: "POST",
			   url: "../action/insert.php",
			   data: $("#idForm").serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				   	//$("#category").val("").trigger("change");
				   	//alert(data); // show response from the php script.

					$('#response').html("<h4 class='text-success'>"+data+"</h4>");
					$('#response').show();
					$('#response').slideUp(6000);
					$('#addservicebutton').removeClass("disabled");
					$.ajax({
						type:"GET",
						url:"service_temp.php",
						data:{shop:shop},
						success: function(data){
							$('#service_temp').html(data);
							resetFields();
							resetAmount();
						}
					});
			   }
			 });
			 //e.preventDefault(); // avoid to execute the actual submit of the form.
		}
	}  

	function validateAdd(){   //add product
		var model= document.getElementById("model");
		var quantity=document.getElementById("quantity");
		var price=document.getElementById("price");
		var shop='<?php echo $shop; ?>';
		$('#addbutton').addClass("disabled");
		if(model.value==''){
			$('#addbutton').removeClass("disabled");
			alert("Select an item!!");
			return false;
		}
		if(price.value=='' || price.value==0){
			$('#addbutton').removeClass("disabled");
			alert("Enter Rate!!");
			$('#price').focus();
			return false;
		} 
		if(quantity.value==''){
			$('#addbutton').removeClass("disabled");
			alert("Enter Desired Quantity!!");
			$('#quantity').focus();
			return false;
		}
		else{
			$('#sbutton').val('add');
			$.ajax({
			   type: "POST",
			   url: "../action/insert.php",
			   data: $("#idForm").serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				   	$("#category").val("").trigger("change");
				   	//alert(data); // show response from the php script.
					$('#response').html("<h4 class='text-success'>"+data+"</h4>");
					$('#response').show();
					$('#response').slideUp(6000);
					$('#addbutton').removeClass("disabled");
					$.ajax({
						type:"GET",
						url:"invoice_temp.php",
						data:{shop:shop},
						success: function(data){
							$('#invoice_temp').html(data);
							resetAmount();
						}
					});
			   }
			 });
			 //e.preventDefault(); // avoid to execute the actual submit of the form.
		}
	}     //add product

 	function deleteTemp(str1,str2,str3){
		var id=str1; var quantity=str2; var ready_id=str3;
		var shop='<?php echo $shop; ?>';
		if(confirm("Are you sure you want to Delete this?")){
			$.ajax({
				type: 'GET',
				url: '../action/delete.php',
				data: {
					id:id,shop:shop,quantity:quantity,ready_id:ready_id,delete_temp:'delete_temp'
				},
				success: function (data) //on recieve of reply
				{
					//alert(data); // show response from the php script.
					$('#response').html("<h4 class='text-success'>"+data+"</h4>");
					$('#response').show();
					$('#response').slideUp(6000);
					$.ajax({
						type:"GET",
						url:"invoice_temp.php",
						data:{shop:shop},
						success: function(data){
							$('#invoice_temp').html(data);
							resetAmount();
						}
					});
					
				}
			});
		}
	}
	
	function deleteTempservice(str){
		var id=str;
		var shop='<?php echo $shop; ?>';
		if(confirm("Are you sure you want to Delete this?")){
			$.ajax({
				type: 'GET',
				url: '../action/delete.php',
				data: {
					id:id,shop:shop,delete_temp_service:'delete_temp_service'
				},
				success: function (data) //on recieve of reply
				{
					//alert(data); // show response from the php script.
					$('#response').html("<h4 class='text-success'>"+data+"</h4>");
					$('#response').show();
					$('#response').slideUp(6000);
					$.ajax({
						type:"GET",
						url:"service_temp.php",
						data:{shop:shop},
						success: function(data){
							$('#service_temp').html(data);
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
	
</script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
</body>
</html>
