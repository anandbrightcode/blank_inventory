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
	$table="`invoice`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="invoice_no='$query' and shop='$shop'";
	}
	elseif(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$where="(`date` between '$from' and '$to') and shop='$shop'";
	}
	else{
		$where="`shop`='$shop'";
	}
	$where.=" and `transport`!=0";
	$order="id";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	$transport=$obj->get_details("`invoice`","sum(`transport`) as `total`",$where);
	echo "<h4 class='text-info'>Total Transport Expense : Rs. ".toDecimal($transport['total'])."</h4>";
	
?>
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
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $invoice){
	?>
    <tr>
    	<td align="center"><?php echo $invoice['invoice_no']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
    	<td align="center"><?php echo $invoice['customer_name']; ?></td>
    	<td align="center"><?php echo toDecimal($invoice['gross_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['transport']); ?></td></td>
    	<td align="center"><?php echo toDecimal($invoice['total_amount']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['paid']); ?></td>
    	<td align="center"><?php echo toDecimal($invoice['dues']); ?></td>
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
    			<li><a href="transport.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
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
                	<a href="transport.php?pagename=report&page=<?php echo $i; 
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
    			<li><a href="transport.php?pagename=report&page=<?php echo $page+1; 
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