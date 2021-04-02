<?php
	if(isset($_GET['id'])){
		include('../action/class.php');
		$obj=new database();
		$shop=$_GET['shop'];
		$id=$_GET['id'];
		$array2=$obj->get_rows("`purchase_order`","*","`purchase_id`='$id' and `shop`='$shop'");
	}
?>
<table class="table table-striped table-bordered ">
	<tr>
    	<th style="text-align:center" width="5%">Sl no</th>
    	<th style="text-align:center">Particulars</th>
    	<th style="text-align:center">HSN</th>
    	<th style="text-align:center">MRP</th>
    	<th style="text-align:center">Quantity</th>
    	<th style="text-align:center">Rate</th>
    	<th style="text-align:center">Charity</th>
    	<th style="text-align:center">Discount</th>
    	<th style="text-align:center">Disc.(%)</th>
    	<th style="text-align:center">Taxable Value</th>
    	<th style="text-align:center"">GST</th>
    	<th style="text-align:center">Base Price</th>
    </tr>
    <?php
    	if(is_array($array2)){$i=0;
			foreach($array2 as $product){$i++;
				$purchase=$product['purchase']; 
				
	?>
    <tr>
    	<td align="center"><?php echo $i;  ?></td>
    	<td align="left">
			<?php 
				$cat=$obj->get_details("`category`","`name`","`id`='".$product['category']."'"); echo $cat['name']." - ";  
				$comp=$obj->get_details("`company`","`name`","`id`='".$product['company_id']."'"); echo $comp['name'];  
			 	echo "<br>".$product['model'];  ?>
        </td>
    	<td align="center"><?php echo $product['hsn'];  ?></td>
    	<td align="center"><?php echo $product['mrp'];  ?></td>
    	<td align="center"><?php echo $product['quantity'];  ?></td>
    	<td align="center"><?php echo toDecimal($product['purchase']);  ?></td>
    	<td align="center"><?php echo toDecimal($product['charity']);  ?></td>
    	<td align="center"><?php echo toDecimal($product['discount']);  ?></td>
    	<td align="center">
		<?php 
			$special_discount=$product['special_discount'];
			$cash_discount=$product['cash_discount'];
			echo $product['custdiscount']."% + ";
			if(is_float($special_discount)){echo $special_discount."% + "; }else{ echo (int)$special_discount."% + "; }
			if(is_float($cash_discount)){echo $cash_discount."%"; }else{ echo (int)$cash_discount."%"; }
		?>
        </td>
    	<td align="center"><?php echo toDecimal($product['taxable']);  ?></td>
    	<td align="center">
			<?php 
				if($product['cgst']!=0){echo "CGST : ".$product['cvalue']."<br>";}  
				if($product['sgst']!=0){echo "SGST : ".$product['svalue'];}  
				if($product['igst']!=0){echo "IGST : ".$product['ivalue'];}  
			?>
        </td>
    	<td align="center"><?php echo toDecimal($product['purchase_gst']);  ?></td>
    </tr>
    <?php	
			}	
		}
	?>
</table>
<button type="button" class="btn btn-danger btn-sm" onClick="closeThis();">Close</button>