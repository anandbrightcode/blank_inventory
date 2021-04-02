<?php
	if(isset($_GET['shop'])){
		$shop=$_GET['shop'];
		include('../action/class.php');
		$obj=new database();
	}
?>
<table class="table table-bordered table-condensed">
	<tr class="bg-primary">	
    	<th style="text-align:center">Sl no</th>
    	<th style="text-align:center">Description</th>
    	<th style="text-align:center">hsn</th>
    	<th style="text-align:center">uom</th>
    	<th style="text-align:center">quantity</th>
    	<th style="text-align:center">Rate</th>
    	<th style="text-align:center">Gst(%)</th>
		<th style="text-align:center">Amount</th>
    	<th style="text-align:center">GST Value</th>
    	<th style="text-align:center"> Total Amount</th>
    	<th style="text-align:center">Action</th>
    </tr>
    <?php
		$select_temp=$obj->get_rows("`quotservice_temp`","*","`shop`='$shop'");
		$i=0;
		$samount=0;
		if(is_array($select_temp)){
			foreach($select_temp as $temp){$i++;
	?>
    <tr>
    	<td align="center"><?php echo $i;  ?></td>
    	<td align="center"><?php echo $temp['description'];  ?></td>
        <td align="center"><?php echo $temp['shsn'];  ?></td>
    	<td align="center"><?php echo $temp['suom'];  ?></td>
        <td align="center"><?php echo $temp['squantity'];  ?></td>
    	<td align="center"><?php echo $temp['srate'];  ?></td>
        <td align="center"><?php echo $temp['ser_gst'];  ?></td>
		<td align="center"><?php echo toDecimal($temp['samount']);  ?></td>

    	<td align="center"><?php echo toDecimal($temp['ser_gstvalue']);  ?></td>
    	<td align="center"><?php echo toDecimal($temp['stotal_amount']);  ?></td>
        <td align="center">
        	<!--<button type="button" class="btn btn-primary btn-xs fa fa-edit" title="Edit"></button>-->
        	<button type="button" class="btn btn-danger btn-xs fa fa-trash" title="Delete" 
            		onClick="deleteTempqutoservice('<?php echo $temp['id']; ?>')"></button>
        </td>
    </tr>
    <?php
				$samount+=$temp['stotal_amount'];
			}
		}
		$samount = twoDigits($samount);
		$stotal=round($samount);
		$sround=$stotal-$samount;
		$sround = twoDigits($sround);
	?>
</table>
<input type="hidden" id="temp_samount" value="<?php echo $samount; ?>" />
<input type="hidden" id="temp_sround" value="<?php echo $sround; ?>" />
<input type="hidden" id="temp_stotal" value="<?php echo $stotal; ?>" />