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
	$table="`supplier`";
	$columns="`id`,`name`,`phone`,`email`,`gst`,`address`";
	if(isset($_GET['query']) && trim($_GET['query'])!=""){
		$query=$_GET['query'];
		$where="(id='$query' or name like '%$query%') and shop='$shop'";
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
            <th style="text-align:center">Id</th>
            <th style="text-align:center">Name</th>
            <th style="text-align:center">Phone</th>
            <th style="text-align:center">Email</th>
            <th style="text-align:center">GSTIN</th>
            <th style="text-align:center">Address</th>
            <th style="text-align:center">Action</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){
			foreach($array as $supplier){
	?>
    <tr>
    	<td align="center"><?php echo $supplier['id']; ?></td>
    	<td align="center"><?php echo $supplier['name']; ?></td>
    	<td align="center"><?php echo $supplier['phone']; ?></td>
    	<td align="center"><?php echo $supplier['email']; ?></td>
    	<td align="center"><?php echo $supplier['gst']; ?></td>
    	<td align="center"><?php echo $supplier['address']; ?></td>
    	<td align="center">
			<a href="supplierdetails.php?id=<?php echo $supplier['id']."&pagename=supplier"; ?>" class="btn btn-warning btn-xs fa fa-eye"></a>
			<a href="supplieredit.php?id=<?php echo $supplier['id']."&pagename=supplier"; ?>" class="btn btn-info btn-xs fa fa-edit"></a>
			<a href="../action/delete.php?id=<?php echo $supplier['id']; ?>&supDelete=supDelete" class="btn btn-danger btn-xs fa fa-trash" onclick="return confirmDel()"></a>
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
				if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
	?>	
    		<ul class="pagination pagination-sm">
    			<li <?php if($i==$page){echo "class='active'";} ?>>
                	<a onClick="navigate('<?php if(isset($_GET['query'])){echo $_GET['query'];}else{echo "";} ?>','<?php echo $i; ?>')"><?php echo $i; ?></a>
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