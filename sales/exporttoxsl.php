<?php
include_once "../action/class.php";
$obj=new database();
$link=$obj->getDBConnect();
$from=$_GET['from'];
$to=$_GET['to'];
$shop=$_GET['shop'];
$type="";
if($_GET['type']!=''){
	$type=" and t4.`billing_mode`='$_GET[type]'";;
}
     $query="SELECT concat(t4.`prefix`,t4.`invoice_no`) as `Invoice No`,DATE_FORMAT(t4.date, '%d/%m/%Y') as `Date`, concat(t2.`name`,' ',t3.`name`,' ',t1.`model`) as `Product`, 
	 		t1.`hsn` as `HSN`, t1.`quantity` as `Quantity`, t1.`price` as `Rate`, t1.`discount` as `Discount`,
			round(t1.custdiscount*((t1.price*t1.quantity-t1.discount)/100),2) as `Customer Discount`, t1.`taxable` as `Taxable`,
			t1.`svalue` as `SGST`, t1.`cvalue` as `CGST`, t1.`ivalue` as `IGST`, t1.`amount` as `Amount`
			from `sales` t1, `category` t2,`company` t3, `invoice` t4 
			where t1.`shop`='1' and t1.`category`=t2.`id` and t1.`company_id`=t3.`id` and t1.`invoice_id`=t4.`id` $type order by t1.`id` ";
			
$result=@mysqli_query($link,$query) or die("Couldn't execute query:<br>" . mysqli_error($link));  

$filename="Sales-Report-".date('d-m-Y');
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
$fieldinfo=mysqli_fetch_fields($result);

  foreach ($fieldinfo as $val)
    {
    printf($val->name . "\t");
    }
//for ($i = 0; $i < mysqli_num_fields($result); $i++) {
//echo mysqli_fetch_fields($result,$i) . "\t";
//}
print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysqli_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysqli_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }   


?>