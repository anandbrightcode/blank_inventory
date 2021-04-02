<?php
include_once "../action/class.php";
$obj=new database();
$link=$obj->getDBConnect();
$shop=$_GET['shop'];
$table="`sales`";
	$columns="sum(`amount`) as sum,sum(`taxable`) as taxable,(`cgst`+`sgst`+`igst`) as gst,`invoice_id`";
	$pagefilters="";
	$where="`shop`='$shop'";
	
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from'];$to=$_GET['to'];
		if($from!='' && $to!=''){
			$where.=" and invoice_id in(select id from invoice where  (date >= '$from' and date <= '$to') and shop='$shop')";
		}elseif($from=='' && $to!=''){
			$date=$to;
			$where.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')  ";
		}
		elseif($from!='' && $to==''){
			$date=$from;
			$where.=" and invoice_id in(select id from invoice where  date = '$date' and shop='$shop')";
		}
		$pagefilters.="&from=$from&to=$to";
	}
	//echo $where; 
	$order="id";
	$groupby="`invoice_id`,(`cgst`+`igst`+`sgst`)";
    $array=$obj->get_rows($table,$columns,$where,$order,'',$groupby);
$data=array();
/*
$year=2000;
while($year<=date('Y')){
	echo $year++;
}*/

if(is_array($array)){
	foreach($array as $sales){
		$getinv=$obj->get_details("`invoice`","*,concat(`prefix`,`invoice_no`) as `inv`","`id`='$sales[invoice_id]'");
		   $product=array();
		   $product[]=$getinv['gst'];
		   $product[]=$getinv['customer_name'];
		   $product[]=$getinv['inv'];
		   $product[]=date('d-m-Y',strtotime($getinv['date']));
		   $product[]=$sales['sum'];
		   $product[]=$getinv['code'].'-'.$getinv['state'];
		   $product[]=$getinv['reverse'];
		   $product[]=$getinv['billing_mode'];
		   $product[]=toDecimal($sales['gst']);
		   $product[]=toDecimal($sales['taxable']);
			$data[]=$product;
		}
}
$range="";
if(isset($_GET['from']) && isset($_GET['to']) && ($_GET['from'])!='' && ($_GET['to'])!=''){
	$range="-".date('d-m-Y',strtotime($from))."-".date('d-m-Y',strtotime($to));
}

$filename="GST-Report".$range;
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
$fieldinfo=array("GSTIN","Receiver Name","Invoice No.","Invoice Date","Invoice Value","Place of Supplier","Reverse Charge","Invoice Type","Rate","Taxable Value");
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