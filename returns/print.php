<?php
session_start();
error_reporting(0);
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
	$obj=new database();
	$words=new notowords;
	$invoice=$obj->get_details("`invoice`","*","`id`='$inv_id' and `shop`='$shop'");
	$returns=$obj->get_rows("`returns`","*","`invoice_id`='$inv_id' and `shop`='$shop'");
	$shop_details=$obj->get_details("`shop`","*","`id`='$shop'");
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
            </table>
            <table align="center" width="95%" height="800" border="1" cellpadding="0" cellspacing="0" id="table" >
            	<tr height="30">
                	<th align="center" width="5%">Sl.No.</th>
                	<th align="center">Description</th>
                	<th align="center">HSN</th>
                	<th align="center">Price</th>
                	<th align="center">Quantity</th>
                	<th align="center">Amount</th>
                </tr>
                <?php
					if(is_array($returns)){
						$i=0;$quantity=0; $amount=0; 
						foreach($returns as $product){
							$amt=$product['quantity']*$product['price'];
				?>
                <tr height="30">
                	<td align="center"><?php echo ++$i; ?></td>
                	<td style="padding:0px 5px;"><?php echo $product['category']."-".$product['company']."<br />".$product['model']; ?> </td>
                	<td align="center"><?php echo $product['hsn']; ?></td>
                	<td align="center"><?php echo toDecimal($product['price']); ?></td>
                	<td align="center"><?php echo $product['quantity']; ?></td>
                	<td align="center"><?php echo toDecimal($amt); ?></td>
                </tr>
                <?php	
							$quantity+=$product['quantity'];
							$amount+=$amt;
						}
					}
				?>
                <tr id="blank">
                	<td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr height="30">
                	<td></td>
                    <th align="center">Total</th>
                    <td></td><td></td><td align="center"><?php echo $quantity; ?>
                    <th align="center"><?php echo toDecimal($amount); ?></th>
                </tr>
             	<!--<tr height="30">
                	<td colspan="<?php if($checkigst['gst']!=0){echo 7;}else {echo 10;} ?>" style="padding:20px;" 
                    		rowspan="<?php if($checkigst['gst']==0){echo 0;}elseif($checkigst['ivalue']==0) {echo 4;}else{echo "3";} ?>">
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
                <?php } ?>-->
			 	<tr height="40">
             		<td style="text-align:center;" colspan="3"><font size="+2">Total  Returned Amount </font></td>
             		<td align="right" colspan="3"><font size="+2" style="margin-right:20px;"><?php echo "Rs. ".toDecimal($amount); ?></font></td>
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
                	<td rowspan="3" width="50%">
                    <br /><br />
                  	</td>
                    <td align="center" valign="top"><?php echo "For ".strtoupper($shop_details['name']); ?><br /><br /></td>
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
					window.location="../reports/defective.php?pagename=report";
				}	
				else{
					window.location="../returns/?pagename=returns";	
				}
			}
        </script>
    </body>
</html>