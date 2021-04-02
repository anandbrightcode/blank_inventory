<?php 
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
	include_once "../action/class.php";
	$obj=new database();
}
	$count=15;
	$offset =0;
	if(isset($_GET['page'])){
		$page=$_GET['page'];
	}
	else{
		$page=1;	
	}
	$offset=($page-1)*$count;
	$table="`cust_pay_details`";
	$columns="*";
	$pagefilters="";
	$where="`shop`='$shop'";
	if(isset($_GET['customer']) && trim($_GET['customer'])!=""){
		$customer=$_GET['customer'];
		$where.="and `customer_id`='$customer'";
		$pagefilters.="&customer=$customer";
	}
	else{
		exit;
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
		$pagefilters.="&from=$from&to=$to";
	}
	//echo $where; 
	$order="id";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	$arr=$obj->get_details("`invoice`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`","`customer_id`='$customer' and `shop`='$shop'");
	$getadvance=$obj->get_details("`customer`","`advance`","`id`='$customer'");
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
    	<div class="sections"><a href="customer_pay.php?id=<?php echo $customer; ?>&pagename=report" class="btn btn-sm btn-primary pull-right">Make Payment</a></div>
 	</div>
</div>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:95%;" >
    <thead>
    	<tr>
            <th style="text-align:center">Sl. No.</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Customer</th>
            <th style="text-align:center">Payment Mode</th>
            <th style="text-align:center">Cheque Number</th>
            <th style="text-align:center">Cheque Date</th>
            <th style="text-align:center">Bank</th>
            <th style="text-align:center">Paid</th>
            
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){$sl=$offset;
			foreach($array as $customer){$sl++;
	?>
    <tr>
    	<td align="center"><?php echo $sl; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($customer['date'])); ?></td>
    	<td align="center"><?php $cust=$obj->get_details("`customer`","`name`","`id`='".$customer['customer_id']."'");echo $cust['name']; ?></td>
        <td align="center"><?php echo $customer['payment_mode']; ?></td>
        <td align="center"><?php echo $customer['cheque_no']; ?></td>
        <td align="center"><?php echo $customer['cheque_date']; ?></td>
        <td align="center"><?php echo $customer['bank']; ?></td>
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
		if($pages>1){
	?>
    <tr>
    	<td colspan="6" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="payment.php?pagename=report&page=<?php echo $page-1; echo $pagefilters; ?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="payment.php?pagename=report&page=<?php echo $i;  echo $pagefilters; ?>"><?php echo $i; ?></a>
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
    			<li><a href="payment.php?pagename=report&page=<?php echo $page+1;  echo $pagefilters; ?>">Next</a></li>
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