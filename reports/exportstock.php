<?php
include_once "../action/class.php";
$obj=new database();
$link=$obj->getDBConnect();
$shop=$_GET['shop'];
$table="`stock`";
$columns="*";
$where="`shop`='$shop'";
$array=$obj->get_rows($table,$columns,$where);
$data=array();
/*
$year=2000;
while($year<=date('Y')){
	echo $year++;
}*/
if(is_array($array)){$i=0;
		foreach($array as $stock){$i++;
			$getcategory=$obj->get_details("`category`","`name`","`id`='$stock[category]'");
			$getcompany=$obj->get_details("`company`","`name`","`id`='$stock[company_id]'");
			$where2="`category`='$stock[category]' and `company_id`='$stock[company_id]' and `model`='$stock[model]' and `shop`='$shop'";
			$pwhere=$swhere='';$pcarry=$scarry=0;
			if(isset($_GET['from']) && isset($_GET['to']) && ($_GET['from'])!='' && ($_GET['to'])!=''){
				$from=$_GET['from']; $to=$_GET['to'];
				$pwhere=" and `purchase_id` in (SELECT id from purchase where (`date` between '$from' and '$to') and `shop`='$shop')";
				$swhere=" and `invoice_id` in (SELECT id from invoice where (`date` between '$from' and '$to') and `shop`='$shop')";
				$getpcarry=$obj->get_details("`purchase_order`","sum(`quantity`) as `total`","$where2 and `purchase_id` in (SELECT id from purchase where `date`<'$from' and `shop`='$shop')");
				$getscarry=$obj->get_details("`sales`","sum(`quantity`) as `total`","$where2 and `invoice_id` in (SELECT id from invoice where `date`<'$from' and `shop`='$shop')");
				$pcarry=$getpcarry['total'];
				if($pcarry==''){$pcarry=0;}
				$scarry=$getscarry['total'];
				if($scarry==''){$scarry=0;}
			}
			$getpurchase=$obj->get_details("`purchase_order`","sum(`quantity`) as `total`",$where2.$pwhere);
			$getsale=$obj->get_details("`sales`","sum(`quantity`) as `total`",$where2.$swhere);
			$purchase=$getpurchase['total'];
			if($purchase==''){$purchase=0;}
			$sale=$getsale['total'];
			if($sale==''){$sale=0;}
			$carry=$pcarry-$scarry;
			$remaining=$purchase+$carry-$sale;
			$product=array();
			$product[]=$i;
			$product[]=$getcategory['name']."-".$getcompany['name']."-".$stock['model'];
			$product[]=$carry;
			$product[]=$purchase;
			$product[]=$sale;
			$product[]=$remaining;
			$data[]=$product;
		}
}
$range="";
if(isset($_GET['from']) && isset($_GET['to']) && ($_GET['from'])!='' && ($_GET['to'])!=''){
	$range="-".date('d-m-Y',strtotime($from))."-".date('d-m-Y',strtotime($to));
}

$filename="Stock-Report".$range;
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
//$fieldinfo=mysqli_fetch_fields($result);
$fieldinfo=array("Sl no.","Product","Carry Forward","Purchase","Sale","Remaining");
  foreach ($fieldinfo as $val)
    {
    printf($val . "\t");
    }
//for ($i = 0; $i < mysqli_num_fields($result); $i++) {
//echo mysqli_fetch_fields($result,$i) . "\t";
//}
print("\n");    
//end of printing column names  
//start while loop to get data
    foreach($data as $row)
    {
        $schema_insert = "";
        for($j=0; $j<sizeof($fieldinfo);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            else//if ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
           // else
                //$schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }   


?>