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
	$gst=array();
	$selcat=$obj->get_rows("`category`","*","`shop`='$shop'");
	if(is_array($selcat)){
		foreach($selcat as $arr){
		  //$category[]=$arr['name'];
		  $gst[$arr['id']]['cgst']=$arr['cgst'];
		  $gst[$arr['id']]['sgst']=$arr['sgst'];
		  $gst[$arr['id']]['igst']=$arr['igst'];
		}
	}
 $array=array();
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
                            <font size="+2">Add Purchase</font>
                        </div>
                        <div class="panel-body">
                        	<form action="../action/insert.php" method="post" id="book" onsubmit="return validate()">          	
            					<table class="table table-bordered">
                                    <tr>
                                        <td><b>Date:</b></td>
                                        <td><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"/></td>
                                        <td><b>Invoice No:</b></td>
                                        <td><input type="text" name="invoice" class="form-control" required="required"/></td>
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
                                        <td>
                                            <input type="text" name="bank" class="form-control"/>
                                            <input type="hidden" id="count" value="0" />
                                            <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                        </td>
                                    </tr>
                                </table>
                           <!-- <input type="button" value="Remove" onClick="deleteRow('dataTable')" class="btn btn-danger" style="margin-top:20px;" /> -->
                                      <table id="dataTable" class="table table-bordered">
                                      <tr>
                                      	<th style="text-align:center;">Category</th>
                                      	<th style="text-align:center;">Company</th>
                                      	<th style="text-align:center;">Model</th>
                                      	<th style="text-align:center;">MRP</th>
                                      	<th style="text-align:center;">UOM</th>
                                      	<th style="text-align:center;">HSN</th>
                                      	<th style="text-align:center;">Pieces</th>
                                      	<th style="text-align:center;">Quantity(Kg/pc)</th>
                                      	<th style="text-align:center;">Rate/pc</th>
                                      	<th style="text-align:center;">Discount</th>
                                      	<th style="text-align:center;">Customer<br />Disc(%)</th>
                                      	<th style="text-align:center;">GST</th>
                                      </tr>
                                       <tr>
                                         <td width="8%">
                                         	<select name="category[]" id="category0" class="form-control" onchange="getCompany(0,this.value)">
                                                <option value="">Select</option>
                                                <?php if(is_array($selcat)){
													foreach($selcat as $val){ 
												?>
                                                <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                                <?php }} ?>
                                            </select>
                                         </td>
                                         <td width="8%">
                                            <select name="company_id[]" id="company_id0" class="form-control" onchange="getModel(0,this.value)">
                                                <option value="">Select</option>
                                            </select>
                                         </td>
                                         <td width="8%">
                                            <select name="model[]" id="model0" class="form-control">
                                                <option value="">Select</option>
                                            </select>
                                         </td>
                                         <td width="7%"><input type="text" name="mrp[]" class="form-control"  placeholder="MRP"/></td>
                                         <td width="7%">
                                         	<input type="text" name="uom[]" class="form-control"  placeholder="UOM"/>
                                         	<input type="hidden" name="slno[]" class="form-control"  placeholder="Serial No."/>
                                       	 </td>
                                         <td width="8%"><input type="text" name="hsn[]" class="form-control"  placeholder="HSN"/></td>
                                         <td width="7%"><input type="text" name="pieces[]" id="pieces0" class="form-control"  placeholder="Pieces"/></td>
                                         <td width="11%"><input type="text" name="quantity[]" id="quantity0" class="form-control" placeholder="Quantity" /></td>
                                         <td width="8%"><input type="text" name="purchase[]" id="purchase0" class="form-control" placeholder="Rate" autocomplete="off" /></td>
                                         <td width="8%"><input type="text" name="discount[]" id="discount0" class="form-control" placeholder="Discount" autocomplete="off" /></td>
                                         <td width="7%"><input type="text" name="custdiscount[]" id="custdiscount0" class="form-control" /></td>
                                         <td width="13%">
                                        	<label class="checkbox-inline"><input type="checkbox" name="cgst[]" id="cgst0"><b>CGST <span id="cvalue0"></span></b></label><br />
                                        	<label class="checkbox-inline"><input type="checkbox" name="sgst[]" id="sgst0"><b>SGST <span id="svalue0"></span></b></label><br />
                                        	<label class="checkbox-inline"><input type="checkbox" name="igst[]" id="igst0"><b>IGST <span id="ivalue0"></span></b></label>
                                        </td>
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
                                                <th style="text-align:center;">Transport</th>
                                                <td colspan="2"><input type="text" name="transport" id="transport" class="form-control" autocomplete="off" onkeyup="getFinal(this.value)"/></td>
                                            </tr>
                                        </table>
                                    </div>
                                   <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="text-align:center;">Total Amount</th>
                                                <td colspan="2"><input type="text" name="total" id="total" class="form-control" readonly="readonly" /></td>
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
                                            <input type="hidden" id="round" name="round" />
                                            <input type="submit" id="supplier_input13" name="add_purchase" value="Save" class="btn btn-success" />
                                        
                                        <!--	<button id="supplier_input13" type="button" >Print</button> -->
                                            <a href="../reports/purchase_order.php?pagename=report" class="btn btn-info">
                                                View Purchases
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
			function addRow(str) {
				var table=str;
				var count=$('#count').val();
				count++;
				$('#count').val(count);
				var first= "<select name='category[]' id='category"+count+"' class='form-control' onchange='getCompany("+count+",this.value)' ><option value=''>Select Category</option><?php if(is_array($selcat)){foreach($selcat as $val){ ?><option value='<?php echo $val['id']; ?>'><?php echo addslashes($val['name']); ?></option><?php } }?></select>";
				var col2="<td><select name='company_id[]' id='company_id"+count+"' class='form-control' onchange='getModel("+count+",this.value)'><option value=''>Select Company</option></select></td>"; 
				var col3="<td><select name='model[]' id='model"+count+"' class='form-control'><option value=''>Select Model</option></select></td>"; 
				var col4="<td><input type='text' name='mrp[]' class='form-control'  placeholder='MRP'/></td>";
				var col5="<td><input type='text' name='uom[]' class='form-control'  placeholder='UOM' /><input type='hidden' name='slno[]' class='form-control'  placeholder='Serial No.'/></td>";
				var col6="<td><input type='text' name='hsn[]' class='form-control'  placeholder='HSN'/></td>";
				var col7="<td><input type='text' name='pieces[]' id='pieces"+count+"' class='form-control'  placeholder='Pieces'/></td>";
				var col8="<td><input type='text' name='quantity[]' id='quantity"+count+"' class='form-control'  placeholder='Quantity'/></td>";
				var col9="<td><input type='text' name='purchase[]' id='purchase"+count+"' class='form-control'  placeholder='Rate'/></td>";
				var col10="<td><input type='text' name='discount[]' id='discount"+count+"' class='form-control'  placeholder='Discount'/></td>";
				var col11="<td><input type='text' name='custDiscount[]' id='custDiscount"+count+"' class='form-control'  placeholder=''/></td>";
				var col12="<td><label class='checkbox-inline'><input type='checkbox' name='cgst[]' id='cgst"+count+"'><b>CGST <span id='cvalue"+count+"'></span></b></label><br />";
				var col13="<label class='checkbox-inline'><input type='checkbox' name='sgst[]' id='sgst"+count+"'><b>SGST <span id='svalue"+count+"'></span></b></label><br />";
				var col14="<label class='checkbox-inline'><input type='checkbox' name='igst[]' id='igst"+count+"'><b>IGST <span id='ivalue"+count+"'></span></b></label></td>";
				
				$('#dataTable tr:last').after("<tr><td>"+first+"</td>"+col2+col3+col4+col5+col6+col7+col8+col9+col10+col11+col12+col13+col14+"</tr>");
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
				var model='#model'+str;	
				var models="<option value=''>Select Model</option>";
				var comp="<option value=''>Select Company</option>";
				var cid="#cgst"+str;var sid="#sgst"+str;var iid="#igst"+str;
				var cvalue="#cvalue"+str;var svalue="#svalue"+str;var ivalue="#ivalue"+str;
				var shop='<?php echo $shop; ?>';
				$(model).html(models);
				var category=str2;
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{category:category,shop:shop,get_company:'get_company',page:'purchase'},
						success: function(data){
							$(id).html(data);
							var array='<?php echo json_encode($gst); ?>';	
							var gst=JSON.parse(array);
							var cgst=gst[category]['cgst'];
							var sgst=gst[category]['sgst'];
							var igst=gst[category]['igst'];
							$(cid).val(cgst); $(sid).val(sgst); $(iid).val(igst);
							$(cvalue).html("@ "+cgst+"%"); $(svalue).html("@ "+sgst+"%"); $(ivalue).html("@ "+igst+"%");
						}	
					});
				}
				else{
					$(id).html(comp);
					$(cid).val(''); $(sid).val(''); $(iid).val('');
					$(cvalue).html(""); $(svalue).html(""); $(ivalue).html("");
				}
			}
			
			function getModel(str,str2){
				var id='#model'+str;	
				var company_id=str2;
				var cid="#category"+str;
				var category=$(cid).val();
				var shop='<?php echo $shop; ?>';
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{company_id:company_id,category:category,get_model:'get_model',page:'purchase',shop:shop},
						success: function(data){
							$(id).html(data);
						}	
					});
				}
			}
			
			function getAmount(){
				var total=0;
				var count=parseInt($('#count').val());
				for(var i=0;i<=count;i++){
					var p="#purchase"+i; var q="#quantity"+i; var pc="#pieces"+i; var d="#discount"+i; var cd="#custdiscount"+i;
					var c="#cgst"+i; var s="#sgst"+i; var ig="#igst"+i;
					var amount=0;var pur=0;
					var purchase=$(p).val(); var discount=parseFloat($(d).val()); var custdiscount=parseFloat($(cd).val());
					//if(material=='Readymade'){
						
					var quantity=$(pc).val();
					//}else{
						//var quantity=$(q).val();
					//}
					var cgst=parseFloat($(c).val()); var sgst=parseFloat($(s).val()); var igst=parseFloat($(ig).val());
					if(isNaN(cgst)){cgst=0; }if(isNaN(sgst)){sgst=0; }if(isNaN(igst)){igst=0; }if(isNaN(discount)){discount=0; }
					if(isNaN(custdiscount)){custdiscount=0; }
					var pur=parseFloat(purchase)*parseFloat(quantity);
					//var dis=parseFloat(discount)*parseFloat(quantity);
					pur-=discount;
					pur-=(custdiscount*pur)/100;
					amount=pur;
					if($(c).prop('checked') == true){ if(cgst!=0){amount+=(pur*cgst)/100}}
					if($(s).prop('checked') == true){if(sgst!=0){amount+=(pur*sgst)/100}}
					if($(ig).prop('checked') == true){if(igst!=0){amount+=(pur*igst)/100}}
					if(isNaN(amount)){amount=0;}
					total+=amount;
				}
				var gross_amount=total.toFixed(2);
				total=Math.round(total);
				var roundoff=total-gross_amount;
				roundoff=roundoff.toFixed(2);
				$('#gross_amount').val(gross_amount);
				$('#round').val(roundoff);
				$('#total').val(total);
				$('#transport').val('');
				$('#paid').val('');
				$('#dues').val('');
			}
			
			function getFinal(str){
				var transport=str;
				var total=$('#gross_amount').val();
				if(transport!='' && total!=''){
					transport=parseFloat(transport);
					total=parseFloat(total);
					total+=transport;
					var gross=total;
					total=Math.round(total);
					var roundoff=total-gross;
					roundoff=roundoff.toFixed(2);
					$('#round').val(roundoff);
					$('#total').val(total);
				}
				else{
					$('#transport').val('');	
				}
			}
			
			function calcDues(str){
				var paid	=str;
				var total=$('#total').val();
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
				var final=$('#total').val();
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
