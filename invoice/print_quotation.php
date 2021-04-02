<?php
session_start();
//error_reporting(0);
	include('../action/class.php');
	if(isset($_SESSION['user'])){
	  	$role=$_SESSION['role'];
      	$user=$_SESSION['user'];
      	$shop=$_SESSION['shop'];
	  	$quot_id=$_GET['quot_id'];
		$pre='';
		if(isset($_GET['page']))
	  	$pre=$_GET['page'];
  	}
  	else{
	  	header("Location:index.php");
  	}
	//include('notowords.php');
	$obj=new database();
	$words=new notowords;
	$quotation=$obj->get_details("`quotation`","*","`id`='$quot_id' and `shop`='$shop'");
	$getcustomer=$obj->get_details("`customer`","*","`id`='$quotation[customer_id]'");
	$qlist=$obj->get_rows("`quot_list`","*","`quot_id`='$quot_id' and `shop`='$shop'");
	$getstate=$obj->get_details("`state`","*","`id`='$quotation[state]'");
	$shop_details=$obj->get_details("`shop`","*","`id`='$shop'");
	$checkigst=$obj->get_details("`services`","sum(`ivalue`) as `ivalue`","`quot_id`='$quot_id'");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Quotation</title>
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
                <font size="+1" style="border:1px solid #000000; padding:5px;">QUOTATION</font><br /><br>
                <font size="+3" style="letter-spacing:2px; font-weight:bold;"><?php echo strtoupper($shop_details['name']); ?><br /></font>
                <font size="+1"><?php echo $shop_details['address'];  ?><br />
                <?php  
					if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; 
					if($shop_details['pin']!=''){echo " - ".$shop_details['pin'] ;}
				?><br />
                Ph : <?php echo $shop_details['phone'];  
                if($shop_details['email']!=''){echo "<br>Email - ".$shop_details['email']; }?><br />
                Website : www.suntech.net.in<br>
                <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?><br />
                </font>
            </center>
            <hr style="border:1px solid #000000;" />
            <table align="center" width="95%">
            	<tr height="35">
                    <th align="left" width="15%">Name</th>
                    <td align="left" width="35%"><?php echo $quotation['customer_name']; ?></td>
                    <th align="left" width="18%">Date</th>
                    <td align="left" width="32%"><?php echo date('d-m-Y',strtotime($quotation['date'])); ?></td>
                </tr>
                <tr height="35">
                    <th align="left" valign="top">Address</th>
                    <td align="left" style="font-size:15px; padding-right:10px;" valign="top">
						<?php echo $quotation['add_to']."<br />State : ".strtoupper($getstate['state'])." State Code : ".$getstate['code'].""; ?>
                    </td>
                    <th align="left" valign="top">Our Reference</th>
                    <td align="left" valign="top"><?php echo $quotation['prefix'].$quotation['invoice_no']; ?></td>
                </tr>
                <tr height="35">
                    <th align="left">Phone</th>
                    <td align="left"><?php echo $quotation['customer_mobile']; ?></td>
                    <th align="left">GSTIN</th>
                    <td align="left"><?php echo $quotation['gst']; ?></td>
                </tr>
                <tr height="50">
                	<td colspan="4" style="border-top:1px solid #000000;">
                    	Dear Sir,
                        <br /> &nbsp; &nbsp; &nbsp; &nbsp; We thank you for your above enquiry and pleased to quote our rates below.
                    </td>
                </tr>
            </table>
            <table align="center" width="95%" height="950" border="1" cellpadding="0" cellspacing="0" id="table" >
            	<tr height="25">
                	<th align="center" width="5%">S.No.</th>
                	<th align="center">Product Description</th>
                	<th align="center">HSN</th>
                	<th align="center">Qty.</th>
                	<th align="center">Rate</th>
                	<th align="center">Disc</th>
                	<th align="center">Total</th>
                </tr>
                <?php
					if(is_array($qlist)){
						$i=0;$quantity=0; $amount=0; $discount=0; $taxable=0;
						$cgst=0; $sgst=0; $igst=0; $charity=$gst_value=0;
						foreach($qlist as $product){
							$comp=$obj->get_details("`company`","`name`","`id`='".$product['company_id']."'");
							
				?>
                <tr height="25">
                	<td align="center"><?php echo ++$i; ?></td>
                	<td style="padding:5px 20px;">
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
                	<td align="center"><?php echo toDecimal($product['discount']); ?></td>
                	<td align="center"><?php echo toDecimal($product['amount']); ?></td>
                </tr>
                <?php	
						}
					}
				?>
                <tr id="blank">
                	<td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td>
                </tr>
                <?php if($quotation['roundoff']!=0){ ?>
                <tr height="25">
                	<td></td>
                    <td align="center">Round Off</td>
                    <td></td><td></td><td></td><td></td>
                    <td align="center"><?php echo twoDigits($quotation['roundoff']); ?></td>
                </tr>
                <?php } ?>
                <tr height="30">
                	<td colspan="6" style="padding:5px 20px; font-size:18px;"><?php echo "Rs. ".$words->to_words($quotation['total_amount'])." Only"; ?></td>
                    <th align="center" style="padding:5px; font-size:18px;"><?php echo toDecimal($quotation['total_amount']); ?></th>
                </tr>
                <?php if($quotation['add_terms']!=''){ ?>
                <tr height="30">
                	<td colspan="7" style="padding:0 20px;">
						<?php 
							$add_terms=explode("\n",$quotation['add_terms']);
							echo "<ol style='padding:5px; margin:0;'>";
							foreach($add_terms as $add_term){
								echo "<li>$add_term</li>";
							}
							echo "</ol>";
						?>
                    </td>
                </tr>
                <?php } ?>
                <tr height="50">
                	<td colspan="2" style="padding:5px 20px;">
                    	Terms &amp; Conditions:
                        <table style="width:95%; font-weight:300; font-size:13px; padding-left:5px;">
                            <?php
                           		$terms=explode("\n",$quotation['terms']);
								if(is_array($terms) && !empty($terms)){$t=0;
									foreach($terms as $term){$t++;
										if($term==''){continue;}
										echo "<tr>";
										$tarr=explode(':-',$term); 
										if(sizeof($tarr)>1){
											echo "<td width='3%' valign='top'>$t.</td>";
											echo "<th align='left' valign='top' width='20%'>$tarr[0] :- </th>";
											echo "<td valign='top'>$tarr[1]</td>";
										}
										else{
											echo "<td valign='top'>$t.</td>";
											echo "<td colspan='2' valign='top'>$term</td>";
										}
										echo "</tr>";
									}
								}
						   	?>
                        </table>
                    </td>
                	<!--<td width="55%" colspan="2" style="padding:5px 20px;">
                    	Our Bank Details:<br>
                    	Bank Name : <?php echo $shop_details['bank']; ?><br />
                    	Bank A/C : <?php echo $shop_details['account']; ?><br />
                        Bank IFSC : <?php echo $shop_details['ifsc']; ?><br>
                        A/C Name : <?php echo $shop_details['name']; ?>
                    </td>-->
                	<td width="45%" colspan="5" valign="top" align="right" style="padding:5px 20px;">
                        E. &amp; O.E<br><br>
                    	<div style="width:60%; text-align:left;">Thanking You <br><br><br><br>
                    	For <b><?php echo strtoupper($shop_details['name']); ?></b>
                        </div>
                        
                 	</td>
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
					window.location="../reports/quotation.php?pagename=report";
				}	
				else{
					window.location="../invoice/quotation.php?pagename=quotation";	
				}
			}
        </script>
    </body>
</html>