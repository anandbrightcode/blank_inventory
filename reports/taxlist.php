<?php 
include_once "../action/class.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}
	$table="`category`";
	$columns="distinct(`cgst`+`sgst`) as gst";
	$array=$obj->get_rows($table,$columns,"`shop`='$shop'","`gst`");
	
?>
<table class="table table-bordered">
	<tr>
    	<th style="text-align:center">GST</th>
    	<th style="text-align:center">Input Tax</th>
    	<th style="text-align:center">Output Tax</th>
    	<th style="text-align:center">Payable</th>
    </tr>
    <?php
		$tax_input=0;	$tax_output=0;	$tax_payable=0;
    	foreach($array as $val){
			if(isset($_GET['from']) && isset($_GET['to'])){
				$from=$_GET['from']; $to=$_GET['to'];
				$where1=" and purchase_id in(SELECT id from purchase where `date` between '$from' and '$to')";
				$where2=" and invoice_id in(SELECT id from invoice where `date` between '$from' and '$to')";
			}
			else{
				$where1="";$where2="";
			}
			$where="`shop`='$shop' and `cgst`+`sgst`+`igst`='".$val['gst']."'";
			$input=$obj->get_details("`purchase_order`","round(sum(`cvalue`+`svalue`+`ivalue`),2) as input",$where.$where1);
			$output=$obj->get_details("`sales`","round(sum(`cvalue`+`svalue`+`ivalue`),2) as output",$where.$where2);
			$tax_in=$input['input'];if($tax_in==''){$tax_in=0;}
			$tax_out=$output['output'];if($tax_out==''){$tax_out=0;}
			$payable=$tax_out-$tax_in;
			$tax_in=number_format((float)$tax_in,2,'.','');
			$tax_out=number_format((float)$tax_out,2,'.','');
			$payable=number_format((float)$payable,2,'.','');
	?>
    <tr>
    	<td align="center"><?php echo "@ ".$val['gst']."%"; ?></td>
    	<td align="center"><?php echo toDecimal($tax_in); ?></td>
    	<td align="center"><?php echo toDecimal($tax_out); ?></td>
    	<td align="center"><?php echo toDecimal($payable); ?></td>
    </tr>
    <?php
			$tax_input+=$tax_in;		$tax_output+=$tax_out;	$tax_payable+=$payable;
			
		}
		$tax_input=number_format((float)$tax_input,2,'.','');
		$tax_output=number_format((float)$tax_output,2,'.','');
		$tax_payable=number_format((float)$tax_payable,2,'.','');
	?>
    <tr>
    	<th style="text-align:center">Total</th>
    	<th style="text-align:center"><?php echo toDecimal($tax_input); ?></th>
    	<th style="text-align:center"><?php echo toDecimal($tax_output); ?></th>
    	<th style="text-align:center"><?php echo toDecimal($tax_payable); ?></th>
    </tr>
</table>