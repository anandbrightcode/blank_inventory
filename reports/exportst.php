<?php

include_once "../action/class.php";
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
	//print_r($array);
     $data=array();
/*
$year=2000;
while($year<=date('Y')){
	echo $year++;
}*/
if(is_array($array)){$i=0;
		foreach($array as $stock){
		
				$getcategory=$obj->get_details("`category`","`name`","`id`='$stock[category]'");
				$getcompany=$obj->get_details("`company`","`name`","`id`='$stock[company_id]'");
				
			     $where1="`category`='$stock[category]' && `company_id`='$stock[company_id]' && `model`='$stock[model]'";
				 
				 $array2=$obj->get_details("`sales`","sum(`quantity`) as sell",$where1);
				
				 $array3=$obj->get_details("`purchase_order`","sum(`quantity`) as cost",$where1);
			
				 $array4=$obj->get_details("`sales`","sum(`amount`) as amount",$where1);
				 	
				 $array5=$obj->get_details("`purchase_order`","sum(`purchase_gst`) as amount",$where1);
				  
			$single=array();
			
			$single[]=$getcategory['name'];$single[]=$getcompany['name'];$single[]=$stock['model'];$single[]=$array3['cost'];$single[]=$array2['sell'];$single[]=$array3['cost']-$array2['sell'];
			if(($array5['amount']-$array4['amount'])>0)
			{
			  $single[]="Loss".toDecimal($array5['amount']-$array4['amount']);	
			}
			else
			{
			  $single[]="Profit".toDecimal($array4['amount']-$array5['amount']);	
			}
			$data[]=$single;
			//print_r($single);
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
$fieldinfo=array("Category", "Company", "Model", "Purchase", "Sale", "Remaining","Profit/Loss");;
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