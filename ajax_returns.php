<?php
session_start();
error_reporting(0);
include('action/class.php');
$obj=new database();
if(isset($_POST['getModel']) && $_POST['page']=='balancesheet')
{
    $company=$_POST['comp'];
    $category=$_POST['cat'];
	$columns="*";
	$table="`models`";
	$where="`category`='$category' && `company_id`='$company'";
	$array=$obj->get_rows($table,"*",$where);
	$select="<option value=''>Select Model</option>";
	if(is_array($array))
	{
	
	foreach($array as $val)
	{
		
		$select.="<option value='".$val['model']."'>".$val['model']."</option>";
	}
	
	}
	echo $select;
}

elseif(isset($_POST['getCategory']) && $_POST['page']=='balancesheet')
{
    $company=$_POST['company'];
   $where1="`id`='$company'";
   $array=$obj->get_details("`company`","`category`",$where1);
    $cat=$array['category'];
   $table="`category`";
   $columns="*";
   $where2=" id in($cat)";
   $arr2=$obj->get_rows($table,$columns,$where2);
    $select="<option value=''>Select Category</option>";
   if(is_array($arr2))
   {
  
	foreach($arr2 as $val)
	
	{
		
		$select.="<option value='".$val['id']."'>".$val['name']."</option>";
	}
	
   }
	echo $select;
   
}
elseif(isset($_POST['editUser'])){					//ADMIN PAGE
	$id=$_POST['id'];
	$array=$obj->get_details("`users`","*","`id`='$id'");
	echo json_encode($array);
	
}												//ADMIN PAGE

elseif(isset($_POST['get_category']) && $_POST['page']=='master'){					//MASTER PAGE
	$id=$_POST['id'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`category`","*","`id`='$id' and `shop`='$shop'");
	echo json_encode($array);
}
elseif(isset($_POST['get_CompDetails']) && $_POST['page']=='master'){
	$id=$_POST['id'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`company`","*","`id`='$id' and `shop`='$shop'");
	$category['category']=explode(',',$array['category']);
	$array=array_merge($array,$category);
	echo json_encode($array);
}
elseif(isset($_POST['get_company']) && $_POST['page']=='master'){
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`company`","`id`,`name`,`category`","`category` like '%$category%' and `shop`='$shop'");
	$select="<option value=''>Select Company</option>";
	foreach($rs as $array){
		$arr=explode(',',$array['category']);
		if(array_search($category,$arr)===false){continue;}
		$select.="<option value='".$array['id']."'>".$array['name']."</option>";
	}
	echo $select;
}
elseif(isset($_POST['get_modelDetails']) && $_POST['page']=='master'){
	$id=$_POST['id'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`models`","*","`id`='$id' and `shop`='$shop'");
	echo json_encode($array);
}																					//MASTER PAGE

elseif(isset($_POST['get_company']) && $_POST['page']=='purchase'){					//PURCHASE PAGE
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`company`","`id`,`name`,`category`","`category` like '%$category%' and `shop`='$shop'");
	$select="<option value=''>Select Company</option>";
	foreach($rs as $array){
		$arr=explode(',',$array['category']);
		if(array_search($category,$arr)===false){continue;}
		$select.="<option value='".$array['id']."'>".$array['name']."</option>";
	}
	echo $select;
}
elseif(isset($_POST['get_model']) && $_POST['page']=='purchase'){
	$company_id=$_POST['company_id'];
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`models`","`model`","`company_id`='$company_id' and `category`='$category' and `shop`='$shop'");
	$select="<option value=''>Select Model</option>";
	foreach($rs as $array){
		$select.="<option>".$array['model']."</option>";
	}
	echo $select;
}																					//PURCHASE PAGE

elseif(isset($_POST['get_name'])){													//INVOICE PAGE
	$mobile=$_POST['mobile'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`customer`","`id`,`name`,`address`,`gst`","`phone`='$mobile'");
	echo json_encode($array);
}
elseif(isset($_POST['get_customer'])) {
	$result=$obj->get_rows("`customer`","*","`name` like '" . $_POST["keyword"] . "%'","`name`");
	
	$count=sizeof($result);
	if(!empty($result)) {
?>
	<div class="btn-group-vertical" style="width:100%; border-left:1px solid #7AC7E4; border-right:1px solid #7AC7E4; border-bottom:1px solid #7AC7E4; border-radius:5px; 
				<?php if($count>3)echo "overflow:hidden;  height:200px;" ?>">
    <?php
		$i=0;
		foreach($result as $customer) {$i++;
?>
		<button type="button" id="list<?php echo $i; ?>" class="btn btn-default btns" onClick="selectCustomer('<?php echo $customer["id"]; ?>');" style="width:100%;"><?php echo $customer["name"]; ?></button>
		
<?php 
		} 
?>
    	
  	</div>
<?php 
	} 
}
elseif(isset($_POST['getCustomer'])){
	$id=$_POST['id'];
	$array=$obj->get_details("`customer`","*","`id`='$id'");
	echo json_encode($array);
}
elseif(isset($_POST['get_company']) && $_POST['page']=='invoice'){
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`company`","`id`,`name`","`shop`='$shop' and `id` in (select `company_id` from `stock` where `shop`='$shop' and `category`='$category')");
	$select="<option value=''>Select Company</option>";
	foreach($rs as $array){
		$select.="<option value='".$array['id']."'>".$array['name']."</option>";
	}
	echo $select;
}
elseif(isset($_POST['get_model']) && $_POST['page']=='invoice'){
	$company_id=$_POST['company_id'];
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`stock`","`model`","`company_id`='$company_id' and `category`='$category' and `shop`='$shop'");
	$select="<option value=''>Select Model</option>";
	foreach($rs as $array){
		$select.="<option>".$array['model']."</option>";
	}
	echo $select;
}
elseif(isset($_POST['select_model']) && $_POST['page']=='invoice'){
	$company_id=$_POST['company_id'];
	$category=$_POST['category'];
	$model=$_POST['model'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`stock`","*","`company_id`='$company_id' and `category`='$category' and `shop`='$shop' and `model`='$model'");
	echo json_encode($array);
}
elseif(isset($_POST['getDues'])){
	$invoice_no=$_POST['query'];
	$prefix=$_POST['prefix'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`invoice`","`id`,`customer_id`,`total_amount`,`dues`,`paid`,`next_payment`",
							"`prefix`='$prefix' and `invoice_no`='$invoice_no' and `shop` = '$shop' and dues!=0");
	echo json_encode($array);
}																					//INVOICE PAGE

elseif(isset($_POST['getReturns'])){	
	$invoice_no=$_POST['invoice_no'];												
	$prefix=$_POST['prefix'];
	$shop=$_POST['shop'];
	$array=$obj->get_details("`invoice`","`id`,`customer_id`,`customer_name`,`customer_mobile`,`add_to`","`invoice_no`='$invoice_no' and `prefix`='$prefix' and `shop` = '$shop'");
	$id=$array['id'];
	$array2=$obj->get_rows("`sales` t1,`category` t2,`company` t3","t1.*,t2.`name` as `category`,t3.`name` as `company`",
							"t1.`invoice_id`='$id' and t1.`shop` = '$shop' and t1.`category`=t2.`id` and t1.`company_id`=t3.`id`");
	$array=array_merge($array,$array2);
	echo json_encode($array);
}																					//RETURNS PAGE	

elseif(isset($_POST['get_company']) && $_POST['page']=='stock'){						//STOCK PAGE
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`company`","`id`,`name`,`category`","`category` like '%$category%' and `shop`='$shop'");
	$select="<option value=''>Select Company</option>";
	foreach($rs as $array){
		$arr=explode(',',$array['category']);
		if(array_search($category,$arr)===false){continue;}
		$select.="<option value='".$array['id']."'>".$array['name']."</option>";
	}
	echo $select;
}
elseif(isset($_POST['get_model']) && $_POST['page']=='stock'){
	$company_id=$_POST['company_id'];
	$category=$_POST['category'];
	$shop=$_POST['shop'];
	$rs=$obj->get_rows("`models`","`model`","`company_id`='$company_id' and `category`='$category' and `shop`='$shop'");
	$select="<option value=''>Select Model</option>";
	foreach($rs as $array){
		$select.="<option>".$array['model']."</option>";
	}
	echo $select;
}
?>