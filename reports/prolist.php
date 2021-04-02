<?php
	if(isset($_GET['id'])){
		include('../action/class.php');
		$obj=new database();
		$shop=$_GET['shop'];
		$id=$_GET['id'];
		$array2=$obj->get_rows("`services`","*","`quot_id`='$id' and `shop`='$shop'");
	}
?>
<table class="table table-striped table-bordered ">
	<tr>
    	<th style="text-align:center;">Sl No</th>
    	<th style="text-align:center;">Particulars</th>
    	<th style="text-align:center;">HSN Code</th>
    	<th style="text-align:center;">UOM</th>
    	<th style="text-align:center;">Quantity</th>
    	<th style="text-align:center;">Rate</th>
    	<th style="text-align:center;">Charity</th>
    	<th style="text-align:center;">Discount</th>
    	<th style="text-align:center;">Taxable</th>
    	<th style="text-align:center;">GST</th>
    	<th style="text-align:center;">Amount</th>
    </tr>
    <?php
    	if(is_array($array2)){$i=0;
			foreach($array2 as $product){$i++;				
	?>
    <tr>
    	<td align="center"><?php echo $i; ?></td>
    	<td align="center"><?php echo $product['particulars']; ?></td>
    	<td align="center"><?php echo $product['hsn']; ?></td>
    	<td align="center"><?php echo $product['uom']; ?></td>
    	<td align="center"><?php echo $product['quantity']; ?></td>
    	<td align="center"><?php echo $product['price']; ?></td>
    	<td align="center"><?php echo $product['charity']; ?></td>
    	<td align="center"><?php echo $product['discount']."%"; ?></td>
    	<td align="center"><?php echo $product['taxable']; ?></td>
    	<td align="center">
		<?php 
			if($product['igst']==0){
                echo "CGST : ".$product['cvalue'];  echo "<br>SGST : ".$product['svalue'];
        	}else{echo "IGST : ".$product['ivalue']; }
		?>
        </td>
    	<td align="center"><?php echo $product['amount']; ?></td>
    </tr>
    <?php	
			}	
		}
	?>
</table>
<button type="button" class="btn btn-danger btn-sm" onClick="closeThis();">Close</button>