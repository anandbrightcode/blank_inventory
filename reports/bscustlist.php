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
	$table="`customer_pay`";
	$columns="*";
	$pagefilters="";
	$where="`shop`='$shop'";
	if(isset($_GET['customer']) && trim($_GET['customer'])!=""){
		$customer=$_GET['customer'];
		$where.="and `customer_id`='$customer'";
		$pagefilters.="&customer=$customer";
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
	//print_r($array);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	$gettotal=$obj->get_details("`invoice`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`",$where);
	$total_amount=$total_paid=$total_dues=0;
	if(is_array($gettotal)){
		$total_amount=$gettotal['total'];
		$total_paid=$gettotal['paid'];
		$total_dues=$gettotal['dues'];
	}
?>

<div class="col-md-4 text-primary"><h4>Total Amount : Rs <?php echo toDecimal($total_amount); ?></h4></div>
<div class="col-md-4 text-success"><h4>Total Paid : Rs <?php echo toDecimal($total_paid); ?></h4></div>
<div class="col-md-4 text-danger"><h4>Total Dues : Rs <?php echo toDecimal($total_dues); ?></h4></div>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:95%;" >
    <thead>
    	<tr>
            <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Customer</th>
            <th style="text-align:center">Mode</th>
           <!-- <th style="text-align:center">Cheq. No</th>
            <th style="text-align:center">Cheq. Date</th>
            <th style="text-align:center">Bank</th>-->
            <th style="text-align:center">Total Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
            <th style="text-align:center">Action</th>
            
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $customer){
				$getinv=$obj->get_details("`invoice`","concat(`prefix`,`invoice_no`) as `inv`","`id`='$customer[invoice_id]'");
				//$result=$obj->get_details("`invoice`","`payment_mode`, `cheque_no`, `cheque_date`, `bank`","`id`='$customer[invoice_id]'");
				//print_r($result);
	?>
    <tr>
    	<td align="center"><?php echo $getinv['inv']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($customer['date'])); ?></td>
    	<td align="center"><?php $cust=$obj->get_details("`customer`","`name`","`id`='".$customer['customer_id']."'");echo $cust['name']; ?></td>
        <td align="center"><?php echo $customer['payment_mode']; ?></td>
        <!-- <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_no']; }else { echo "";}?></td>
          <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['cheque_date']; }else { echo "";}?></td>
        <td align="center"><?php if($result['payment_mode']=='cheque'){echo $result['bank']; }else { echo "";}?></td>-->
       
    	<td align="center"><?php echo toDecimal($customer['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($customer['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($customer['dues']); ?></td>
        <td align="center"><a href="../invoice/print_invoice.php?inv_id=<?php echo $customer['invoice_id']."&page=report"; ?>" class="btn btn-danger btn-xs fa fa-print"></a></td>
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
		if($pages>1){
	?>
    <tr>
    	<td colspan="6" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="balancesheet.php?pagename=report&page=<?php echo $page-1; echo $pagefilters; ?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="balancesheet.php?pagename=report&page=<?php echo $i;  echo $pagefilters; ?>"><?php echo $i; ?></a>
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
    			<li><a href="balancesheet.php?pagename=report&page=<?php echo $page+1;  echo $pagefilters; ?>">Next</a></li>
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