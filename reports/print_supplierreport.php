<?php
if(isset($_GET['shop']))
{
	 $shop=$_GET['shop'];
}
	include_once "../action/class.php";
	$obj=new database();
	$payment="";
	$stable="`supplier_pay`";
	$scolumns="*";
	$swhere=$swhere2="1";
	$shop_details=$obj->get_details("`shop`","*","`id`='$shop'");
	if(isset($_GET['supplier']) && trim($_GET['supplier'])!=""){
		$supplier=$_GET['supplier'];
		$swhere.=" and `supplier_id`='$supplier'";
		$swhere2.=" and `supplier`='$supplier'";
	}
	
	if(isset($_GET['sfrom']) && isset($_GET['sto'])){
		$from=$_GET['sfrom'];$to=$_GET['sto'];
		if($from!='' && $to!=''){
			$swhere.=" and  (date >= '$from' and date <= '$to')";
			$swhere2.=" and  (date >= '$from' and date <= '$to')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$swhere.=" and date = '$date'";
			$swhere2.=" and date = '$date'";
			
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$swhere.=" and date = '$date'";
			$swhere2.=" and date = '$date'";
		}
		
	}
	//echo $swhere;
	$sarray=$obj->get_rows($stable,$scolumns,$swhere);
	
	//print_r($sarray);
	
	$gettotal=$obj->get_details("`purchase`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`",$swhere2);
	//print_r($gettotal);
	$total_amount=$total_paid=$total_dues=0;
	if(is_array($gettotal)){
		$total_amount=$gettotal['total'];
		$total_paid=$gettotal['paid'];
		$total_dues=$gettotal['dues'];
	}
	
	// $getstotal=$obj->get_details("`purchase`","`payment_mode`, `cheque_no`, `bank`",$swhere2);
	
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
       <font size="+1" style="border:1px solid #000000; padding:5px;">SUPPLIER REPORT</font><br /><br>
        <font size="+3" style="letter-spacing:2px"><?php echo strtoupper($shop_details['name']); ?><br /></font>
        <font size="+1"><?php echo $shop_details['address'];  ?><br />
        <?php  if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; ?><br />
        Ph : <?php echo $shop_details['phone']; ?><br />
        <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?>
        </font><br />
  </center><br>
   
<div style="float:left; margin-right:20px;"><h4>Total Amount : Rs <?php echo toDecimal($total_amount); ?></h4></div>
<div  style="float:left; position:relative; margin-right:20px;"><h4>Total Paid : Rs <?php echo toDecimal($total_paid); ?></h4></div>
<div style="float:left; position:relative; margin-right:20px;"><h4>Total Dues : Rs <?php echo toDecimal($total_dues); ?></h4></div>

<table border="1" cellspacing="0" cellpadding="0" style="width:95%;" id="s_list" >
     
    <thead>
    	<tr>
            <th style="text-align:center">Invoice</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Supplier</th>
             <th style="text-align:center">Mode</th>
              <!--<th style="text-align:center">Cheq. No</th>
               <th style="text-align:center">Cheq. Date</th>
                <th style="text-align:center">Bank</th>-->
            <th style="text-align:center">Total Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
          
    	</tr>
    </thead>
    <?php
    	if(is_array($sarray)){
			foreach($sarray as $supplier){
				$result=$obj->get_details("`purchase`","`payment_mode`, `cheque_no`, `cheque_date`, `bank`","`id`='$supplier[purchase_id]'");
				//print_r($result);
	?>
    <tr>
    	<td align="center"><?php echo $supplier['invoice']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($supplier['date'])); ?></td>
    	<td align="center"><?php $sup=$obj->get_details("`supplier`","`name`","`id`='".$supplier['supplier_id']."'");echo $sup['name']; ?></td>
        <td align="center"><?php echo $supplier['payment_mode']; ?></td>
    	 <!-- <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_no']; }else { echo "";}?></td>
          <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_date']; }else { echo "";}?></td>
        <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['bank']; }else { echo "";}?></td>-->
       <td align="center"><?php echo toDecimal($supplier['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($supplier['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($supplier['dues']); ?></td>
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