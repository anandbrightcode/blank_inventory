<?php
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
	include_once "../action/class.php";
	$obj=new database();
}
	$scount=20;
	$soffset =0;
	if(isset($_GET['spage'])){
		$spage=$_GET['spage'];
	}
	else{
		$spage=1;	
	}
	$payment="";
	$soffset=($spage-1)*$scount;
	$stable="`supplier_pay`";
	$scolumns="*";
	$pagefilters="";
	$swhere=$swhere2="shop='$shop'";
	if(isset($_GET['supplier']) && trim($_GET['supplier'])!=""){
		$supplier=$_GET['supplier'];
		$swhere.=" and `supplier_id`='$supplier'";
		$swhere2.=" and `supplier`='$supplier'";
		$pagefilters.="&supplier=$supplier";
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
		$pagefilters.="&sfrom=$from&sto=$to";
	}
	//echo $swhere;
	
	$sorder="id";
	$slimit="$soffset,$scount";
	$sarray=$obj->get_rows($stable,$scolumns,$swhere,$sorder,$slimit);
	//print_r($sarray);
	$srowcount=$obj->get_count($stable,$swhere);
	$spages=ceil($srowcount/$scount);
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

<div class="col-md-4 text-primary"><h4>Total Amount : Rs <?php echo toDecimal($total_amount); ?></h4></div>
<div class="col-md-4 text-success"><h4>Total Paid : Rs <?php echo toDecimal($total_paid); ?></h4></div>
<div class="col-md-4 text-danger"><h4>Total Dues : Rs <?php echo toDecimal($total_dues); ?></h4></div>
<table class="table-striped table-bordered table-hover table-condensed" style="width:95%;" id="s_list" >
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
           <th style="text-align:center;">Action</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($sarray)){
			foreach($sarray as $supplier){
				//$result=$obj->get_details("`purchase`","`payment_mode`, `cheque_no`, `cheque_date`, `bank`","`id`='$supplier[purchase_id]'");
				//print_r($result);
	?>
    <tr>
    	<td align="center"><?php echo $supplier['invoice']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($supplier['date'])); ?></td>
    	<td align="center"><?php $sup=$obj->get_details("`supplier`","`name`","`id`='".$supplier['supplier_id']."'");echo $sup['name']; ?></td>
        <td align="center"><?php echo $supplier['payment_mode']; ?></td>
        <!--<td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_no']; }else { echo "";}?></td>
        <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_date']; }else { echo "";}?></td>
        <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['bank']; }else { echo "";}?></td>-->
        <td align="center"><?php echo toDecimal($supplier['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($supplier['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($supplier['dues']); ?></td>
        <td align="center">
           	<button type="button" class="btn btn-info btn-sm" onClick="viewPurchase('<?php echo $supplier['purchase_id']; ?>');"><i class="fa fa-eye"></i></button>
        </td>
    </tr>
    <?php
		   
			}	
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="10">No Records Found!!</td>
    </tr>
    <?php
		}
		if($spages>1){
	?>
    <tr>
    	<td colspan="6" align="center">
    <?php
			if($spage!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="balancesheet.php?pagename=report&spage=<?php echo $spage-1; echo $pagefilters; ?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($si=1;$si<=$spages;$si++){
				if($si<4 || $si>$spages-3 || $si==$spage || $si==$spage-1 || $si==$spage+1 || $si==$spage-2 || $si==$spage+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($si==$spage){echo "class='active'";} ?>>
                	<a href="balancesheet.php?pagename=report&spage=<?php echo $si;  echo $pagefilters; ?>"><?php echo $si; ?></a>
                </li>
          	</ul>
    <?php		
				}
				elseif($spages>5 && ($si==4 || $si==$spages-3)){
	?>
			<ul class="pagination pagination-sm">
    			<li>
                	<a>...</a>
                </li>
          	</ul>
    <?php
				}
			}
			if($spage!=$spages){
	?>
    		<ul class="pagination pagination-sm">
    			<li><a href="balancesheet.php?pagename=report&spage=<?php echo $spage+1;  echo $pagefilters; ?>">Next</a></li>
          	</ul>
    <?php
			}
	?>
    	</td>
    </tr>
    <?php
		}
	?>
</table>
