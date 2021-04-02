<?php
session_start();
	include('../action/class.php');
	if(isset($_SESSION['shop'])){
	  $shop=$_GET['shop'];
  	}
  
	$obj=new database();
	$table="`stock`";
	$columns="*";
	$where="1";
	if(isset($_GET['company']) && trim($_GET['company'])!=""){
	 $comp=$_GET['company'];
		$where.=" and `company_id`='$comp'";
		$pagefilters="&comp=$comp";
	}
	if(isset($_GET['category']) && trim($_GET['category'])!=""){
		$cat=$_GET['category'];
		$where.=" and `category`='$cat'";
		$pagefilters.="&cat=$cat";
	}
	if(isset($_GET['model']) && trim($_GET['model'])!=""){
		$model=$_GET['model'];
		$where.=" and `model`='$model'";
		$pagefilters.="&model=$model";
	}
	
	$result=$obj->get_details("`purchase_order`","sum(purchase_gst) as cp",$where);
	$result2=$obj->get_details("`sales`","sum(amount) as sp",$where);
	$array=$obj->get_rows($table,$columns,$where);
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
                     <font size="+1" style="border:1px solid #000000; padding:5px;">STOCK REPORT</font><br /><br>
                      <font size="+3" style="letter-spacing:2px"><?php echo strtoupper($shop_details['name']); ?><br /></font>
                      <font size="+1"><?php echo $shop_details['address'];  ?><br />
                      <?php  if($shop_details['address2']!=''){echo $shop_details['address2'].", " ;} echo $shop_details['district']; ?><br />
                      Ph : <?php echo $shop_details['phone']; ?><br />
                      <?php if($shop_details['gst']!=''){echo "GSTIN - ".$shop_details['gst']; }?>
                      </font><br />
               </center>
              <br />
      <div style="float:left; margin-right:20px;">
    <h4>Total Purchase Amount:<?php if($result['cp']!=""){echo toDecimal($result['cp']);}else{ echo "0";};?></h4></div>
  <div style="float:left; position:relative; margin-right:20px;"><h4>Total Sale Amount:<?php if($result2['sp']!=""){echo toDecimal($result2['sp']);} else{ echo "0";}?></h4></div>
   <div style="float:left; position:relative; margin-right:20px;"><h4><?php if($result['cp']>$result2['sp']){ echo "Loss:".toDecimal($result['cp']-$result2['sp']);}else{ echo "Profit:".toDecimal($result2['sp']-$result['cp']);}?> </h4>
    
</div>
  <table border="1" cellspacing="0" cellpadding="0" id="c_list" style="width:100%" >
              

    <thead>
   
    	<tr>
            <th style="text-align:center">Sl No</th>
            <th style="text-align:center">Category</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Model</th>
            <th style="text-align:center">Purchase</th>
            <th style="text-align:center">Sale</th>
            <th style="text-align:center">Remaining</th>
            <th style="text-align:center">Profit/Loss</th>
    	</tr>
    </thead>
    <?php
    	if(is_array($array)){$i=1;$i++;
			foreach($array as $stock){$i++;
				$getcategory=$obj->get_details("`category`","`name`","`id`='$stock[category]'");
				$getcompany=$obj->get_details("`company`","`name`","`id`='$stock[company_id]'");
				
			     $where1="`category`='$stock[category]' && `company_id`='$stock[company_id]' && `model`='$stock[model]'";
				 
				 $array2=$obj->get_details("`sales`","sum(`quantity`) as sell",$where1);
				 $array3=$obj->get_details("`purchase_order`","sum(`quantity`) as cost",$where1);
				 
				 $array4=$obj->get_details("`sales`","sum(`amount`) as amount",$where1);
				 $array5=$obj->get_details("`purchase_order`","sum(`purchase_gst`) as amount",$where1);
				 
	?>
    <tr>
    	<td align="center"><?php echo $i; ?></td>
    	<td align="center"><?php echo $getcategory['name']; ?></td>
    	<td align="center"><?php echo $getcompany['name']; ?></td>
    	<td align="center"><?php echo $stock['model']; ?></td>
       	<td align="center"><?php echo $array3['cost']; ?></td>
    	<td align="center"><?php if($array2['sell']==""){ echo "0";}else {echo $array2['sell'];} ?></td>
        <td align="center"><?php echo ($array3['cost']-$array2['sell']); ?></td>
        <td align="center"><?php if(($array5['amount']-$array4['amount'])>0){ echo "Loss:".toDecimal($array5['amount']-$array4['amount']);}else{ echo "Profit:".toDecimal($array4['amount']-$array5['amount']);}?></td>
    </tr>
    <?php
			}	
		}
		else{
	?>
    <tr>
    	<td align="center" class="text-danger" colspan="13">No Records Found!!</td>
    </tr>
    <?php
		}
		
	?>

   
</table>
            <br />  
         	<div id="buttons">
             	<center>
                  	<button type="button" class="btn btn-danger" onclick="window.print();" 
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
                 	<a href="balancesheet.php"><button type="button" onclick="closeThis('<?php echo $pre; ?>');" class="btn btn-default"
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button></a>
             	</center>
         	</div>
        </div>
        <script language="javascript">
        	
        </script>
    </body>
</html>