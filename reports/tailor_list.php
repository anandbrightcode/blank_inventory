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
	$table="`tailoring`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="shop='$shop'";
		if($query=='tailored'){ $where.=" and `product_quantity`!='0' "; }
		else{ $where.=" and `product_quantity`='0' "; }
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
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Category</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Model</th>
            <th style="text-align:center">Pieces</th>
            <th style="text-align:center">Quantity</th>
            <th style="text-align:center">Purchase(GST)</th>
            <th style="text-align:center">Tailored</th>
            
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $purchase){
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($purchase['date'])); ?></td>
    	<td align="center"><?php echo $purchase['category']; ?></td>
    	<td align="center"><?php $comp=$obj->get_details("`company`","`name`","`id`='".$purchase['company_id']."'"); echo $comp['name']; ?></td>
    	<td align="center"><?php echo $purchase['model']; ?></td>
    	<td align="center"><?php echo $purchase['pieces']; ?></td>
    	<td align="center"><?php echo $purchase['quantity']; ?></td>
    	<td align="center"><?php echo $purchase['purchase_gst']; ?></td>
    	<td align="center">
		<?php 
			if($purchase['product_quantity']==0){echo "<i class='fa fa-times text-danger'</i>";}
			elseif($purchase['product_quantity']!=0){echo "<i class='fa fa-check text-success'</i>";}
		?>
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
    			<li><a href="tailoring.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
				?>" >Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a href="tailoring.php?pagename=report&page=<?php echo $i; 
						if(isset($_GET['query'])){echo "&query=".$_GET['query'];}
						if(isset($_GET['from'])){echo "&from=".$_GET['from'];}
						if(isset($_GET['to'])){echo "&to=".$_GET['to'];}
					?>"><?php echo $i; ?></a>
                </li>
          	</ul>
    <?php				
			}
			if($page!=$pages){
	?>
    		<ul class="pagination pagination-sm">
    			<li><a href="tailoring.php?pagename=report&page=<?php echo $page+1; 
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