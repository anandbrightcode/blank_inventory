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
	$table="`returns`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="(`invoice_id`='$query' or `model` like '%$query%') and shop='$shop'";
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
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >
    <thead>
    	<tr>
            <th style="text-align:center">Sl No</th>
            <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Category</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Model</th>
            <th style="text-align:center">HSN/SAC</th>
            <th style="text-align:center">Price</th>
            <th style="text-align:center">Quantity</th>
            <th style="text-align:center">Action</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			$i=0;
			foreach($array as $products){$i++;
				$getinv=$obj->get_details("`invoice`","`prefix`,`invoice_no`","`id`='$products[invoice_id]'");
	?>
    <tr>
    	<td align="center"><?php echo $i; ?></td>
    	<td align="center"><?php echo $getinv['prefix'].$getinv['invoice_no']; ?></td>
    	<td align="center"><?php echo date('d-m-Y',strtotime($products['date'])); ?></td>
    	<td align="center"><?php echo $products['category']; ?></td>
    	<td align="center"><?php echo $products['company']; ?></td>
    	<td align="center"><?php echo $products['model']; ?></td>
    	<td align="center"><?php echo $products['hsn']; ?></td>
    	<td align="center"><?php echo $products['price']; ?></td>
    	<td align="center"><?php echo $products['quantity']; ?></td>
    	<td align="center">
        	<a href="../returns/print.php?inv_id=<?php echo $products['invoice_id']; ?>&page=report" class="btn btn-danger btn-sm"><i class="fa fa-print"></i></a>
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
		if($pages>1){
	?>
    <tr>
    	<td colspan="9" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a href="defective.php?pagename=report&page=<?php echo $page-1;
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
                	<a href="defective.php?pagename=report&page=<?php echo $i;
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
    			<li><a href="defective.php?pagename=report&page=<?php echo $page+1;
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