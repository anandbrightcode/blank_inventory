<?php
session_start();
	include('../action/class.php');
	if(isset($_SESSION['user'])){
	  	$role=$_SESSION['role'];
      	$user=$_SESSION['user'];
      	$shop=$_SESSION['shop'];
	  	$inv_id=$_GET['inv_id'];
	  	$pre=$_GET['page'];
  	}
  	else{
	  	header("Location:index.php");
  	}
	include('notowords.php');
	$obj=new database();
	$words=new notowords;
	$invoice=$obj->get_details("`invoice`","*","`id`='$inv_id' and `shop`='$shop'");
	$sales=$obj->get_rows("`sales`","*","`invoice_id`='$inv_id' and `shop`='$shop'");
	$shop_details=$obj->get_details("`shop`","*","`id`='$shop'");
	$checkigst=$obj->get_details("`sales`","sum(`ivalue`) as `ivalue`,sum(`sgst`+`cgst`+`igst`) as `gst`","`invoice_id`='$inv_id'");
	//echo $checkigst['gst'];
	$rspan="rowspan='";
	if($checkigst['gst']==0){$rspan="";}elseif($checkigst['ivalue']==0) {$rspan.="4'";}else{$rspan.="3'";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="UTF-8">
        <title>Invoice</title>
        <style type="text/css" media="print">
			@page {
					margin:0 10px;
					/*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
					/*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
			}
			@media print{
				table {page-break-inside: avoid;}
				#buttons{
						display:none;
				}
				#invoice{
					margin-top:20px;
  				}
			}
		</style>
    </head>
    
    <body>
    	<div id="invoice" style="width:1000px;">
            <center>
                <font size="+1" style="border:1px solid #000000; padding:5px;"><?php if($invoice['billing_mode']=='retail'){echo "Retail Invoice";} elseif($invoice['billing_mode']=='tax'){echo "Tax Invoice";} ?></font><br /><br>
                <font size="+3" style="letter-spacing:2px"><?php echo strtoupper($shop_details['name']); ?><br /></font>
                <font size="+1"><?php echo $shop_details['address'];  ?><br />
                <?php  if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; ?><br />
                Ph : <?php echo $shop_details['phone']; ?><br />
                <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?>
                </font><br />
            </center>
            <hr style="border:1px solid #000000;" />
            <table align="center" width="95%">
            	<tr>
                    <th align="left" width="18%">Invoice No</th>
                    <td align="left" width="34%"><?php echo $invoice['prefix'].$invoice['invoice_no']; ?></td>
                    <td align="left" width="13%"></td>
                    <th align="left" width="18%">Date</th>
                    <td align="left" width="17%"><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
                </tr>
                <tr>
                    <th align="left">Name</th>
                    <td align="left"><?php echo $invoice['customer_name']; ?></td>
                    <td align="left"></td>
                    <th align="left">Phone</th>
                    <td align="left"><?php echo $invoice['customer_mobile']; ?></td>
                </tr>
                <tr>
                    <th align="left">Address</th>
                    <td align="left" colspan="2" style="font-size:15px; padding-right:10px;"><?php echo $invoice['add_to']."<br />State : ".strtoupper($invoice['state'])." State Code : ".$invoice['code'].""; ?></td>
                    <th align="left">GSTIN</th>
                    <td align="left"><?php echo $invoice['gst']; ?></td>
                </tr>
                <tr>
                    <th align="left">Reverse Charge</th>
                    <td align="left"><?php echo ucfirst($invoice['reverse']); ?></td>
                    <td></td>
                    <th align="left">Transport Mode</th>
                    <td align="left"><?php echo ucfirst($invoice['transport_mode']); ?></td>
                </tr>
                <tr>
                    <th align="left">Payment Mode</th>
                    <td align="left"><?php echo ucfirst($invoice['payment_mode']); ?></td>
                    <td align="left"></td>
                    <th align="left">Paid Amount</th>
                    <td align="left"><?php echo toDecimal($invoice['paid']); ?></td>
                </tr>
                <?php if($invoice['dues']!=0){?>
                <tr>
                    <th align="left">Dues Amount</th>
                    <td align="left"><?php echo toDecimal($invoice['dues']); ?></td>
                    <td align="left"></td>
                    <th align="left">Next Payment</th>
                    <td align="left"><?php echo date('d-m-Y',strtotime($invoice['next_payment'])); ?></td>
                </tr>
                <?php } ?>
            </table>
            <table align="center" width="95%" height="800" border="1" cellpadding="0" cellspacing="0" id="table" >
            	<tr height="30">
                	<th align="center" width="5%" rowspan="2">S.No.</th>
                	<th align="center" width="25%" rowspan="2">Description</th>
                	<th align="center" rowspan="2">HSN</th>
                	<th align="center" rowspan="2">MRP</th>
                	<th align="center" width="5%" rowspan="2">UOM</th>
                	<th align="center" rowspan="2">Qty.</th>
                	<th align="center" rowspan="2">Rate</th>
                	<th align="center" rowspan="2">Charity</th>
                	<th align="center" rowspan="2">Disc.<br />Amt.</th>
                	<th align="center" rowspan="2">CD<br />%</th>
                	<th align="center" rowspan="2">Taxable<br />Amount</th>
                    <?php 
						if($checkigst['gst']!=0){
							if($checkigst['ivalue']==0){ 
					?>
                	<th align="center" colspan="2">CGST</th>
                	<th align="center" colspan="2">SGST</th>
                    <?php }else{ ?>
                	<th align="center" colspan="2">IGST</th>
                    <?php 
							}
						} 
					?>
                	<th align="center" rowspan="2">Total</th>
                </tr>
                <tr height="30">
                	<?php if($checkigst['gst']!=0){ ?>
                	<th align="center" width="5%">Rate</th>
                	<th align="center" width="7%">Tax</th>
                    <?php if($checkigst['ivalue']==0){ ?>
                	<th align="center" width="5%">Rate</th>
                	<th align="center" width="7%">Tax</th>
                    <?php } } ?>
                </tr>
                <?php
					if(is_array($sales)){
						$i=0;$quantity=0; $amount=0; $discount=0; $taxable=0;
						$cgst=0; $sgst=0; $igst=0; $charity=0;
						foreach($sales as $product){
							$comp=$obj->get_details("`company`","`name`","`id`='".$product['company_id']."'");
				?>
                <tr height="30">
                	<td align="center"><?php echo ++$i; ?></td>
                	<td style="padding:0px 5px;">
						<?php $cat=$obj->get_details("`category`","`name`","`id`='".$product['category']."'"); echo $cat['name']."<br />".$comp['name']."-";
								if(strlen($product['model'])>30){
									$a=substr($product['model'],0,30);
									$b=substr($product['model'],30);
									$product['model']=$a."<br />".$b;
								}
								echo $product['model'];
								if($product['slno']!=''){echo "<br>Sl.no. : ".$product['slno']; } 
						?>
                    </td>
                	<td align="center"><?php echo $product['hsn']; ?></td>
                	<td align="center"><?php echo toDecimal($product['mrp']); ?></td>
                	<td align="center"><?php echo $product['uom']; ?></td>
                	<td align="center"><?php echo $product['quantity']; ?></td>
                	<td align="center"><?php echo toDecimal($product['price']); ?></td>
                	<td align="center"><?php echo toDecimal($product['charity']); ?></td>
                	<td align="center"><?php echo $product['discount']; ?></td>
                	<td align="center"><?php echo $product['custdiscount']; ?></td>
                	<td align="center"><?php echo toDecimal($product['taxable']); ?></td>
                    <?php 
						if($checkigst['gst']!=0){
							if($checkigst['ivalue']==0){ ?>
                	<td align="center"><?php echo $product['cgst']."%"; ?></td>
                	<td align="center"><?php echo toDecimal($product['cvalue']); ?></td>
                	<td align="center"><?php echo $product['sgst']."%"; ?></td>
                	<td align="center"><?php echo toDecimal($product['svalue']); ?></td>
                    <?php 	} else{ ?>
                	<td align="center"><?php echo $product['igst']."%"; ?></td>
                	<td align="center"><?php echo toDecimal($product['ivalue']); ?></td>
                    <?php 
							} 
						}
					?>
                	<td align="center"><?php echo toDecimal($product['amount']); ?></td>
                </tr>
                <?php	
							$charity+=$product['charity'];
							$quantity+=$product['quantity'];
							$discount+=$product['discount'];
							$taxable+=$product['taxable'];
							$cgst+=$product['cvalue'];
							$sgst+=$product['svalue'];
							$igst+=$product['ivalue'];
						}
					}
				?>
                <tr id="blank">
                	<td></td><td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
					<?php  if($checkigst['gst']!=0){ echo "<td></td><td></td>";if($checkigst['ivalue']==0){ echo "<td></td><td></td>";}}?>
                </tr>
                <?php if($invoice['roundoff']!=0){ ?>
                <tr height="30">
                	<td></td>
                    <td align="center">Round Off</td>
                    <td></td><td></td><td></td>
					<?php  if($checkigst['gst']!=0){ echo "<td></td><td></td>";if($checkigst['ivalue']==0){ echo "<td></td><td></td>";}}?>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($invoice['roundoff']); ?></td>
                </tr>
                <?php } ?>
                <?php if($invoice['transport']!=0){ ?>
                <tr height="30">
                	<td></td>
                    <td align="center">Transport</td>
                    <td></td><td></td><td></td>
					<?php  if($checkigst['gst']!=0){ echo "<td></td><td></td>";if($checkigst['ivalue']==0){ echo "<td></td><td></td>";}}?>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo toDecimal($invoice['transport']); ?></td>
                </tr>
                <?php } ?>
                <tr height="30">
                	<td></td>
                    <th align="center">Total</th><td></td>
                    <td></td><td></td><td align="center"><?php echo $quantity; ?></td><td></td><td align="center"><?php echo $charity; ?></td>
                    <td align="center"><?php echo $discount; ?></td>
                    <td></td>
                    <td align="center"><?php echo toDecimal($taxable); ?></td>
					<?php 
						if($checkigst['gst']!=0){if($checkigst['ivalue']==0){ ?>
                   	<td></td><td align="center"><?php echo toDecimal($cgst); ?></td><td></td><td align="center"><?php echo toDecimal($sgst); ?></td>
                   	<?php }else{ ?>
                   	<td></td><td align="center"><?php echo toDecimal($igst); ?></td>
                   	<?php }} ?>
                    <th align="center"><?php echo toDecimal($invoice['total_amount']); ?></th>
                </tr>
                <tr height="30">
             		<th style="text-align:center;" colspan="<?php if($checkigst['gst']!=0){echo 9;}else {echo 12;} ?>">Total Invoice Amount in Words</th>
             		<?php 
						if($checkigst['gst']!=0){
					?>
                    <th style="padding-left:10px;"  <?php if($checkigst['ivalue']==0){ echo "colspan='6'"; }else{ echo "colspan='4'"; } ?>>Total Amount before Tax</th>
                	<td align="center"><?php echo toDecimal($taxable); ?></td>
                    <?php } ?>
             	</tr>
             	<tr height="30">
                	<td colspan="<?php if($checkigst['gst']!=0){echo 9;}else {echo 12;} ?>" style="padding:20px;" 
                    		<?php echo $rspan; ?>>
                		<font size="+1"><?php echo $words->to_words($invoice['total_amount'])." Only";?></font> 
               		</td>
                	<?php if($checkigst['gst']!=0){
							if($checkigst['ivalue']==0){ ?>
             		<th style="padding-left:10px;" colspan="6">Add CGST</th>
                	<td align="center"><?php echo toDecimal($cgst); ?></td>
                	<?php }else{ ?>
             		<th style="padding-left:10px;" colspan="4">Add IGST</th>
                	<td align="center"><?php echo toDecimal($igst); ?></td>
                	<?php }} ?>
             	</tr>
              	<?php if($checkigst['gst']!=0){
						if($checkigst['ivalue']==0){ ?>
             	<tr height="30">
             		<th style="padding-left:10px;" colspan="6">Add SGST</th>
                	<td align="center"><?php echo toDecimal($sgst); ?></td>
             	</tr><?php } ?>
             	<tr height="30">
             		<th style="padding-left:10px;" <?php if($checkigst['ivalue']==0){ echo "colspan='6'"; }else{ echo "colspan='4'"; } ?>>Total Tax</th>
                	<td align="center"><?php if($checkigst['ivalue']==0){ echo toDecimal($cgst + $sgst); }else{ echo toDecimal($igst);} ?></td>
             	</tr>
             	<tr height="30">
             		<th style="padding-left:10px;" <?php if($checkigst['ivalue']==0){ echo "colspan='6'"; }else{ echo "colspan='4'"; } ?>>Total Amount after Tax</th>
                	<td align="center"><?php echo toDecimal($invoice['total_amount']-$invoice['transport']); ?></td>
             	</tr>
                <?php } ?>
			 	<tr height="40">
             		<td style="text-align:center;" colspan="9"><font size="+2">Total Payable Amount </font></td>
             		<td align="right" colspan="7"><font size="+2" style="margin-right:20px;"><?php echo "Rs. ".toDecimal($invoice['total_amount']); ?></font></td>
			 	</tr>
            </table>
            <table align="center" width="95%">
            	<?php
                	if($shop_details['account']!='' && $shop_details['ifsc']!=''){
				?>
            	<tr>
                	<th align="left">Bank Details</th>
                    <td></td>
                </tr>
            	<tr>
                	<td>
                    	Bank : <?php echo $shop_details['bank']; ?><br />
                    	Bank A/C : <?php echo $shop_details['account']; ?><br />
                        Bank IFSC : <?php echo $shop_details['ifsc']; ?>
					</td>
                    <td></td>
                </tr><?php } ?>
            	<tr>
                	<td rowspan="3">
                    	Terms &amp; Conditions:
                        <ol style="width:350px; font-weight:300; font-size:13px;">
                            <li>All Disputes are subject to Ranchi Jurisdiction only.</li>
                            <li>Goods once sold will not be exchanged or returned.</li>
                            <li>Damaged Goods to be returned within 30 Days.</li>
                            <li>Any kind of goods returned 10% will be deducted.</li>
                            <li>Our responsibility ceases once the consignment is dispatched from the shop.</li>
                        </ol>
                  	</td>
                    <td align="center" valign="top"><?php echo "For ".strtoupper($shop_details['name']); ?></td>
                </tr>
                <tr height="10">
                	<td align="center">Authorised Signature</td>
                </tr>
                <tr height="10">
                	<td align="center">Thank You!</td>
                </tr>
            </table>
         	<div id="buttons">
             	<center>
                  	<button type="button" class="btn btn-danger" onclick="window.print();" 
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
                 	<button type="button" onclick="closeThis('<?php echo $pre; ?>');" class="btn btn-default"
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button>
             	</center>
         	</div>
        </div>
        <script language="javascript">
        	function closeThis(str){
				var page=str;
				if(page=='report'){
					window.location="../reports?pagename=report";
				}	
				else{
					window.location="../invoice?pagename=invoice";	
				}
			}
        </script>
    </body>
</html>