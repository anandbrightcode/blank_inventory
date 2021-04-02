<?php
include_once "../action/class.php";
$obj=new database();
$link=$obj->getDBConnect();
$query="SELECT t3.name as `Category`,t2.name as `Company`, t1.model as `Model`, t1.hsn as `HSN/SAC`, t1.quantity as `Quantity`, t1.purchase as `Purchase`,  t1.base_price as `Base Price`, t1.selling_price as `Selling Price` from stock t1, company t2, category t3 where t1.company_id=t2.id and t1.category=t3.id";
$result=@mysqli_query($link,$query) or die("Couldn't execute query:<br>" . mysqli_error($link));  
$filename="Stock-Report-".date('dmY');
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
print "Sl. No.\t";
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
	$i=1;
    while($row = mysqli_fetch_row($result))
    {
		print $i."\t";$i++;
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