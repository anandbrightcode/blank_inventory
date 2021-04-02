<?php 
include_once "../action/class.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
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
	$table="`sales` t1, `category` t2,`company` t3";
	$columns="t1.*,concat(t2.`name`,' ',t3.`name`,' ',t1.`model`) as product ";
	$where="t1.`shop`='$shop' and t1.`category`=t2.`id` and t1.`company_id`=t3.`id`";
	$pagefilters="";
	if(isset($_GET['query']) && trim($_GET['query'])!=''){
		$query=$_GET['query'];
		$where.=" and ((concat(t2.`name`,' ',t3.`name`,' ',t1.`model`) like '%$query%') or t1.`invoice_id` in (SELECT id from invoice where `invoice_no`='$query'))";
		$pagefilters.="&query=$query";
	}
	if(isset($_GET['type']) && trim($_GET['type'])!=''){
		$type=$_GET['type'];
		$where.=" and t1.`invoice_id` in (SELECT id from invoice where `billing_mode`='$type')";
		$pagefilters.="&type=$type";
	}
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from'];$to=$_GET['to'];
		if($from!='' && $to!=''){
			$where.=" and t1.`invoice_id` in (SELECT id from invoice where (date >= '$from' and date <= '$to'))";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$where.=" and t1.`invoice_id` in (SELECT id from invoice where date = '$date')";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$where.=" and t1.`invoice_id` in (SELECT id from invoice where date = '$date')";
		}
		$pagefilters.="&from=$from&to=$to";
	}
	$order="t1.`id`";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where,"t1.`id`");
	$pages=ceil($rowcount/$count);
	
?>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >
    <thead>
    	<tr>
            <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Product</th>
            <th style="text-align:center">HSN Code</th>
            <th style="text-align:center">Quantity</th>
            <th style="text-align:center">Rate</th>
            <th style="text-align:center">Charity</th>
            <th style="text-align:center">Discount</th>
            <th style="text-align:center">Cust. Disc.</th>
            <th style="text-align:center">Taxable</th>
            <th style="text-align:center">GST</th>
            <th style="text-align:center">Amount</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $product){
				$arr=$obj->get_details("`invoice`","`invoice_no`,`date`","`id`=".$product['invoice_id']."");
	?>
    <tr>
    	<td align="center"><?php echo $arr['invoice_no']; ?></td>
    	<td align="center"><?php  echo date('d-m-Y',strtotime($arr['date'])); ?></td>
    	<td align="center"><?php echo $product['product']; ?></td>
    	<td align="center"><?php echo $product['hsn']; ?></td>
    	<td align="center"><?php echo $product['quantity']; ?></td>
    	<td align="center"><?php echo $product['price']; ?></td>
    	<td align="center"><?php echo $product['charity']; ?></td>
    	<td align="center"><?php echo $product['discount']; ?></td>
    	<td align="center"><?php echo $product['custdiscount']."%"; ?></td>
    	<td align="center"><?php echo $product['taxable']; ?></td>
    	<td align="center">
		<?php 
			if($product['ivalue']==0){
				echo "CGST : ".$product['cvalue']."<br>";
				echo "SGST : ".$product['svalue'];
			}else{
				echo "IGST : ".$product['ivalue'];
			}
		?>
        </td> 
    	<td align="center"><?php echo $product['amount']; ?></td>
    </tr>
    <?php
			}	
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="14">No Records Found!!</td>
    </tr>
    <?php
		}
		if($pages>1){
	?>
    <tr>
    	<td colspan="14" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li>
                	<a href="../sales/?pagename=sales&page=<?php echo $page-1; echo $pagefilters; ?>" >Prev</a>
               	</li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="../sales/?pagename=sales&page=<?php echo $i; echo $pagefilters; ?>" >
					<?php echo $i; ?></a>
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
    			<li><a href="../sales/?pagename=sales&page=<?php echo $page+1; echo $pagefilters; ?>" >
                Next</a></li>
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