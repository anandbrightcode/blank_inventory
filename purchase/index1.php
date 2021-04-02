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
  $category=array("TV","LED","Refrigerator","Washing Machine","A/C","Purifier","Geyser","Mixer","Emergency Light","Induction","Invertor","Home Theatre","Microwave Oven");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Expenses</title>
        
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
                            <font size="+2">Add Purchase</font>
                            <button class="btn btn-warning pull-right" onclick="showThis('list');">View Expenses</button>
                        </div>
                        <div class="panel-body">
                        	<form action="../action/inser.php" method="post" id="book" onsubmit="return validate()">          	
            					<table class="table table-bordered">
                                    <tr>
                                        <td><b>Date:</b></td>
                                        <td><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"/></td>
                                        <td><b>Bill No:</b></td>
                                        <td><input type="text" name="bill" class="form-control" required="required"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Supplier Name:</b></td>
                                        <td>
                                            <select name="supplier" class="form-control" required="required" onchange="getMobile(this.value)">
                                                <option value="">Select</option>
                                                <?php
													$suppliers=$obj->get_rows("`supplier`","`id`,`name`,`phone`");
													foreach($suppliers as $supplier){
                                                        $array[$supplier['id']]=$supplier['phone'];
                                                ?>
                                                <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td><b>Supplier Mobile:</b></td>
                                        <td><input type="text" name="mobile" id="mobile" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Payment Mode:</b></td>
                                        <td>
                                            <select name="payment_mode" class="form-control" onchange="getCheque(this.value)" >
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
                                                <option value="debit">Debit</option>
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
                                        <td><input type="text" name="bank" class="form-control"/></td>
                                    </tr>
                                </table>
                           <!-- <input type="button" value="Remove" onClick="deleteRow('dataTable')" class="btn btn-danger" style="margin-top:20px;" /> -->
                                     <table id="dataTable" class="table table-bordered">
                                       <tr>
                                         <td width="14%">
                                         	<select name="category[]" id="category0" class="form-control" onchange="getCompany(0,this.value)">
                                                <option value="">Select Category</option>
                                                <?php foreach($category as $val){ 
												?>
                                                <option><?php echo $val; ?></option>
                                                <?php } ?>
                                            </select>
                                         </td>
                                         <td width="14%">
                                            <select name="company_id[]" id="company_id0" class="form-control" onchange="getModel(0,this.value)">
                                                <option value="">Select Company</option>
                                            </select>
                                         </td>
                                         <td width="14%">
                                            <select name="model[]" id="model0" class="form-control">
                                                <option value="">Select Model</option>
                                            </select>
                                         </td>
                                         <td width="14%"><input type="text" name="slno[]" class="form-control"  placeholder="Serial No."/></td>
                                         <td width="14%"><input type="text" name="hsn[]" class="form-control"  placeholder="HSN/SAC"/></td>
                                         <td width="14%"><input type="number" name="quantity[]" class="form-control" placeholder="Quantity" /></td>
                                         <td><input type="number" name="purchase[]" class="form-control" placeholder="Total Purchase" autocomplete="off" /></td>
                                         <!-- <td><input type="checkbox" required="required" name="chk[]" checked="checked" style="height:20px; width:20px; margin-left:5px;"/>
                                         </td> -->
                                        </tr> 
                                     
                                   </table>
                                    <div class="row"><div class="col-md-12"><input type="button" value="Add New Item" onClick="addRow('dataTable')" class="btn btn-warning" /></div></div><br />
                                   <div class="row">
                                   <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td colspan="3" align="center"><button type="button" class="btn btn-info" onclick="getAmount()">Calculate</button></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Gross Amount</th>
                                                <td colspan="2"><input type="text" name="gross_amount" id="gross_amount" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">CGST</th>
                                                <td><input type="number" name="cgst" id="cgst" class="form-control calc" autocomplete="off" /></td>
                                                <td><input type="text" name="cvalue" id="cvalue" class="form-control " readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">SGST</th>
                                                <td><input type="number" name="sgst" id="sgst" class="form-control calc" autocomplete="off" /></td>
                                                <td><input type="text" name="svalue" id="svalue" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">IGST</th>
                                                <td><input type="number" name="igst" id="igst" class="form-control calc" autocomplete="off" /></td>
                                                <td><input type="text" name="ivalue" id="ivalue" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Net Amount</th>
                                                <td colspan="2"><input type="text" name="net_amount" id="net_amount" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                   <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="text-align:center;">Discount</th>
                                                <td><input type="number" name="dpercent" id="dpercent" class="form-control calc" autocomplete="off" /></td>
                                                <td><input type="text" name="dvalue" id="dvalue" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Total Amount</th>
                                                <td colspan="2"><input type="text" name="total" id="total" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Transport</th>
                                                <td colspan="2"><input type="text" name="transport" id="transport" class="form-control" autocomplete="off" onkeyup="getFinal(this.value)"/></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Final Amount</th>
                                                <td colspan="2"><input type="text" name="final_amount" id="final_amount" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Paid</th>
                                                <td colspan="2"><input type="number" name="paid" id="paid" class="form-control" autocomplete="off" onkeyup="calcDues(this.value)" /></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:center;">Dues</th>
                                                <td colspan="2"><input type="text" name="dues" id="dues" class="form-control" readonly="readonly" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                    </div>
                                   <table>
                                    <tr>
                                        <td colspan="3">
                                            <input type="submit" id="supplier_input13" name="save" value="Save" class="btn btn-success" />
                                        
                                        <!--	<button id="supplier_input13" type="button" >Print</button> -->
                                            <a href="purchase_order.php?pagename=report" class="btn btn-info">
                                                View Purchases
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                    <div class="panel panel-danger" id="listPanel" style="display:none;">
                        <div class="panel-heading">
                            <font size="+2">Daily Expenses</font>
                            <button class="btn btn-info pull-right" onclick="showThis('form');">Add Expense</button>
                        </div><!-- panel-heading closed-->
                        <div class="panel-body">
                            <input type="text" placeholder="Search Expense By Name Or Id" class="form-control" onkeyup="getExpenseData(this.value)" id="c_search">
                            <br>
                            <div id="query_result">
                                <?php include "expenselist.php" ;?>	  
                            </div>
                        </div>
                    </div><!-- list panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
			
			function addRow(str){
				var table=str;
				var count=$('#count').val();
				count++;
				$('#count').val(count);
				var first= "<select name='category[]' id='category"+count+"' class='form-control' onchange='getCompany("+count+",this.value)' ><option value=''>Select Category</option><?php foreach($category as $val){ ?><option><?php echo $val; ?></option><?php } ?></select>";
				var col2="<td><select name='company_id[]' id='company_id"+count+"' class='form-control' onchange='getModel("+count+",this.value)'><option value=''>Select Company</option></select></td>"; 
				var col3="<td><select name='model[]' id='model"+count+"' class='form-control'><option value=''>Select Model</option></select></td>"; 
				var col4="<td><input type='text' name='slno[]' class='form-control'  placeholder='Serial No.'/></td>";
				var col5="<td><input type='text' name='hsn[]' class='form-control'  placeholder='HSN/SAC'/></td>";
				var col6="<td><input type='number' name='quantity[]' class='form-control'  placeholder='Quantity'/></td>";
				var col7="<td><input type='number' name='purchase[]' class='form-control'  placeholder='Total Purchase'/></td>";
				
				$('#dataTable tr:last').after("<tr><td>"+first+"</td>"+col2+col3+col4+col5+col6+col7+"</tr>");
				
			}
        	function getMobile(str){
				var id=str;
				var numbers='<?php echo json_encode($array); ?>';	
				var mobiles=JSON.parse(numbers);
				$('#mobile').val(mobiles[id]);
			}
			
			function getCheque(str){
				var mode=str;
				if(mode=='cheque'){
					$('.cheque').show();
				}else{
					$('.cheque').hide();
				}
			}
			
			
			function getCompany(str,str2){
				var id='#company_id'+str;	
				var category=str2;
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{category:category,get_company:'get_company'},
						success: function(data){
							$(id).html(data);
						}	
					});
				}
			}
			function getModel(str,str2){
				var id='#model'+str;	
				var company_id=str2;
				var cid="#category"+str;
				var category=$(cid).val()
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{company_id:company_id,category:category,get_model:'get_model'},
						success: function(data){
							$(id).html(data);
						}	
					});
				}
			}
	
	
			function getAmount(){
				var amount=0;
				$("input[name='purchase[]']").each(function() {
					if($(this).val()!=''){
						amount +=parseFloat($(this).val());
					}
				});
				if(isNaN(amount)){amount=0;}
				$('#gross_amount').val(amount)
				$('#net_amount').val(amount)
				$('#total').val(amount)
				$('#final_amount').val(amount)
			}
			
			
			$(function () {
				$(".calc").keyup(function(){
					var gross=$('#gross_amount').val();
					var sgst=$('#sgst').val();
					var cgst=$('#cgst').val();
					var igst=$('#igst').val();
					var dPercent=$('#dpercent').val();
					if(sgst==''){sgst=0;}else{var s=sgst.replace(/^0+/, ''); sgst=parseFloat(sgst);} $('#sgst').val(s);
					if(cgst==''){cgst=0;}else{var c=cgst.replace(/^0+/, ''); cgst=parseFloat(cgst);} $('#cgst').val(c);
					if(igst==''){igst=0;}else{var e=igst.replace(/^0+/, ''); igst=parseFloat(igst);} $('#igst').val(e);
					if(dPercent==''){dPercent=0;}else{var d=dPercent.replace(/^0+/, ''); dPercent=parseFloat(dPercent);} $('#dpercent').val(d);
					var cgstValue=(cgst/100)*gross; cgstValue=Number(cgstValue).toFixed(2);  $('#cvalue').val(cgstValue);
					var sgstValue=(sgst/100)*gross; sgstValue=Number(sgstValue).toFixed(2);  $('#svalue').val(sgstValue);
					var igstValue=(igst/100)*gross; igstValue=Number(igstValue).toFixed(2);  $('#ivalue').val(igstValue);
					var net=parseFloat(gross)+parseFloat(cgstValue)+parseFloat(sgstValue)+parseFloat(igstValue);
					net=Number(net).toFixed(2);
					$('#net_amount').val(net);
					$('#total').val(net);
					var dValue=(dPercent/100)*net; dValue=Number(dValue).toFixed(2); $('#dvalue').val(dValue);
					var total=parseFloat(net)-parseFloat(dValue);
					total=Math.round(total);
					$('#total').val(total);
				});
			});
			
			function getFinal(str){
				var transport=str;
				var total=$('#total').val();
				if(transport!='' && total!=''){
					transport=parseFloat(transport);
					total=parseFloat(total);
					total+=transport;
					$('#final_amount').val(total);	
				}
				else{
					$('#transport').val('');	
				}
			}
			
			function calcDues(str){
				var paid	=str;
				var total=$('#final_amount').val();
				if(total!=0){
					var dues=total-paid;
					$('#dues').val(dues);	
				}
				else{
					$('#paid').val('0');
					$('#dues').val('0');	
				}
				
			}
			
			function validate(){
				var type=$('#type').val();
				var final=$('#final_amount').val();
				if(type=='credit'){
					$('#paid').val('0');
					$('#dues').val(final);
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
