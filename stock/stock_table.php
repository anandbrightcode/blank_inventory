
      <table class="table datatable">
            <thead>
                
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Category</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Company</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Model</th>
                <th style="background-color:#95FFFE; text-align:center; vertical-align:middle;">HSN/SAC</th>
                <th class="bg-success" style="text-align:center; vertical-align:middle;">Quantity</th>
                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Base Price</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Selling Price</th>
                <th width="12%" style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Action</th>
            </thead>
           
            <tbody>
             <?php
                $count=20;
                $offset =0;
				if(isset($_GET['page'])){
                	$page=$_GET['page'];
				}
				else{$page=0;}
				
				if(isset($_GET['shop'])){
					$shop=$_GET['shop'];	
					include('../action/class.php');
					$obj=new database();
				}
                $offset=$page*$count;
				$table="`stock`";
				$columns="*";
				if(isset($_GET['query']) && trim($_GET['query'])!=""){
					$query=$_GET['query'];
					$where="`model` like '%$query%' or `company` like '%$comp%' and shop='$shop'";
				}
				else{
					$where="`shop`='$shop'";
				}
				$order="id";
				$limit="$offset,$count";
				$array=$obj->get_rows($table,$columns,$where,$order);
				$rowcount=$obj->get_count($table,$where);
				$pages=ceil($rowcount/$count);
    			$i=$offset;
				if(is_array($array)){
                	foreach($array as $result){
						$id=$result['id'];$i++;
            ?>
            <tr>
                
               	<td align="center"><?php echo $i;?></td>
                <td align="center"><?php $cat=$obj->get_details("`category`","`name`","`id`='".$result['category']."'"); echo $cat['name'];?></td>
                <td align="center"><?php $comp=$obj->get_details("`company`","`name`","`id`='".$result['company_id']."'"); echo $comp['name'];?></td>
                <td align="center"><?php echo $result['model'];?></td>
                <td align="center"><?php echo $result['hsn'];?></td>
                <td align="center"><?php echo $result['quantity'];?></td>
                <td align="center"><?php echo $result['base_price'];?></td>
                <td align="center"><?php echo $result['selling_price'];?></td>
                <td align="center">
                  	<a href="editstock.php?pagename=stock&id=<?php echo $id;?>" title="Edit"><i class="btn btn-info btn-xs fa fa-edit"></i></a>
                	<a href="../action/delete.php?deleteStock=deleteStock&id=<?php echo $id; ?>"  onclick="return confirmDel()"><i class="btn btn-danger btn-xs fa fa-trash"></i></a>
                </td>
            </tr>
            
            <?php 
                	}
				}
            ?>
          <?php /*?>  <tr>
             <td colspan="10" style="text-align:center">
                             <?php $page=$page +1;
                $prev= $page-2;
        if($page>1){
       ?>
        <ul class="pagination pagination-sm">
       <li><a href ="../stock?pagename=raw_stock&page=<?php echo $prev;if(isset($_GET['query'])){echo "&query=".$_GET['query'];}?>" style="color:#000000">Prev</a></li>
       </ul>
       <?php 
       }
        if(ceil($pages)>1){
		   for($i=0;$i<ceil($pages);$i++){
				if($i<3 || $i>$pages-4 || $i==$page-1 || $i==$page-2 || $i==$page || $i==$page-3 || $i==$page+1){
			 ?>
			  <ul class="pagination pagination-sm">
			  
			  <li <?php if($i==$page-1){ echo "class='active'"; }?>> 
				<a href="../stock?pagename=stock&page=<?php echo $i;if(isset($_GET['query'])){echo "&query=".$_GET['query'];} ?>"><?php echo $i+1; ?></a>
			  </li>
			  </ul>
				<?php		
					}
					elseif($pages>4 && ($i==3 || $i==$pages-4)){
                ?>
                <ul class="pagination pagination-sm">
                    <li>
                        <a>...</a>
                    </li>
                </ul>
                <?php
				}			
			}
			}//if closed
            if($page<ceil($pages)){
             ?>
              <ul class="pagination pagination-sm">
           <li><a href="../stock?pagename=raw_stock&page=<?php echo $page;if(isset($_GET['query'])){echo "&query=".$_GET['query'];} ?>" style="color:#000000">Next</a></li>
           </ul>
          <?php
       
        }
        ?>
             </td>
            </tr><?php */?>
            </tbody>
</table>