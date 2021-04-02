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
	$table="`sales`";
	$columns="sum(`amount`) as sum,sum(`taxable`) as taxable,(`cgst`+`sgst`+`igst`) as gst,`invoice_id`";
	$pagefilters="";
	$where="`shop`='$shop'";
	
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from'];$to=$_GET['to'];
		if($from!='' && $to!=''){
			$where.=" and invoice_id in(select id from invoice where  (date >= '$from' and date <= '$to') and shop='$shop')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$where.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')  ";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$where.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')";
		}
		$pagefilters.="&from=$from&to=$to";
	}
	//echo $where; 
	$order="id";
	$limit="$offset,$count";
	$groupby="`invoice_id`,(`cgst`+`igst`+`sgst`)";
    $array=$obj->get_rows($table,$columns,$where,$order,'',$groupby);
	if(is_array($array))
	{
	$rowcount=sizeof($array);
	}
	else
	
	{
		$rowcount=0;
	}
    $array=$obj->get_rows($table,$columns,$where,$order,$limit,$groupby);
	$pages=ceil($rowcount/$count);
	
?>
<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:95%;" >
    <thead>
    	<tr>
             <th style="text-align:center">GSTIN</th>
              <th style="text-align:center">Receiver Name</th>
               <th style="text-align:center">Invoice No</th>
            <th style="text-align:center">Invoice Date</th>
             <th style="text-align:center">Invoice Value</th>
            <th style="text-align:center">Place of Supplier</th>
            <th style="text-align:center">Reverse Charge</th>
            <th style="text-align:center">Invoice Type</th>
            <th style="text-align:center">Rate</th>
            <th style="text-align:center">Taxable Value</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $sales){
				$getinv=$obj->get_details("`invoice`","*,concat(`prefix`,`invoice_no`) as `inv`","`id`='$sales[invoice_id]'");
	?>
    <tr>
        
    	<td align="center"><?php echo $getinv['gst']; ?></td>
        <td align="center"><?php echo $getinv['customer_name']; ?></td>
        <td align="center"><?php echo $getinv['inv']; ?></td>
        
    	<td align="center"><?php echo date('d-m-Y',strtotime($getinv['date'])); ?></td>
    	<td align="center"><?php echo toDecimal($sales['sum']); ?></td>
    	<td align="center"><?php echo ($getinv['code'].'-'.$getinv['state']); ?></td>
         <td align="center"><?php echo $getinv['reverse']; ?></td>
         <td align="center"><?php echo $getinv['billing_mode']; ?></td>
    	<td align="center"><?php echo toDecimal($sales['gst']); ?></td>
    	<td align="center"><?php echo toDecimal($sales['taxable']); ?></td>
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
		?>
</table>
<div class="text-center">
<?php
echo pagination("taxes.php?pagename=reports",$pages,$page,$pagefilters);
?>
</div>
<?php
	function pagination($ref,$pages,$page,$pagefilters=''){

		$pagination=$current="";

		if($pages>1){

			if($page!=1){

				$pagination.=createpagelinks($ref,$page-1,"Prev",$current,$pagefilters);

			}

			for($i=1;$i<=$pages;$i++){

				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){

					if($i==$page){$current=true;}else{$current='';}

					$pagination.=createpagelinks($ref,$i,$i,$current,$pagefilters);

				}

				elseif($pages>5 && ($i==4 || $i==$pages-3)){

					$pagination.=" <ul class='pagination pagination-sm'><li><a>...</a></li></ul> ";

				}

			}

			if($page!=$pages){

				$pagination.=createpagelinks($ref,$page+1,"Next",$current,$pagefilters);

			}

		}

		return $pagination;

	}

	

	function createpagelinks($ref,$page,$link,$current,$pagefilters){

		$pagelink="<ul class='pagination pagination-sm'>";

    	$pagelink.="<li";

		if($current!=''){$pagelink.=" class='active'";}

		$pagelink.="><a href='".$ref."&page=".$page.$pagefilters."'>".$link."</a></li>";

       	$pagelink.="</ul> ";

		return $pagelink;

	}


?>