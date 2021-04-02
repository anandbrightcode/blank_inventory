<?php
	session_start();
	include('class.php');
	$updateObj=new database();
	$obj=new database();
	if(isset($_POST['update_cust'])){
		$name=$_POST['name'];
		$phone=$_POST['phone'];
		$gst=$_POST['gst'];
		$address=$_POST['address'];
		$email=$_POST['email'];
		$shop=$_POST['shop'];
		$type=$_POST['type'];
		$advance=$_POST['advance'];
		if($type=='dues'){$advance=0-$advance;}
		$id=$_POST['id'];
		$table="`customer`";
		if($phone!=''){
			$count1=$updateObj->get_count($table,"`phone`='$phone' and `id`!='$id'");
		}else{ $count1=0; }
		if($gst!=''){
			$count2=$updateObj->get_count($table,"`gst`='$gst' and `id`!='$id'");
		}else{ $count2=0; }
		if($count1==0 && $count2==0){
			$col_values="`name`='$name', `phone`='$phone', `gst`='$gst', `address`='$address', `advance`='$advance', `email`='$email'";
			$where="`id`='$id'";
			$run=$updateObj->update($table,$col_values,$where);
		}
		elseif($count1!=0 && $count2==0){
			$run="Phone No Already Registered!";
		}
		elseif($count1==0 && $count2!=0){
			$run="GSTIN Already Registered!";
		}
		else{
			$run="Phone No and GSTIN Already Registered!";
		}
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../customer?pagename=customer");	
		
	}
	elseif(isset($_POST['update_supplier'])){
		$name=$_POST['name'];
		$phone=$_POST['phone'];
		$gst=$_POST['gst'];
		$address=$_POST['address'];
		$email=$_POST['email'];
		$state=$_POST['state'];
		$shop=$_POST['shop'];
		$type=$_POST['type'];
		$advance=$_POST['advance'];
		if($type=='dues'){$advance=0-$advance;}
		$id=$_POST['id'];
		$table="`supplier`";
		if($phone!=''){
			$count1=$updateObj->get_count($table,"`phone`='$phone' and `id`!='$id'");
		}else{ $count1=0; }
		if($gst!=''){
			$count2=$updateObj->get_count($table,"`gst`='$gst' and `id`!='$id'");
		}else{ $count2=0; }
		if($count1==0 && $count2==0){
			$col_values="`name`='$name', `phone`='$phone', `gst`='$gst', `address`='$address', `advance`='$advance', `email`='$email', `state`='$state'";
			$where="`id`='$id'";
			$run=$updateObj->update($table,$col_values,$where);
		}
		elseif($count1!=0 && $count2==0){
			$run="Phone No Already Registered!";
		}
		elseif($count1==0 && $count2!=0){
			$run="GSTIN Already Registered!";
		}
		else{
			$run="Phone No and GSTIN Already Registered!";
		}
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../supplier?pagename=supplier");	
		
		
	}
	elseif(isset($_POST['up_user'])){
		$id=$_POST['uid'];
		$username=$_POST['up_username'];
		$password=$_POST['up_password'];
		$role=$_POST['up_role'];
		$shop=$_POST['up_shop'];
		$active=$_POST['up_active'];
		$table="`users`";
		$col_values="`username`='$username', `role`='$role', `shop`='$shop', `active`='$active'";
		if($password!=''){$col_values.=",`password`='$password'";}
		$where="`id`='$id'";
		$run=$updateObj->update($table,$col_values,$where);
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../admin?pagename=admin");	
		
		
	}
	elseif(isset($_POST['update_category'])){
		$id=$_POST['id'];
		$name=$_POST['name'];
		$cgst=$_POST['cgst'];
		$sgst=$_POST['sgst'];
		$igst=$_POST['igst'];
		$table="`category`";
		$count=$updateObj->get_count($table,"`name`='$name' and `id`!='$id'");
		if($count==0){
			$col_values="`name`='$name', `cgst`='$cgst', `sgst`='$sgst', `igst`='$igst'";
			$where="`id`='$id'";
			$run=$updateObj->update($table,$col_values,$where);
		}
		else{$run="Category Already Added!";}
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../masterkey?pagename=master");	
		
		
	}
	elseif(isset($_POST['update_company'])){
		$id=$_POST['id'];
		$name=$_POST['name'];
		$category=implode(',',$_POST['category']);
		$category=addslashes($category);
		$table="`company`";
		$count=$updateObj->get_count($table,"`name`='$name' and `id`!='$id'");
		if($count==0){
			$col_values="`name`='$name',`category`='$category'";
			$where="`id`='$id'";
			$run=$updateObj->update($table,$col_values,$where);
		}
		else{$run="Company Already Added!";}
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../masterkey?pagename=master");	
		
		
	}
	elseif(isset($_POST['update_dues'])){
		$id=$_POST['id'];
		$date=$_POST['date'];
		$payment_mode=$_POST['payment_mode'];
		$total=$_POST['total'];
		$paid=$_POST['paid'];
		$advance=$_POST['advance'];
		$shop=$_POST['shop'];
		if($paid!=0){
			$cols="(`date`, `supplier_id`, `amount`, `shop`)";
			$vals="('$date','$id','$paid','$shop')";
			$inspaydetails=$updateObj->insert("`sup_pay_details`",$cols,$vals);
		}
		if(isset($_POST['check_advance'])){ 
			$paid=$paid+$advance;
		}
		$dues=0;
		if($advance=0){
			$getdues=$obj->get_details("`supplier`","`advance`","`id`='$id'");
			$dues=$getdues['advance'];
			if($dues<0){
				$dues=0-$dues;
				if($dues>=$paid){
					$paid=0;
					$dues-=$paid;
				}
				else{
					$dues=0;
					$paid-=$dues;
				}
			}
		}
		if($paid>$total){
			$advance=$paid-$total;
		}
		else{
			$advance="-$dues";
		}
		$update=$updateObj->update("`supplier`","`advance`='$advance'","`id`='$id'");
		$upaid=$paid;
		$array=$updateObj->get_rows("`purchase`","`id`,`paid`,`total_amount`,`dues`,`invoice`","`supplier`='$id' and `dues`!=0 and `shop`='$shop'");
		if(is_array($array)){
			foreach($array as $purchase){
				if($paid>0){
					$pid=$purchase['id'];
					$p=$purchase['paid'];
					$t=$purchase['total_amount'];
					$d=$purchase['dues'];
					$invoice=$purchase['invoice'];
					if($upaid>$d){$upay=$d; $dues=0;}
					else{$upay=$upaid;}
					if($paid>$d){ $pay=$d; $dues=0;}
					else{$pay=$paid; $dues=$t-$p-$pay;}
					$paid-=$pay;
					$pay+=$p;
					$update=$updateObj->update("`purchase`","`paid`='$pay', `dues`='$dues'","`id`='$pid' and `shop`='$shop'");
					$table="`supplier_pay`";
					$columns="(`date`, `supplier_id`, `purchase_id`, `payment_mode`, `invoice`, `total_amount`, `paid`, `dues`, `shop`)";
					$values="('$date','$id','$pid','$payment_mode','$invoice','$t','$upay','$dues','$shop')";
					$run=$updateObj->insert($table,$columns,$values);
					$upaid-=$upay;
				}
			}
		}
		if($update){
			$_SESSION['msg']="Successfully Updated";
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../reports/supplier_pay.php?id=$id&pagename=report");
	}
	elseif(isset($_POST['update_cust_dues'])){
		$id=$_POST['id'];
		$date=$_POST['date'];
		$payment_mode=$_POST['payment_mode'];
		$total=$_POST['total'];
		$paid=$_POST['paid'];
		$advance=$_POST['advance'];
		$shop=$_POST['shop'];
		if($paid!=0){
			$cols="(`date`, `customer_id`, `amount`, `shop`)";
			$vals="('$date','$id','$paid','$shop')";
			$inspaydetails=$updateObj->insert("`cust_pay_details`",$cols,$vals);
		}
		if(isset($_POST['check_advance'])){ 
			$paid=$paid+$advance;
		}
		$dues=0;
		if($advance=0){
			$getdues=$obj->get_details("`customer`","`advance`","`id`='$id'");
			$dues=$getdues['advance'];
			if($dues<0){
				$dues=0-$dues;
				if($dues>=$paid){
					$paid=0;
					$dues-=$paid;
				}
				else{
					$dues=0;
					$paid-=$dues;
				}
			}
		}
		if($paid>$total){
			$advance=$paid-$total;
		}
		else{
			$advance="-$dues";
		}
		$update=$updateObj->update("`customer`","`advance`='$advance'","`id`='$id'");
		$upaid=$paid;
		$array=$updateObj->get_rows("`invoice`","`id`,`paid`,`total_amount`,`dues`","`customer_id`='$id' and `dues`!=0 and `shop`='$shop'");
		if(is_array($array)){
			foreach($array as $invoice){
				if($paid>0){
					$invoice_id=$invoice['id'];
					$p=$invoice['paid'];
					$t=$invoice['total_amount'];
					$d=$invoice['dues'];
					if($upaid>$d){$upay=$d; $dues=0;}
					else{$upay=$upaid;}
					if($paid>$d){ $pay=$d; $dues=0;}
					else{$pay=$paid; $dues=$t-$p-$pay;}
					$paid-=$pay;
					$pay+=$p;
					$update=$updateObj->update("`invoice`","`paid`='$pay', `dues`='$dues'","`id`='$invoice_id' and `shop`='$shop'");
					$table="`customer_pay`";
					$columns="(`date`, `payment_mode`, `customer_id`, `invoice_id`, `total_amount`, `paid`, `dues`, `shop`)";
					$values="('$date','$payment_mode','$id','$invoice_id','$t','$upay','$dues','$shop')";
					$run=$updateObj->insert($table,$columns,$values);
					$upaid-=$upay;
				}
			}
		}
		if($update){
			$_SESSION['msg']="Successfully Updated";
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../reports/customer_pay.php?id=$id&pagename=report");
	}
	elseif(isset($_POST['update_invoice'])){
		$shop=$_POST['shop'];
		$date=$_POST['date'];
		$id=$_POST['id'];
		$payment_mode=$_POST['pay_mode'];
		$customer_id=$_POST['customer_id'];
		$total_amount=$_POST['total_amount'];
		$paid=strip_tags($_POST['paid']);
		$dues=strip_tags($_POST['dues']);
		$cpaid=$repaid=strip_tags($_POST['repaid']);
		$next_payment=strip_tags($_POST['next_payment']);
		if($repaid>=$dues){
			$advance=$repaid-$dues;
			$cpaid=$dues;
			$dues=0;
		}else{
			$advance=0;
			$dues-=$cpaid;
		}
		if($customer_id){
			$update=$updateObj->update("`customer`","`advance`=`advance`+'$advance'","`id`='$customer_id'");
			$cols="(`date`, `customer_id`, `amount`, `shop`)";
			$vals="('$date','$customer_id','$repaid','$shop')";
			$inspaydetails=$updateObj->insert("`cust_pay_details`",$cols,$vals);
		}
		$table="`invoice`";
		
		$col_values="`paid`=`paid`+'$cpaid',`dues`=`dues`-'$cpaid',`next_payment`='$next_payment'";
		$where="`id`='$id'";
		$run=$updateObj->update($table,$col_values,$where);
		if($run===true){
			$table2="`customer_pay`";
			$columns="(`date`, `customer_id`, `payment_mode`, `invoice_id`, `total_amount`, `paid`, `dues`, `shop`)";
			$values="('$date','$customer_id','$payment_mode','$id','$total_amount','$cpaid','$dues','$shop')";
			$updateObj->insert($table2,$columns,$values);
			header("Location:../invoice/print_invoice.php?inv_id=$id&page=invoice");	
		
		}else{
			$_SESSION['err']=$run;
			header("Location:../invoice/dues_payment?pagename=invoice");	
		}
	}
	elseif(isset($_POST['update_stock'])){
		$id=$_POST['id'];
		$quantity=$_POST['quantity'];
		$mrp=$_POST['mrp'];
		$uom=$_POST['uom'];
		$hsn=$_POST['hsn'];
		$selling_price=$_POST['selling_price'];
		$description=$_POST['description'];
		$table="`stock`";
		$col_values="`mrp`='$mrp',`uom`='$uom',`quantity`='$quantity',`hsn`='$hsn',`selling_price`='$selling_price',`description`='$description'";
		$where="`id`='$id'";
		$run=$updateObj->update($table,$col_values,$where);
		if($run===true){
			$_SESSION['msg']="Successfully Updated!";
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../stock?pagename=stock");	
	}
elseif(isset($_POST['update_additionalterms'])){
	$id=$_POST['id'];
	$terms=$_POST['uterms'];
	$table="`add_terms`";
	$col_values="`terms`='$terms'";
	$where="`id`='$id'";
	$run=$obj->update($table,$col_values,$where);
	if($run===true){
		$_SESSION['msg']="Successfully Updated!";	
	}else{
		$_SESSION['err'].=$run;
	}
	header("Location:../masterkey/?pagename=master&section=add_terms");	
}
	else{
		header("Location:../home?pagename=home");	
	}
?>