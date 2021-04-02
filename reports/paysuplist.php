<?php
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
	include_once "../action/class.php";
	$obj=new database();
}
	$scount=15;
	$soffset =0;
	if(isset($_GET['spage'])){
		$spage=$_GET['spage'];
	}
	else{
		$spage=1;	
	}
	$soffset=($spage-1)*$scount;
	$stable="`sup_pay_details`";
	$scolumns="*";
	$pagefilters="";
	$swhere="shop='$shop'";
	if(isset($_GET['supplier']) && trim($_GET['supplier'])!=""){
		$supplier=$_GET['supplier'];
		$swhere.=" and `supplier_id`='$supplier'";
		$pagefilters.="&supplier=$supplier";
	}
	else{exit;}
	if(isset($_GET['sfrom']) && isset($_GET['sto'])){
		$from=$_GET['sfrom'];$to=$_GET['sto'];
		if($from!='' && $to!=''){
			$swhere.=" and  (date >= '$from' and date <= '$to')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$swhere.=" and date = '$date'";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$swhere.=" and date = '$date'";
		}
		$pagefilters.="&sfrom=$from&sto=$to";
	}
	//echo $swhere;
	$sorder="id";
	$slimit="$soffset,$scount";
	$sarray=$obj->get_rows($stable,$scolumns,$swhere,$sorder,$slimit);
	$srowcount=$obj->get_count($stable,$swhere);
	$spages=ceil($srowcount/$scount);
	$arr=$obj->get_details("`purchase`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`","`supplier`='$supplier' and `shop`='$shop'");
	$getadvance=$obj->get_details("`supplier`","`advance`","`id`='$supplier'");
	$advance=$getadvance['advance'];
	$dues=$arr['dues'];
	if($advance<0){
		$dues+=0-$advance;
		$advance=0;
	}
?>
<div class="row">
	<div class="col-md-12">
    	<div class="sections"><h4 class="text-primary">Total : <?php echo toDecimal($arr['total']); ?></h4></div>
    	<div class="sections"><h4 class="text-success">Paid : <?php echo toDecimal($arr['paid']); ?></h4></div>
    	<div class="sections"><h4 class="text-danger">Dues : <?php echo toDecimal($dues); ?></h4></div>
    	<div class="sections"><h4 class="text-success">Advance : <?php echo toDecimal($advance); ?></h4></div>
    	<div class="sections"><a href="supplier_pay.php?id=<?php echo $supplier; ?>&pagename=report" class="btn btn-sm btn-primary pull-right">Make Payment</a></div>
 	</div>
</div>
<table class="table-striped table-bordered table-hover table-condensed" style="width:100%;" id="s_list" >
    <thead>
    	<tr>
            <th style="text-align:center">Sl. No.</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Supplier</th>
            <th style="text-align:center">Payment Mode</th>
            <th style="text-align:center">Cheque Number</th>
            <th style="text-align:center">Cheque Date</th>
            <th style="text-align:center">Bank</th>
            <th style="text-align:center">Paid</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($sarray)){$slno=$soffset;
			foreach($sarray as $supplier){$slno++;
	?>
    <tr>
    	<td align="center"><?php echo $slno; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($supplier['date'])); ?></td>
    	<td align="center"><?php $sup=$obj->get_details("`supplier`","`name`","`id`='".$supplier['supplier_id']."'");echo $sup['name']; ?></td>
        <td align="center"><?php echo $supplier['payment_mode']; ?></td>
        <td align="center"><?php echo $supplier['cheque_no']; ?></td>
        <td align="center"><?php echo $supplier['cheque_date']; ?></td>
        <td align="center"><?php echo $supplier['bank']; ?></td>
    	<td align="center"><?php echo toDecimal($supplier['amount']); ?></td>
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
		if($spages>1){
	?>
    <tr>
    	<td colspan="6" align="center">
    <?php
			if($spage!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="payment.php?pagename=report&spage=<?php echo $spage-1; echo $pagefilters; ?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($si=1;$si<=$spages;$si++){
				if($si<4 || $si>$spages-3 || $si==$spage || $si==$spage-1 || $si==$spage+1 || $si==$spage-2 || $si==$spage+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($si==$spage){echo "class='active'";} ?>>
                	<a href="payment.php?pagename=report&spage=<?php echo $si;  echo $pagefilters; ?>"><?php echo $si; ?></a>
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
    			<li><a href="payment.php?pagename=report&spage=<?php echo $spage+1;  echo $pagefilters; ?>">Next</a></li>
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