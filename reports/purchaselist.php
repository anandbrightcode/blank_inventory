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
	$table="`purchase`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="`invoice`='$query' and shop='$shop'";
	}
	elseif(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$where="(`date` between '$from' and '$to') and shop='$shop'";
		echo "<a href='purchasetoxsl.php?from=$from&to=$to&shop=$shop' class='btn btn-sm btn-primary'>Export to Excel <i class='fa fa-download'></i></a>";
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
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >
    <thead>
    	<tr>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Invoice</th>
            <th style="text-align:center">Supplier</th>
            <th style="text-align:center">Gross Amount</th>
            <th style="text-align:center">Transport</th>
            <th style="text-align:center">Final Amount</th>
            <th style="text-align:center">Paid</th>
            <th style="text-align:center">Dues</th>
            <th style="text-align:center">Action</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $purchase){
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($purchase['date'])); ?></td>
    	<td align="center"><?php echo $purchase['invoice']; ?></td>
    	<td align="center"><?php $supplier=$obj->get_details("`supplier`","`name`","`id`='".$purchase['supplier']."'"); echo $supplier['name']; ?></td>
    	<td align="center"><?php echo toDecimal($purchase['gross_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($purchase['transport']); ?></td>
    	<td align="center"><?php echo toDecimal($purchase['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($purchase['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($purchase['dues']); ?></td>
    	<td align="center">
        	<button type="button" class="btn btn-info btn-sm" onClick="viewPurchase('<?php echo $purchase['id']; ?>');"><i class="fa fa-eye"></i></button>
          <!--<a href="../purchase/edit_purchase.php?id=<?php echo $purchase['id']."&page=report"; ?>" class="btn btn-warning btn-sm fa fa-edit"></a>-->
        </td>
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
    			<li><a href="purchase_order.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
				?>" >Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="purchase_order.php?pagename=report&page=<?php echo $i; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
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
    			<li><a href="purchase_order.php?pagename=report&page=<?php echo $page+1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
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