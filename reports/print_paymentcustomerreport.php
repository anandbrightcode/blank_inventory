<?php 
if(isset($_GET['shop']))
{
	 $shop=$_GET['shop'];
}	
	include_once "../action/class.php";
	$obj=new database();

	$table="`cust_pay_details`";
	$columns="*";
	$pagefilters="";
	$where="1";
	$shop_details=$obj->get_details("`shop`","*","`id`='$shop'");
	if(isset($_GET['customer']) && trim($_GET['customer'])!=""){
		 $customer=$_GET['customer'];
		$where.=" and `customer_id`='$customer'";
		
	}
	
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from'];$to=$_GET['to'];
		if($from!='' && $to!=''){
			$where.=" and  (date >= '$from' and date <= '$to')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$where.=" and date = '$date'";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$where.=" and date = '$date'";
		}
	
	}
	
	//echo $where; 
	
	 $array=$obj->get_rows($table,$columns,$where);
     /*$gettotal=$obj->get_details("`invoice`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`",$where);
	$total_amount=$total_paid=$total_dues=0;
	if(is_array($gettotal)){
		$total_amount=$gettotal['total'];
		$total_paid=$gettotal['paid'];
		$total_dues=$gettotal['dues'];
	}*/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="UTF-8">
        <title>Invoice</title>
        <style type="text/css" media="print">
			@page {
					margin:0 10px;
					/*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
					/*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
			}
			@media print{
				table {page-break-inside: avoid;}
				#buttons{
						display:none;
				}
				#invoice{
					margin-top:20px;
  				}
			}
		</style>
    </head>
    <body>
      <div id="invoice" style="width:1000px;">
 <center>
    
         <font size="+1" style="border:1px solid #000000; padding:5px;">CUSTOMER REPORT</font><br /><br>
          <font size="+3" style="letter-spacing:2px"><?php echo strtoupper($shop_details['name']); ?><br /></font>
          <font size="+1"><?php echo $shop_details['address'];  ?><br />
          <?php  if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; ?><br />
          Ph : <?php echo $shop_details['phone']; ?><br />
          <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?>
          </font><br />

 </center><br>

<!--<div style="text-align:center;">
<div style="float:left; margin-right:20px;"><h4>Total Amount : Rs <?php echo toDecimal($total_amount); ?></h4></div>
<div style="float:left; position:relative; margin-right:20px;"><h4>Total Paid : Rs <?php echo toDecimal($total_paid); ?></h4></div>

<div style="float:left; position:relative; margin-right:20px;"><h4>Total Dues : Rs <?php echo toDecimal($total_dues); ?></h4></div>
</div>-->
<table border="1" cellspacing="0" cellpadding="0" id="c_list" style="width:95%;" >


    <thead>
    	<tr>
          
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Customer</th>
            <th style="text-align:center">Paid</th>
            
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $customer){
				
				//print_r($result);
	?>
    <tr>
    
    	<td align="center"><?php echo date('d-m-Y',strtotime($customer['date'])); ?></td>
    	<td align="center"><?php $cust=$obj->get_details("`customer`","`name`","`id`='".$customer['customer_id']."'");echo $cust['name']; ?></td>
    	<td align="center"><?php echo toDecimal($customer['amount']); ?></td>
    	
    </tr>
    <?php
			}	
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="6">No Records Found!!</td>
    </tr>
    <?php
		}
		
	?>

</table><br>
         <div id="buttons">
             	<center>
                  	<button type="button" class="btn btn-danger" onclick="window.print();" 
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
                 	<a href="balancesheet.php"><button type="button" onclick="closeThis('<?php echo $pre; ?>');" class="btn btn-default"
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button></a>
             	</center>
         	</div>
            
            </div>
             <script language="javascript">
        
        </script>
        </body>
        </html>