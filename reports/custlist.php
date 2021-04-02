<?php 
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
	include_once "../action/class.php";
	$obj=new database();
}
	$count=20;
	$offset =0;
	if(isset($_GET['page'])){
		$page=$_GET['page'];
	}
	else{
		$page=1;	
	}
	$offset=($page-1)*$count;
	$table="`invoice`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="invoice_no='$query' and shop='$shop'";
	}
	elseif(isset($_GET['customer']) && trim($_GET['customer'])!=""){
		$customer=$_GET['customer'];
		$where="`customer_id`='$customer' and shop='$shop'";
	}
	else{
		$where="`shop`='$shop'";
	}
	$where.=" and `customer_id`!=0";
	$order="id";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	$gettotal=$obj->get_details("`invoice`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`","`shop`='$shop'");
	$total_amount=$total_paid=$total_dues=0;
	if(is_array($gettotal)){
		$total_amount=$gettotal['total'];
		$total_paid=$gettotal['paid'];
		$total_dues=$gettotal['dues'];
	}
	
?>
<div class="col-md-4 text-primary"><h4>Total Amount : Rs <?php echo $total_amount; ?></h4></div>
<div class="col-md-4 text-success"><h4>Total Paid : Rs <?php echo $total_paid; ?></h4></div>
<div class="col-md-4 text-danger"><h4>Total Dues : Rs <?php echo $total_dues; ?></h4></div>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >
    <thead>
    	<tr>
            <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Customer</th>
            <th style="text-align:center">Gross Amount</th>
            <th style="text-align:center">Transport</th>
            <th style="text-align:center">Total Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
            <th style="text-align:center">Next Payment</th>
    	</tr>
    </thead>
    <?php
		$gross=$trans=$total=$paid=$dues=0;
    	if(is_array($array)){
			foreach($array as $invoice){
	?>
    <tr>
    	<td align="center"><?php echo $invoice['invoice_no']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
    	<td align="center"><?php echo $invoice['customer_name']; ?></td>
    	<td align="center"><?php echo toDecimal($invoice['gross_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['transport']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['dues']); ?></td>
    	<td align="center"><?php if($invoice['next_payment']!="0000-00-00"){echo date('d-m-Y',strtotime($invoice['next_payment']));}else{echo "-- -- ----";} ?></td>
    </tr>
    <?php
				$gross+=$invoice['gross_amount']; $trans+=$invoice['transport']; $total+=$invoice['total_amount'];
				$paid+=$invoice['paid']; $dues+=$invoice['dues'];
			}	
			if(isset($_GET['customer']) && trim($_GET['customer'])!=""){
	?>
    <tr style="font-size:16px; font-weight:500;">
    	<td align="center" colspan="3">Total</td>
    	<td align="center"><?php echo toDecimal($gross); ?></td>
    	<td align="center"><?php echo toDecimal($trans); ?></td>
    	<td align="center"><?php echo toDecimal($total); ?></td>
    	<td align="center"><?php echo toDecimal($paid); ?></td>
    	<td align="center"><?php echo toDecimal($dues); ?></td>
        <td></td>
    </tr>
    <?php
			}
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="13">No Records Found!!</td>
    </tr>
    <?php
		}
		if($pages>1){
	?>
    <tr>
    	<td colspan="13" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="customer.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
				?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="customer.php?pagename=report&page=<?php echo $i; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
					?>"><?php echo $i; ?></a>
                </li>
          	</ul>
    <?php		
				}
				elseif($pages>5 && ($i==4 || $i==$pages-3)){
	?>
			<ul class="pagination pagination-sm">
    			<li>
                	<a>...</a>
                </li>
          	</ul>
    <?php
				}
			}
			if($page!=$pages){
	?>
    		<ul class="pagination pagination-sm">
    			<li><a href="customer.php?pagename=report&page=<?php echo $page+1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
				?>">Next</a></li>
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