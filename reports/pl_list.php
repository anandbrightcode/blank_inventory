<?php 
include_once "../action/class.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$where="(`date` between '$from' and '$to') and shop='$shop'";
	}
	else{
		$where="`shop`='$shop'";
	}
	$exp=$obj->get_details("`expense`","sum(`amount`) as `total`",$where);
	$expense=$exp['total'];
	$pur=$obj->get_details("`purchase`","sum(`total_amount`) as `total`",$where);
	$purchase=$pur['total'];
	$ret=$obj->get_details("`returns`","sum(`price`*`quantity`) as `total`",$where);
	$returns=$ret['total'];
	$pay=$obj->get_details("`salary`","sum(`net_payment`) as `total`",$where);
	$salary=$pay['total'];
	$trans=$obj->get_details("`invoice`","sum(`transport`) as `total`",$where);
	$transport=$trans['total'];
	$total_expense=$expense+$purchase+$returns+$salary+$transport;
	
	$inv=$obj->get_details("`invoice`","sum(`total_amount`) as `total`",$where);
	$sales=$inv['total'];
	$camount=0;
	if(isset($_GET['from']) && isset($_GET['to'])){
		$pwhere="`shop`='$shop' and `purchase_id` in (SELECT id from purchase where (`date` between '$from' and '$to') and `shop`='$shop')";
		$swhere="`shop`='$shop' and `invoice_id` in (SELECT id from invoice where (`date` between '$from' and '$to') and `shop`='$shop')";
		$getpcarry=$obj->get_details("`purchase_order`","sum(`quantity`) as `total`, sum(`purchase_gst`)/sum(`quantity`) as `purchase`",
										"`purchase_id` in (SELECT id from purchase where `date`<'$from' and `shop`='$shop')");
		$getscarry=$obj->get_details("`sales`","sum(`quantity`) as `total`",
										"`invoice_id` in (SELECT id from invoice where `date`<'$from' and `shop`='$shop')");
		$cpquantity=$getpcarry['total'];
		$cprate=$getpcarry['purchase'];
		$spquantity=$getscarry['total'];
		$carry=$cpquantity-$spquantity;
		$camount=$carry*$cprate;
		$getpurchase=$obj->get_details("`purchase_order`","sum(`quantity`) as `total`, sum(`purchase_gst`)/sum(`quantity`) as `purchase`",$pwhere);
		$getsale=$obj->get_details("`sales`","sum(`quantity`) as `total`",$swhere);
		$pquantity=$getpurchase['total'];
		$prate=$getpurchase['purchase'];
		$squantity=$getsale['total'];
		$stock=(($pquantity-$squantity)*$prate)+$camount;
	}
	else{
		$rem=$obj->get_details("`stock`","sum(`base_price`*`quantity`) as `total`",$where);
		$stock=$rem['total'];
	}
	$total_income=$sales+$stock;
?>
<table class="table table-bordered">
	<tr>
    	<th style="text-align:center;" colspan="2">Income</th>	
    	<th style="text-align:center;" colspan="2">Expense</th>	
    </tr>
    <tr>
    	<td align="left">Total Sales</td>
    	<td align="right"><?php echo toDecimal($sales); ?></td>
    	<td align="left">Total Purchase</td>
    	<td align="right"><?php echo toDecimal($purchase); ?></td>
    </tr>
    <tr>
    	<td align="left">Total Remaining Stock</td>
    	<td align="right"><?php echo toDecimal($stock); ?></td>
    	<td align="left">Total Expense</td>
    	<td align="right"><?php echo toDecimal($expense); ?></td>
    </tr>
    <tr>
    	<td align="left"></td>
    	<td align="right"></td>
    	<td align="left">Total Returns</td>
    	<td align="right"><?php echo toDecimal($returns); ?></td>
    </tr>
    <tr>
    	<td align="left"></td>
    	<td align="right"></td>
    	<td align="left">Total Salary</td>
    	<td align="right"><?php echo toDecimal($salary); ?></td>
    </tr>
    <tr>
    	<td align="left"></td>
    	<td align="right"></td>
    	<td align="left">Transport Expense</td>
    	<td align="right"><?php echo toDecimal($transport); ?></td>
    </tr>
    <tr>
    	<th>Total Income</th>
    	<th style="text-align:right;"><?php echo toDecimal($total_income); ?></th>
    	<th>Total Expense</th>
    	<th style="text-align:right;"><?php echo toDecimal($total_expense); ?></th>
    </tr>
    <tr>
    	<th colspan="4">
        	<?php
            	$pl=$total_income-$total_expense;
				$pl=number_format((float)$pl,2,'.','');
				if($pl<0){$pl=0-$pl; $pl=number_format((float)$pl,2,'.',''); echo "<h3 class='text-danger text-center'>Total Loss : Rs $pl</h3>";}
				else{echo "<h3 class='text-success text-center'>Total Profit : Rs ".toDecimal($pl)."</h3>";}
			?>
        </th>
    </tr>
</table>