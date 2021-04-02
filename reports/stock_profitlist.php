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
	$table="`stock`";
	$columns="*";
	$where="`shop`='$shop'";
	if(isset($_GET['comp']) && trim($_GET['comp'])!=""){
		 $comp=$_GET['comp'];
		$where.=" and `company_id`='$comp'";
		$pagefilters="&comp=$comp";
	}
	if(isset($_GET['cat']) && trim($_GET['cat'])!=""){
		$cat=$_GET['cat'];
		$where.=" and `category`='$cat'";
		$pagefilters.="&cat=$cat";
	}
	if(isset($_GET['model']) && trim($_GET['model'])!=""){
		$model=$_GET['model'];
		$where.=" and `model`='$model'";
		$pagefilters.="&model=$model";
	}
	
	$result=$obj->get_details("`purchase_order`","sum(purchase_gst) as cp",$where);
	
	$result2=$obj->get_details("`sales`","sum(amount) as sp",$where);
	
	$order="id";
	$limit="$offset,$count";
	$array=$obj->get_rows($table,$columns,$where,$order,$limit);
	$rowcount=$obj->get_count($table,$where);
	$pages=ceil($rowcount/$count);
	
?>
<div class="col-md-4 text-primary">
    <h4>Total Purchase Amount:<?php echo toDecimal($result['cp']);?></h4></div>
  <div class="col-md-4 text-info">  <h4>Total Sale Amount:<?php echo toDecimal($result2['sp']);?></h4></div>
   <div class="col-md-4 text-success"> <h4><?php if($result['cp']>$result2['sp']){ echo "Loss:".toDecimal($result['cp']-$result2['sp']);}else{ echo "Profit:".toDecimal($result2['sp']-$result['cp']);}?> </h4>
    
</div>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >

    <thead>
    	<tr>
            <th style="text-align:center">Sl No</th>
            <th style="text-align:center">Category</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Model</th>
            <th style="text-align:center">Purchase</th>
            <th style="text-align:center">Sale</th>
            <th style="text-align:center">Remaining</th>
            <th style="text-align:center">Profit/Loss</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){$i=$offset;
			foreach($array as $stock){$i++;
				$getcategory=$obj->get_details("`category`","`name`","`id`='$stock[category]'");
				$getcompany=$obj->get_details("`company`","`name`","`id`='$stock[company_id]'");
				
			     $where1="`category`='$stock[category]' && `company_id`='$stock[company_id]' && `model`='$stock[model]'";
				 
				 $array2=$obj->get_details("`sales`","sum(`quantity`) as sell",$where1);
				 $array3=$obj->get_details("`purchase_order`","sum(`quantity`) as cost",$where1);
				 
				 $array4=$obj->get_details("`sales`","sum(`amount`) as amount",$where1);
				 $array5=$obj->get_details("`purchase_order`","sum(`purchase_gst`) as amount",$where1);
				 
	?>
    <tr>
    	<td align="center"><?php echo $i; ?></td>
    	<td align="center"><?php echo $getcategory['name']; ?></td>
    	<td align="center"><?php echo $getcompany['name']; ?></td>
    	<td align="center"><?php echo $stock['model']; ?></td>
       	<td align="center"><?php echo $array3['cost']; ?></td>
    	<td align="center"><?php if($array2['sell']==""){ echo "0";}else {echo $array2['sell'];} ?></td>
        <td align="center"><?php echo ($array3['cost']-$array2['sell']); ?></td>
        <td align="center"><?php if(($array5['amount']-$array4['amount'])>0){ echo "Loss:".toDecimal($array5['amount']-$array4['amount']);}else{ echo "Profit:".toDecimal($array4['amount']-$array5['amount']);}?></td>
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
    			<li><a href="../reports/stock.php?pagename=report&page=<?php echo $page-1; 
						if(isset($_GET['pagefilters'])){echo "&pagefilters=".$_GET['pagefilters'];}
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
                	<a href="../reports/stock.php?pagename=report&page=<?php echo $i; 
						if(isset($_GET['pagefilters'])){echo "&pagefilters=".$_GET['pagefilters'];}
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
    			<li><a href="../reports/stock.php?pagename=report&page=<?php echo $page+1; 
						if(isset($_GET['pagefilters'])){echo "&pagefilters=".$_GET['pagefilters'];}
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