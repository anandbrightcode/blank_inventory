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
	if(isset($_GET['customer']) && trim($_GET['customer'])!=""){
		$customer=$_GET['customer'];
		$where="`customer_id`='$customer' and shop='$shop'";
	}
	elseif(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$where="(`date` between '$from' and '$to') and shop='$shop'";
	}
	else{
		$where="`shop`='$shop'";
	}
	$order="id";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	
?>
<table class="table-striped table-bordered table-hover table-condensed col-md-6" id="c_list" >
    <thead>
    	<tr>
            <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Customer</th>
            <th style="text-align:center">Total Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $customer){
	?>
    <tr>
    	<td align="center"><?php echo $customer['invoice_id']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($customer['date'])); ?></td>
    	<td align="center"><?php $cust=$obj->get_details("`customer`","`name`","`id`='".$customer['customer_id']."'");echo $cust['name']; ?></td>
    	<td align="center"><?php echo toDecimal($customer['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($customer['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($customer['dues']); ?></td>
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
    			<li><a href="balancesheet.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['spage'])){echo "&spage=".$_GET['spage'];}
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
				?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="balancesheet.php?pagename=report&page=<?php echo $i; 
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['spage'])){echo "&spage=".$_GET['spage'];}
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
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
    			<li><a href="balancesheet.php?pagename=report&page=<?php echo $page+1; 
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['spage'])){echo "&spage=".$_GET['spage'];}
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
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
<?php
	$scount=20;
	$soffset =0;
	if(isset($_GET['spage'])){
		$spage=$_GET['spage'];
	}
	else{
		$spage=1;	
	}
	$soffset=($spage-1)*$scount;
	$stable="`supplier_pay`";
	$scolumns="*";
	if(isset($_GET['supplier']) && trim($_GET['supplier'])!=""){
		$supplier=$_GET['supplier'];
		$swhere="`supplier_id`='$supplier' and shop='$shop'";
	}
	elseif(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$swhere="(`date` between '$from' and '$to') and shop='$shop'";
	}
	else{
		$swhere="`shop`='$shop'";
	}
	$sorder="id";
	$slimit="$soffset,$scount";
	$sarray=$obj->get_rows($stable,$scolumns,$swhere,$sorder,$slimit);
	$srowcount=$obj->get_count($stable,$swhere);
	$spages=ceil($srowcount/$scount);
	
?>
<table class="table-striped table-bordered table-hover table-condensed col-md-6" id="s_list" >
    <thead>
    	<tr>
            <th style="text-align:center">Invoice</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Supplier</th>
            <th style="text-align:center">Total Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($sarray)){
			foreach($sarray as $supplier){
	?>
    <tr>
    	<td align="center"><?php echo $supplier['invoice']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($supplier['date'])); ?></td>
    	<td align="center"><?php $sup=$obj->get_details("`supplier`","`name`","`id`='".$supplier['supplier_id']."'");echo $sup['name']; ?></td>
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
		if($spages>1){
	?>
    <tr>
    	<td colspan="6" align="center">
    <?php
			if($spage!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="balancesheet.php?pagename=report&spage=<?php echo $spage-1; 
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['page'])){echo "&page=".$_GET['page'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
				?>">Prev</a></li>
          	</ul>
    <?php
			}
			for($si=1;$si<=$spages;$si++){
				if($si<4 || $si>$spages-3 || $si==$spage || $si==$spage-1 || $si==$spage+1 || $si==$spage-2 || $si==$spage+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($si==$spage){echo "class='active'";} ?>>
                	<a href="balancesheet.php?pagename=report&spage=<?php echo $i; 
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['page'])){echo "&page=".$_GET['page'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
					?>"><?php echo $si; ?></a>
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
    			<li><a href="balancesheet.php?pagename=report&spage=<?php echo $spage+1; 
						if(isset($_GET['supplier'])){echo "&supplier=".$_GET['supplier'];}
						if(isset($_GET['page'])){echo "&page=".$_GET['page'];}
						if(isset($_GET['customer'])){echo "&customer=".$_GET['customer'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
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