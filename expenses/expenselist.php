<?php 
include_once "../action/class.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}
	$count=1;
	$offset =0;
	if(isset($_GET['page'])){
		$page=$_GET['page'];
	}
	else{
		$page=1;	
	}
	$offset=($page-1)*$count;
	$table="`expense`";
	$columns="*";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="(bill='$query' or name like '%$query%') and shop='$shop'";
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
            <th style="text-align:center">Bill No</th>
            <th style="text-align:center">Name</th>
            <th style="text-align:center">Particulars</th>
            <th style="text-align:center">Amount</th>
            <th style="text-align:center">Action</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $expense){
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($expense['date'])); ?></td>
    	<td align="center"><?php echo $expense['bill']; ?></td>
    	<td align="center"><?php echo $expense['name']; ?></td>
    	<td align="center"><?php echo $expense['particulars']; ?></td>
    	<td align="center"><?php echo $expense['amount']; ?></td>
    	<td align="center">
        
		</td>
    </tr>
    <?php
			}	
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="7">No Records Found!!</td>
    </tr>
    <?php
		}
		if($pages>1){
	?>
    <tr>
    	<td colspan="7" align="center">
    <?php
			if($page!=1){
	?>	
    		<ul class="pagination pagination-sm">
    			<li><a onClick="navigate('<?php if(isset($_GET['query'])){echo $_GET['query'];}else{echo "";} ?>','<?php echo $page-1; ?>')">Prev</a></li>
          	</ul>
    <?php
			}
			for($i=1;$i<=$pages;$i++){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a onClick="navigate('<?php if(isset($_GET['query'])){echo $_GET['query'];}else{echo "";} ?>','<?php echo $i; ?>')"><?php echo $i; ?></a>
                </li>
          	</ul>
    <?php				
			}
			if($page!=$pages){
	?>
    		<ul class="pagination pagination-sm">
    			<li><a onClick="navigate('<?php if(isset($_GET['query'])){echo $_GET['query'];}else{echo "";} ?>','<?php echo $page+1; ?>')">Next</a></li>
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