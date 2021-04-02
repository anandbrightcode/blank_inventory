<?php 
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
	include_once "../action/class.php";
	$obj=new database();
	
}
	$table1="`sales`";
	$table2="`purchase_order`";
	$columns1="sum(`taxable`) as taxable,sum(`cvalue`) as cvalue,sum(`svalue`) as svalue";
	$columns2="sum(`taxable`) as taxable,sum(`ivalue`) as ivalue";
	$where1=" `shop`='$shop' and `ivalue`=0";
	$where2=" `shop`='$shop' and `ivalue`!=0";
	$swhere=$pwhere='';
	if(isset($_GET['sfrom']) && isset($_GET['sto'])){
		$from=$_GET['sfrom'];$to=$_GET['sto'];
		if($from!='' && $to!=''){
			$swhere=" and invoice_id in(select id from invoice where  (date >= '$from' and date <= '$to') and shop='$shop')";
			$pwhere=" and purchase_id in(select id from purchase where  (date >= '$from' and date <= '$to') and shop='$shop')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$swhere.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')  ";
			$pwhere.=" and purchase_id in(select id from purchase where  date = '$date' and shop='$shop')  ";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$swhere.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')";
			$pwhere.=" and purchase_id in(select id from purchase where  date = '$date' and shop='$shop')";
		}
	}
	$array1=$obj->get_details($table1,$columns1,$where1.$swhere);
	$array2=$obj->get_details($table1,$columns2,$where2.$swhere);
	$array3=$obj->get_details($table2,$columns1,$where1.$pwhere);
	$array4=$obj->get_details($table2,$columns2,$where2.$pwhere);

?>
<table border="1" class="table">
<tr>
  <th style="text-align:center" colspan="2">Sale Summary Intra State</th>
  <th style="text-align:center" colspan="2">Sale Summary Inter State</th>
  <th style="text-align:center" colspan="2">Total Sale Summary</th>
</tr>
<tr>
  <td>Taxable Value</td>
  <td><?php echo toDecimal($array1['taxable']);?></td>
  <td>Taxable Value</td>
  <td><?php echo toDecimal($array2['taxable']);?></td>
  <td>Taxable Tax</td>
  <td><?php echo toDecimal($array1['taxable']+$array2['taxable']);?></td>
</tr>
<tr>
   <td>Central Tax</td>
  <td><?php echo toDecimal($array1['cvalue']);?></td>
  <td>Integrated Value</td>
  <td><?php echo toDecimal($array2['ivalue']);?></td>
  <td>Central Tax</td>
  <td><?php echo toDecimal($array1['cvalue']);?></td>
 </tr>
<tr>
   <td>State Tax</td>
  <td><?php echo toDecimal($array1['svalue']);?></td>
  <td></td>
  <td></td>
  <td>State Tax</td>
  <td><?php echo toDecimal($array1['svalue']);?></td>
</tr>
<tr>
 <td colspan="2"></td>
  <td colspan="2"></td>
  <td>Integrated Tax</td>
  <td><?php echo toDecimal($array2['ivalue']);?></td>
</tr>

<tr>
  <th style="text-align:center" colspan="2">Purchase Summary Intra State</th>
  <th style="text-align:center" colspan="2">Purchase Summary Inter State</th>
  <th style="text-align:center" colspan="2">Total Purchase Summary</th>
</tr>
<tr>
  <td>Taxable Value</td>
  <td><?php echo toDecimal($array3['taxable']);?></td>
  <td>Taxable Value</td>
  <td><?php echo toDecimal($array4['taxable']);?></td>
  <td>Taxable Tax</td>
  <td><?php echo toDecimal($array3['taxable']+$array4['taxable']);?></td>
</tr>
<tr>
   <td>Central Tax</td>
  <td><?php echo toDecimal($array3['cvalue']);?></td>
  <td>Integrated Value</td>
  <td><?php echo toDecimal($array4['ivalue']);?></td>
  <td>Central Tax</td>
  <td><?php echo toDecimal($array3['cvalue']);?></td>
 </tr>
<tr>
   <td>State Tax</td>
  <td><?php echo toDecimal($array3['svalue']);?></td>
  <td></td>
  <td></td>
  <td>State Tax</td>
  <td><?php echo toDecimal($array3['svalue']);?></td>
</tr>
<tr>
 <td colspan="2"></td>
  <td colspan="2"></td>
  <td>Integrated Tax</td>
  <td><?php echo toDecimal($array4['ivalue']);?></td>
</tr>
</table>