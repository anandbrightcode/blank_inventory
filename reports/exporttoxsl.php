<?php
include_once "../action/class.php";
$obj=new database();
$link=$obj->getDBConnect();
$from=$_GET['from'];
$to=$_GET['to'];
$shop=$_GET['shop'];
    $query="SELECT concat(t1.prefix,t1.invoice_no) as `Invoice No`,DATE_FORMAT(t1.date, '%d/%m/%Y')  as `Date`, t1.billing_mode as `Billing Mode`,t1.payment_mode as `Payment Mode`, 
  			t1.customer_name as `Customer Name`, t1.customer_mobile as `Customer Mobile`,t1.gst as `GSTIN`, t1.add_to as `Address`, round(sum(t2.taxable),2) as `Taxable Value`, 
			round(sum(t2.cvalue),2) as `CGST`, round(sum(t2.svalue),2) as `SGST`, round(sum(t2.ivalue),2) as `IGST`,  t1.transport as `Transport`, t1.roundoff as `Round Off`,  
			t1.total_amount as `Total Amount`, t1.paid as `Paid  Amount`, t1.dues as `Dues Amount`, DATE_FORMAT(t1.next_payment, '%d/%m/%Y') as `Next Payment` 
			FROM `invoice` t1, `sales` t2 WHERE (t1.date >= '$from' and t1.date <= '$to') and t1.shop='$shop' and t1.id=t2.invoice_id group by t2.invoice_id";
    
   

$result=@mysqli_query($link,$query) or die("Couldn't execute query:<br>" . mysqli_error($link));  

$filename="Invoice-Report-".date('d-m-Y');
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