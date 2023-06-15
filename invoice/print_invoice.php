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
    $state=$obj->get_details("state","*","code='$shop_details[state]'");
	//echo $checkigst['gst'];
	$rspan="rowspan='";
	if($checkigst['gst']==0){$rspan="";}elseif($checkigst['ivalue']==0) {$rspan.="4'";}else{$rspan.="3'";}
    $hsns=$obj->get_rows("`sales`","hsn,round(sum(`taxable`),2) as `taxable`,round(sum(`svalue`+`cvalue`+`ivalue`),2) as `gst_value`,sum(`sgst`+`cgst`+`igst`) as `gst`,round(sum(`amount`),2) as `amount`",
                            "`invoice_id`='$inv_id' group by hsn");
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
				#buttons,
                .to-hide{
						display:none;
				}
				.invoice{
					margin-top:30px;
					margin-bottom:40px;
  				}
                .invoice-table{
                    height: 600px !important;
                }
			}
		</style>
        <style>
            table td,
            table th{
                padding: 5px;
                vertical-align: top;
            }
            .info-table table td{
                border-left: 0;
                border-right: 0;
                border-top:0;
            }
            .info-table table tr:last-child td{
                border-bottom:0;
            }
            .invoice-table{
                height: 600px !important;
            }
            .invoice-table td{
                border-top:0;
                border-bottom:0;
            }
        </style>
    </head>
    
    <body  onafterprint="setTimeout(myFunction, 1000)">
        <?php
            for($loop=0;$loop<1;$loop++){
                if($loop==0){
                    $text="(ORIGINAL FOR RECIPIENT)";
                }
                elseif($loop==1){
                    $text="(DUPLICATE FOR TRANSPORTER)";
                }
                else{
                    $text="(TRIPLICATE FOR SUPPLIER)";
                }
        ?>
        <div class="invoice" style="width:1000px;">
            <center><font size="+1">Tax Invoice</font></center>
            <div  id="text" style="font-style: italic; float: right; margin-top: -20px; margin-right: 35px;"><?php echo $text; ?></div>
            <table align="center" width="95%" height="400" border="1" cellpadding="2" cellspacing="0" class="info-table" >
                <tr>
                    <td rowspan="7" width="50%" style="padding: 0 ">
                        <table width="100%" height="100%" border="1" cellpadding="2" cellspacing="0">
                            <tr>
                                <td>
                                    <div style="position: relative; float: left; width: 30%; display:none;">
                                        <img src="../logo.png" alt="" width="65%" style="margin: 5px;">
                                    </div>
                                    <div style="position: relative; float: left; width: 100%">
                                        <font size="+1"><?php echo strtoupper($shop_details['name']); ?><br /></font>
                                        <?php echo $shop_details['address'];  ?><br />
                                        <?php  if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; ?><br />
                                        Ph : <?php echo $shop_details['phone']; ?><br />
                                        <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?><br />
                                        State : <?php echo $state['state'].", Code : ".$shop_details['state'] ?><br />
                                        Email : <?php echo $shop_details['email']; ?><br />
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Consignee<br />
                                    <font size="+1"><?php echo $invoice['consignee_name']; ?><br /></font>
                                    <?php echo $invoice['consignee_address']; ?><br />
                                    Ph : <?php echo $invoice['consignee_mobile']; ?><br />
                                    <?php if($invoice['consignee_gst']!=''){echo "GSTIN - ".$invoice['consignee_gst']; }?><br />
                                    State : <?php echo $invoice['consignee_state'].", Code : ".$invoice['consignee_code'] ?><br />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Buyer(If Other Than Consignee)<br />
                                    <font size="+1"><?php echo $invoice['customer_name']; ?><br /></font>
                                    <?php echo $invoice['add_to']; ?><br />
                                    Ph : <?php echo $invoice['customer_mobile']; ?><br />
                                    <?php if($invoice['gst']!=''){echo "GSTIN - ".$invoice['gst']; }?><br />
                                    State : <?php echo $invoice['state'].", Code : ".$invoice['code'] ?><br />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="25%">Invoice No<br /><?php echo $invoice['prefix'].$invoice['invoice_no']; ?></td>
                    <td>Dated<br /><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
                </tr>
                <tr height="50">
                    <td>Delivery Note</td>
                    <td>Mode/Terms of Payment</td>
                </tr>
                <tr height="50">
                    <td>Supplier's Ref</td>
                    <td>Other Reference(s)</td>
                </tr>
                <tr height="50">
                    <td>Buyer's Order No.</td>
                    <td>Dated</td>
                </tr>
                <tr height="50">
                    <td>Despatch Document No.</td>
                    <td>Delivery Note Date</td>
                </tr>
                <tr height="50">
                    <td>Despatched through</td>
                    <td>Destination</td>
                </tr>
                <tr>
                    <td colspan="2">Terms of Delivery<br />
                    Order Reference: <?php echo $invoice['po'];?>
                        <ol style="width:350px; font-weight:300; font-size:13px; display: none;">
                            <li>All Disputes are subject to Ranchi Jurisdiction only.</li>
                            <li>Goods once sold will not be exchanged or returned.</li>
                            <li>Damaged Goods to be returned within 30 Days.</li>
                            <li>Any kind of goods returned 10% will be deducted.</li>
                            <li>Our responsibility ceases once the consignment is dispatched from the shop.</li>
                        </ol>
                    </td>
                </tr>
            </table>
            <table align="center" width="95%" height="600" border="1" cellpadding="0" cellspacing="0" class="invoice-table" >
            	<tr height="20">
                	<th align="center" width="5%" >S.No.</th>
                	<th align="center" width="35%">Description of Goods</th>
                	<th align="center" width="10%">HSN/SAC</th>
                	<th align="center" width="10%">Quantity</th>
                	<th align="center" width="12%">Rate</th>
                	<th align="center" width="12%">Per</th>
                    <th align="center" width="10%">Disc.(%)</th>
                	<th align="center" width="15%">Total</th>
                </tr>
                <?php
					if(is_array($sales)){
						$i=0;$quantity=0; $amount=0; $discount=0; $taxable=0;
						$cgst=0; $sgst=0; $igst=0; $charity=$gst_value=0;
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
                	<td align="center"><?php echo $product['quantity']; ?></td>
                	<td align="center"><?php echo toDecimal($product['price']); ?></td>
                	<td align="center"><?php echo $product['uom']; ?></td>
                    <td align="center"><?php echo $product['discount']; ?></td>
                	<?php /*?><td align="center"><?php echo $product['discount']; ?></td>
                    <?php 
						if($gst){
					?>
                	<td align="center"><?php echo $product['gst_rate']."%"; ?></td>
                    <?php 
						}
					?><?php */?>
                	<td align="center"><?php echo toDecimal($product['quantity']*$product['price']); ?></td>
                </tr>
                <?php	
							$quantity+=$product['quantity'];
							$discount+=($product['quantity']*$product['price'])-$product['taxable'];
							$taxable+=$product['taxable'];
							$gst_value+=$product['cvalue']+$product['svalue']+$product['ivalue'];
						}
					}
				?>
                <tr id="blank">
                	<td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td>
                </tr>
                <?php 
					if($checkigst['gst']!=0){
				        if($checkigst['ivalue']==0){ 
						
				?>
                <tr height="20">
                	<td></td>
                    <td align="center">CGST</td><?php /*?><td></td>
					<?php  if($gst){ echo "<td></td>";}?><?php */?>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($gst_value/2); ?></td>
                </tr>
                <tr height="20">
                	<td></td>
                    <td align="center">SGST</td><?php /*?><td></td>
					<?php  if($gst){ echo "<td></td>";}?><?php */?>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($gst_value/2); ?></td>
                </tr>
                <?php 
						}
                        else{ 
						
				?>
                <tr height="20">
                	<td></td>
                    <td align="center">IGST</td><?php /*?><td></td>
					<?php  if($gst){ echo "<td></td>";}?><?php */?>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($gst_value); ?></td>
                </tr>
                <?php 
						}
					} 
					if($invoice['roundoff']!=0){ 
				?>
                <tr height="20">
                	<td></td>
                    <td align="center">Round Off</td><?php /*?><td></td>
					<?php  if($gst){ echo "<td></td>";}?><?php */?>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($invoice['roundoff']); ?></td>
                </tr>
                <?php } ?>
                <?php if($invoice['transport']!=0){ ?>
                <tr height="20">
                	<td></td>
                    <td align="center">Transport</td><?php /*?><td></td>
					<?php  if($gst){ echo "<td></td>";}?><?php */?>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo toDecimal($invoice['transport']); ?></td>
                </tr>
                <?php } ?>
                <tr height="20">
                	<th></th>
                    <th align="center">Total</th><th></th><th align="center"><?php echo $quantity; ?></th>
					<?php /*?><?php  if($gst){ echo "<th></th>";}?>
                    <th></th><?php */?><th></th><th></th><th></th>
                    <th align="center"><?php echo toDecimal($invoice['total_amount']); ?></th>
                </tr>
                <tr height="20">
             		<td colspan="8">
                        Amount Chargeable (in words) <div style="float: right">E. & O.E.</div><br />
                		<font size="+1">Indian Rupees. <?php echo $words->to_words($invoice['total_amount'])." Only";?></font> 
               		</td>
             	</tr>
            </table>
            <table align="center" width="95%" border="1" cellpadding="0" cellspacing="0" >
            <?php 
                if($checkigst['gst']!=0){
            ?>
                <tr>
                    <th align="center" rowspan="2">HSN/SAC</th>
                    <th align="center" rowspan="2">Taxable<br />Value</th>
                    <?php if($checkigst['ivalue']==0){ ?>
                    <th align="center" colspan="2">Central Tax</th>
                    <th align="center" colspan="2">State Tax</th>
                    <?php }else{ ?>
                    <th align="center" colspan="2">Integrated Tax</th>
                    <?php } ?>
                    <th align="center" rowspan="2">Total</th>
                </tr>
                <tr>
                    <?php if($checkigst['ivalue']==0){ ?>
                    <th align="center">Rate</th>
                    <th align="center">Amount</th>
                    <th align="center">Rate</th>
                    <th align="center">Amount</th>
                    <?php }else{ ?>
                    <th align="center">Rate</th>
                    <th align="center">Amount</th>
                    <?php } ?>
                </tr>
                <?php
                    $taxable=$gst_value=$total=0;
                    foreach($hsns as $hsn){
                ?>
                <tr>
                    <td align="center"><?php echo $hsn['hsn']; ?></td>
                    <td align="right"><?php echo toDecimal($hsn['taxable']); ?></td>
                    <?php if($checkigst['ivalue']==0){ ?>
                    <td align="right"><?php echo ($hsn['gst']/2).'%'; ?></td>
                    <td align="right"><?php echo toDecimal($hsn['gst_value']/2); ?></td>
                    <td align="right"><?php echo ($hsn['gst']/2).'%'; ?></td>
                    <td align="right"><?php echo toDecimal($hsn['gst_value']/2); ?></td>
                    <?php }else{ ?>
                    <td align="right"><?php echo $hsn['gst'].'%'; ?></td>
                    <td align="right"><?php echo toDecimal($hsn['gst_value']); ?></td>
                    <?php } ?>
                    <td align="right"><?php echo toDecimal($hsn['gst_value']); ?></td>
                </tr>
                <?php
                        $taxable+=$hsn['taxable'];
                        $gst_value+=$hsn['gst_value'];
                        $amount+=$hsn['gst_value'];
                    }
                ?>
                <tr>
                    <th align="center">Total</th>
                    <th align="right"><?php echo toDecimal($taxable); ?></th>
                    <?php if($checkigst['ivalue']==0){ ?>
                    <th align="right"></th>
                    <th align="right"><?php echo toDecimal($gst_value/2); ?></th>
                    <th align="right"></th>
                    <th align="right"><?php echo toDecimal($gst_value/2); ?></th>
                    <?php }else{ ?>
                    <th align="right"></th>
                    <th align="right"><?php echo toDecimal($gst_value); ?></th>
                    <?php } ?>
                    <th align="right"><?php echo toDecimal($amount); ?></th>
                </tr>
            <?php } ?>
                <tr>
                    <td colspan="7" style="border-bottom: 0; padding:10px;">Tax Amount (in words) : Indian Rupees. <?php echo $words->to_words($amount); ?> Only</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0; border-left:1px solid; padding:0 10px; ">
                        Our Bank Details:<br>
                    	Bank Name : <?php echo $shop_details['bank']; ?><br />
                    	Bank A/C : <?php echo $shop_details['account']; ?><br />
                        Bank IFSC : <?php echo $shop_details['ifsc']; ?><br>
                        A/C Name : <?php echo $shop_details['name']; ?>
                    </td>
                    <td colspan="4" style="border: 0; border-right:1px solid;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0; border-left:1px solid; padding:0 10px; ">
                        Company PAN : <?php if($shop_details['gst']!=''){echo "GSTIN - ".substr($shop_details['gst'],2,10); } ?></td>
                    <td colspan="4" align="right" rowspan="2">
                        <strong>for <?php echo $shop_details['name']; ?></strong><br /><br /><br />
                        Authorised Signatory
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="50%" style=" text-align: justify; padding:0 10px; border: 0; border-left:1px solid;">
                        <h4 style="padding: 0; margin: 0;">Declaration: </h4>
                        <p style="padding: 0; margin: 0;">Certified that the particulars given above are true and correct and the amount indicated represents the price actually charged and that there is no flow of additional consideration directly or indirectly from the buyer</p>
                    </td>
                </tr>
            </table>
            <center>This is Computer Generated Invoice</center>
            <br /><hr class="to-hide" /><br />
            <input type="hidden" id="print_page" value="1">
        </div>
        <?php } ?>
        <div id="buttons" style="width:1000px;">
            <center>
                <button type="button" class="btn btn-danger" onclick="window.print();" 
                    style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
                <button type="button" onclick="closeThis('<?php echo $pre; ?>');" class="btn btn-default"
                    style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button>
            </center>
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
            function myFunction() {
                var page=document.getElementById("print_page").value;
                var text='';
                if(page==0){
                    text="(ORIGINAL FOR RECIPIENT)";
                    document.getElementById("print_page").value=1;
                    document.getElementById("text").innerHTML=text;
                }
                else if(page==1){
                    text="(DUPLICATE FOR TRANSPORTER)";
                    document.getElementById("print_page").value=2;
                    document.getElementById("text").innerHTML=text;
                    window.print();
                }
                else{
                    text="(TRIPLICATE FOR SUPPLIER)";
                    document.getElementById("print_page").value=0;
                    document.getElementById("text").innerHTML=text;
                    window.print();
                }
                
            }
        </script>
    </body>
</html>
