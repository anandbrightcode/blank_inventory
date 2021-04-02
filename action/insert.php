<?php
	session_start();
	include('class.php');
	$insertObj=new database();
	$link=$insertObj->getDBConnect();
	if(isset($_POST['save_cust'])){
		$name=$_POST['name'];
		$phone=$_POST['phone'];
		$gst=$_POST['gst'];
		$address=$_POST['address'];
		$email=$_POST['email'];
		$shop=$_POST['shop'];
		$table="`customer`";
		if($phone!=''){
			$count1=$insertObj->get_count($table,"`phone`='$phone'");
		}else{ $count1=0; }
		if($gst!=''){
			$count2=$insertObj->get_count($table,"`gst`='$gst'");
		}else{ $count2=0; }
		if($count1==0 && $count2==0){
			$columns="(`name`, `phone`, `gst`, `address`, `email`, `shop`)";
			$values="('$name','$phone','$gst','$address','$email','$shop')";
			$run=$insertObj->insert($table,$columns,$values);
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
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../customer?pagename=customer");	
		
	}
	elseif(isset($_POST['save_supplier'])){
		$name=$_POST['name'];
		$phone=$_POST['phone'];
		$gst=$_POST['gst'];
		$address=$_POST['address'];
		$email=$_POST['email'];
		$state=$_POST['state'];
		$shop=$_POST['shop'];
		$table="`supplier`";
		if($phone!=''){
			$count1=$insertObj->get_count($table,"`phone`='$phone'");
		}else{ $count1=0; }
		if($gst!=''){
			$count2=$insertObj->get_count($table,"`gst`='$gst'");
		}else{ $count2=0; }
		if($count1==0 && $count2==0){
			$columns="(`name`, `phone`, `gst`, `address`, `email`, `state`, `shop`)";
			$values="('$name','$phone','$gst','$address','$email','$state','$shop')";
			$run=$insertObj->insert($table,$columns,$values);
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
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../supplier?pagename=supplier");	
		
		
	}
	elseif(isset($_POST['adduser'])){
		$username=$_POST['username'];
		$password=$_POST['password'];
		$role=$_POST['role'];
		$shop=$_POST['shop'];
		$active=$_POST['active'];
		$table="`users`";
		$columns="(`username`, `password`, `role`, `shop`, `active`)";
		$values="('$username','$password','$role','$shop','$active')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../admin?pagename=admin");	
		
		
	}
	elseif(isset($_POST['save_expense'])){
		$date=$_POST['date'];
		$name=$_POST['name'];
		$bill=$_POST['bill'];
		$particulars=$_POST['particulars'];
		$amount=$_POST['amount'];
		$shop=$_POST['shop'];
		$table="`expense`";
		$columns="(`date`, `name`, `bill`, `particulars`, `amount`, `shop`)";
		$values="('$date','$name','$bill','$particulars','$amount','$shop')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../expenses?pagename=expense");	
		
		
	}
	elseif(isset($_POST['add_category'])){
		$name=addslashes($_POST['name']);
		$shop=$_POST['shop'];
		$cgst=$_POST['cgst'];
		$sgst=$_POST['sgst'];
		$igst=$_POST['igst'];
		$table="`category`";
		$count=$insertObj->get_count($table,"`name`='$name'");
		if($count==0){
			$columns="( `name`, `cgst`, `sgst`, `igst`, `shop`)";
			$values="('$name','$cgst','$sgst','$igst','$shop')";
			$run=$insertObj->insert($table,$columns,$values);
		}
		else{
			$run="Category Already Added!";	
		}
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../masterkey?pagename=master");	
		
		
	}
	elseif(isset($_POST['add_company'])){
		$name=$_POST['name'];
		$shop=$_POST['shop'];
		$category=implode(',',$_POST['category']);
		$table="`company`";
		$count=$insertObj->get_count($table,"`name`='$name'");
		if($count==0){
			$columns="( `name`, `category`, `shop`)";
			$values="('$name','$category','$shop')";
			$run=$insertObj->insert($table,$columns,$values);
		}
		else{
			$run="Company Already Added!";	
		}
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../masterkey?pagename=master");	
		
		
	}
	elseif(isset($_POST['add_model'])){
		$shop=$_POST['shop'];
		$category=addslashes($_POST['category']);
		$company_id=$_POST['company_id'];
		$table="`models`";
		$msg=array();
		$columns="( `category`, `company_id`, `model`, `shop`)";
		foreach($_POST['model'] as $model){
			if($model!=''){
				$model=trim($model);
				$count=$insertObj->get_count($table,"`category`='$category' and `company_id`='$company_id' and `model`='$model'");
				if($count==0){
					$values="('$category','$company_id','$model','$shop')";
					$run=$insertObj->insert($table,$columns,$values);
				}
				else{
					$msg[]=$model;
				}
			}
		}
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
			if(sizeof($msg)!=0){
				$msg=implode(',',$msg);
				$_SESSION['err']=$msg." Already Added!";
			}
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../masterkey?pagename=master");	
		
		
	}
	elseif(isset($_POST['tempbutton']) && $_POST['tempbutton']=='add'){
		$category=strip_tags($_POST['category']);
		$company_id=strip_tags($_POST['company_id']);
		$model=strip_tags($_POST['model']);
		$hsn=strip_tags($_POST['hsn']);
		$mrp=strip_tags($_POST['mrp']);
		$uom=strip_tags($_POST['uom']);
		$shop=strip_tags($_POST['shop']);
		$quantity=strip_tags($_POST['quantity']);
		$purchase=strip_tags($_POST['purchase']);
		$charity=strip_tags($_POST['charity']);
		$discount=strip_tags($_POST['discount']);
		$cust_discount=strip_tags($_POST['cust_discount']);
		$special_discount=strip_tags($_POST['special_discount']);
		$cash_discount=strip_tags($_POST['cash_discount']);
		/*$taxable=($quantity*$purchase);
		$taxable-=$discount;
		$taxable-=($cust_discount*$taxable)/100;*/
		if(isset($_POST['gst'])){$gst=strip_tags($_POST['gst']);}else{$gst='';}
		$cat_details=$insertObj->get_details("`category`","*","`id`='$category' and `shop`='$shop'");
		$tax=$_POST['tax'];
		$cgst=$sgst=$igst=$cvalue=$svalue=$ivalue=0;
		if($tax=='include'){
			$amount=$purchase;
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
				$purchase=(1000000*$amount);
				$purchase/=(1000000-10000*$special_discount-10000*$cash_discount+100*$special_discount*$cash_discount+10000*($cgst+$sgst)-100*($cgst+$sgst)*$special_discount-100*($cgst+$sgst)*$cash_discount+$special_discount*$cash_discount*($cgst+$sgst));
				$purchase*=100/(100-$cust_discount);
				//$purchase=(10000*$amount)/(10000-100*$cust_discount+100*($cgst+$sgst)-$cust_discount*($cgst+$sgst));
				$purchase-=($charity)/$quantity;
				$purchase+=$discount/$quantity;
			}
			elseif($gst=='igst'){
				$igst=$cat_details['igst'];
				$purchase=(1000000*$amount);
				$purchase/=(1000000-10000*$special_discount-10000*$cash_discount+100*$special_discount*$cash_discount+10000*($igst)-100*($cgst+$sgst)*$special_discount-100*($igst)*$cash_discount+$special_discount*$cash_discount*($igst));
				$purchase*=100/(100-$cust_discount);
				$purchase-=($charity)/$quantity;
				$purchase+=$discount/$quantity;
			}
		}
		else{
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
			}elseif($gst=='igst'){
				$igst=$cat_details['igst'];
			}
		}
		$amount=$purchase*$quantity;
		$taxable=$amount+$charity;
		$taxable=$taxable-$discount;
		$taxable-=($cust_discount*$taxable)/100;
		$taxable-=($special_discount*$taxable)/100;
		$taxable-=($cash_discount*$taxable)/100;
		if($gst=='cgst'){
			$cvalue=($taxable*$cgst)/100;
			$svalue=($taxable*$sgst)/100;
		}elseif($gst=='igst'){
			$igst=$cat_details['igst'];
			$ivalue=($taxable*$igst)/100;
		}
		$purchase=twoDigits($purchase);
		$taxable=twoDigits($taxable);
		$cvalue=twoDigits($cvalue);
		$svalue=twoDigits($svalue);
		$ivalue=twoDigits($ivalue);
		$amount=$taxable+$cvalue+$svalue+$ivalue;
		$amount=twoDigits($amount);
		$columns="(`category`, `company_id`, `model`, `mrp`, `uom`, `hsn`, `quantity`, `purchase`, `charity`, `discount`, `custdiscount`,  `special_discount`,  `cash_discount`, 
					`taxable`, `cgst`, `sgst`, `igst`, `cvalue`, `svalue`, `ivalue`, `amount`, `shop`) ";
		$values="('$category','$company_id','$model','$mrp','$uom','$hsn','$quantity','$purchase','$charity','$discount','$cust_discount','$special_discount','$cash_discount',
					'$taxable','$cgst','$sgst','$igst','$cvalue','$svalue','$ivalue','$amount','$shop')";
		$run=$insertObj->insert("`purchase_temp`",$columns,$values);
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}
	elseif(isset($_POST['add_purchase'])){
		$date=strip_tags($_POST['date']);
		$invoice=strip_tags($_POST['invoice']);
		$type=strip_tags($_POST['type']);
		$supplier=strip_tags($_POST['supplier']);
		$payment_mode=strip_tags($_POST['payment_mode']);
		$cheque_no=strip_tags($_POST['cheque_no']);
		$bank=strip_tags($_POST['bank']);
		$cheque_date=strip_tags($_POST['cheque_date']);
		$gross_amount=strip_tags($_POST['gross_amount']);
		$roundoff=strip_tags($_POST['round']);
		$transport=strip_tags($_POST['transport']);
		$total_amount=strip_tags($_POST['total_amount']);
		$paid=strip_tags($_POST['paid']);
		$dues=strip_tags($_POST['dues']);
		$shop=strip_tags($_POST['shop']);
		$advance=strip_tags($_POST['advance']);
		if(($paid=='' || $paid==0) && ($advance=='' || $advance==0)){$paid=0;$dues=$total_amount;}
		if(isset($_POST['check_advance'])){ 
			$check_advance=$_POST['check_advance']; 
			$cpaid=$paid+$advance;
			$advance-=$total_amount;
			if($cpaid>$total_amount){$cpaid=$total_amount;}
		}
		else{ $check_advance=''; $cpaid=$paid;}
		if($advance<0){$advance=0;}
	    if($paid>$total_amount){
	    	$paid=$total_amount;
	    }
		if($dues<1 ){
			$dues=0;
		}
		if(isset($_POST['id']))
		{
		    	$id=$_POST['id'];
				$get_purchasedetails=$insertObj->get_rows("`purchase_order`","`category`, `company_id`, `model`, `quantity`","`purchase_id`='$id'");
				if(is_array($get_purchasedetails))
				{
				    foreach($get_purchasedetails as $val)
					{
						$category=$val['category'];
						$company_id=$val['company_id'];
						$model=$val['model'];
						$quantity=$val['quantity'];
					    $update_stock=$insertObj->update("`stock`","`quantity`=`quantity`-'$quantity'","`category`='$category' and `company_id`='$company_id' and `model`='$model'");	
					}	
				}
				
				$delete_purchaseorder=$insertObj->delete("`purchase_order`","`purchase_id`='$id'");
				$delete_purchase=$insertObj->delete("`purchase`","`id`='$id'");
				$delete_supplierpay=$insertObj->delete("`supplier_pay`","`purchase_id`='$id'");
	    }
		
		$update=$insertObj->update("`supplier`","`advance`='$advance'","`id`='$supplier'");
		if($paid!='' && $paid!=0){
			$cols="(`date`, `supplier_id`, `payment_mode`, `cheque_no`, `cheque_date`, `bank`, `amount`, `shop`)";
			$vals="('$date','$supplier','$payment_mode','$cheque_no','$cheque_date','$bank','$paid','$shop')";
			$inspaydetails=$insertObj->insert("`sup_pay_details`",$cols,$vals);
		}
		$selqty=$insertObj->get_details("`purchase_temp`","sum(`quantity`) as `total`","`shop`='$shop'");
		$t_qty=$selqty['total'];
		$tr=$transport/$t_qty;
		$table="`purchase`";
		$columns="(`date`, `invoice`, `type`, `supplier`, `payment_mode`, `cheque_no`, `bank`, `cheque_date`, `gross_amount`, `roundoff`, `transport`, `total_amount`, 
					`paid`, `dues`, `shop`)";
		$values="('$date','$invoice','$type','$supplier','$payment_mode','$cheque_no','$bank','$cheque_date','$gross_amount','$roundoff','$transport','$total_amount',
					'$cpaid','$dues','$shop')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$arr=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$purchase_id=$arr['id'];
			$purchase_list=$insertObj->get_rows("`purchase_temp`","*","`shop`='$shop'");
			$insertObj->delete("`purchase_temp`","`shop`='$shop'");
			$table2="`purchase_order`";
			$columns2="(`category`, `company_id`, `model`, `mrp`, `uom`, `hsn`, `quantity`, `purchase`, `charity`, `discount`, `custdiscount`, `special_discount`, `cash_discount`, 
						`taxable`, `cgst`, `sgst`, `igst`, `cvalue`, `svalue`, `ivalue`, `purchase_gst`, `purchase_id`, `shop`)";
			foreach($purchase_list as $product){
				$category=$product['category'];$company_id=$product['company_id'];$model=$product['model'];$mrp=$product['mrp'];$uom=$product['uom'];
				$hsn=$product['hsn'];$quantity=$product['quantity'];$purchase=$product['purchase'];$charity=$product['charity'];$discount=$product['discount'];
				$custdiscount=$product['custdiscount']; $special_discount=$product['special_discount']; $cash_discount=$product['cash_discount'];
				$taxable=$product['taxable'];$cgst=$product['cgst'];$sgst=$product['sgst'];$igst=$product['igst'];
				$cvalue=$product['cvalue'];$svalue=$product['svalue'];$ivalue=$product['ivalue'];
				$t=($tr*$quantity);$purchase_gst=$product['amount'];
				$values2="('$category','$company_id','$model','$mrp','$uom','$hsn','$quantity','$purchase','$charity','$discount','$custdiscount','$special_discount','$cash_discount',
							'$taxable','$cgst','$sgst','$igst','$cvalue','$svalue','$ivalue','$purchase_gst','$purchase_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
				$table3="`stock`";
				$where="`category`='$category' and `company_id`='$company_id' and `model`='$model' and `shop`='$shop'";
				$count=$insertObj->get_count($table3,$where);
				$pgst=($purchase_gst+$t)/$quantity;
				$amount=$pgst*$quantity;
				$pgst=twoDigits($pgst);
				if($count==0){
					$columns3="(`date`, `category`, `company_id`, `model`, `mrp`, `uom`, `hsn`, `quantity`, `purchase`, `base_price`, `shop`)";
					$values3="('$date','$category','$company_id','$model','$mrp','$uom','$hsn','$quantity','$purchase','$pgst','$shop')";
					$rs3=$insertObj->insert($table3,$columns3,$values3);
				}
				else{
					$col_values="`quantity`=`quantity`+'$quantity',`mrp`='$mrp',`purchase`='$purchase', `base_price`='$pgst'";
					$update=$insertObj->update("`stock`",$col_values,$where);
				}
			}
			$table5="`supplier_pay`";
			$columns5="(`date`, `supplier_id`, `type`, `payment_mode`, `purchase_id`, `invoice`, `total_amount`, `paid`, `dues`, `shop`)";
			$values5="('$date','$supplier','$type','$payment_mode','$purchase_id','$invoice','$total_amount','$cpaid','$dues','$shop')";
			$run5=$insertObj->insert($table5,$columns5,$values5);
			$_SESSION['msg']="Successfully Added";
		}
		else{
			$_SESSION['err']=$run;
		}
		header("Location:../purchase?pagename=purchase");
	}
	elseif(isset($_POST['sbutton']) && $_POST['sbutton']=='add'){
		$category=addslashes($_POST['category']);
		$company_id=$_POST['company_id'];
		$model=$_POST['model'];
		$hsn=$_POST['hsn'];
		$mrp=$_POST['mrp'];
		$slno=$_POST['slno'];
		$uom=$_POST['uom'];
		$shop=$_POST['shop'];
		$quantity=$_POST['quantity'];
		$price=$_POST['price'];
		$charity=$_POST['charity'];
		$discount=$_POST['discount'];
		$custdiscount=$_POST['custdiscount'];
		/*$amount=$price*$quantity;
		$taxable=$amount-$discount;
		$taxable-=($custdiscount*$taxable)/100;*/
		if(isset($_POST['gstval'])){$gst=strip_tags($_POST['gstval']);}else{$gst='';}
		$cat_details=$insertObj->get_details("`category`","*","`id`='$category' and `shop`='$shop'");
		$tax=$_POST['tax'];
		$cgst=$sgst=$igst=$cvalue=$svalue=$ivalue=0;
		if($tax=='include'){
			$amount=$price;
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($cgst+$sgst)-$custdiscount*($cgst+$sgst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
			elseif($gst=='igst'){
				$igst=$cat_details['igst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($igst)-$custdiscount*($igst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
		}
		else{
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
			}elseif($gst=='igst'){
				$igst=$cat_details['igst'];
			}
		}
		
		$amount=$price*$quantity;
		$taxable=$amount+$charity;
		$taxable=$taxable-$discount;
		$taxable-=($custdiscount*$taxable)/100;
		if($gst=='cgst'){
			$cvalue=($taxable*$cgst)/100;
			$svalue=($taxable*$sgst)/100;
		}elseif($gst=='igst'){
			$igst=$cat_details['igst'];
			$ivalue=($taxable*$igst)/100;
		}
		$price=twoDigits($price);
		$taxable=twoDigits($taxable);
		$cvalue=twoDigits($cvalue);
		$svalue=twoDigits($svalue);
		$ivalue=twoDigits($ivalue);
		$amount=$taxable+$cvalue+$svalue+$ivalue;
		$amount=twoDigits($amount);
		
		$select=$insertObj->get_details("`stock`","`id`,`quantity`","`category`='$category' and `company_id`='$company_id' and `model`='$model' and `shop`='$shop'");
		$ready_id=$select['id'];
		$avl_quantity=$select['quantity'];
		$avl_quantity-=$quantity;	
		$update=$insertObj->update("`stock`","`quantity`='$avl_quantity'","`id`='$ready_id'");
		$columns="(`category`, `company_id`, `model`, `mrp`, `slno`, `uom`, `hsn`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, 
				`igst`, `cvalue`, `svalue`, `ivalue`, `amount`, `ready_id`,`shop`)";
		$values="('$category','$company_id','$model','$mrp','$slno','$uom','$hsn','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst',
				'$igst','$cvalue','$svalue','$ivalue','$amount','$ready_id','$shop')";
	
		$run=$insertObj->insert("`invoice_temp`",$columns,$values);
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}
		elseif(isset($_POST['servicebutton']) && $_POST['servicebutton']=='add'){
		$description=$_POST['description'];
		$shsn=$_POST['shsn'];
		$suom=$_POST['suom'];
		$squantity=$_POST['squantity'];
		$srate=$_POST['srate'];
		$ser_gst=$_POST['ser_gst'];
		$shop=$_POST['shop'];
		$stotal_amount=$srate*$squantity;
		$gstrate =($stotal_amount*$ser_gst)/ 100;
		$samount=$stotal_amount+$gstrate;
		$columns="(`description`, `shsn`,`suom`, `squantity`, `srate`, `ser_gst`, `ser_gstvalue`,`samount`,`stotal_amount`,`shop`)";
		$values="('$description','$shsn','$suom','$squantity','$srate','$ser_gst','$gstrate','$samount','$stotal_amount','$shop')";
		$run=$insertObj->insert("`service_temp`",$columns,$values);
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}

	elseif(isset($_POST['qutoservicebutton']) && $_POST['qutoservicebutton']=='add'){
		$description=$_POST['description'];
		$shsn=$_POST['shsn'];
		$suom=$_POST['suom'];
		$squantity=$_POST['squantity'];
		$srate=$_POST['srate'];
		$ser_gst=$_POST['ser_gst'];
		$shop=$_POST['shop'];
		$stotal_amount=$srate*$squantity;
		$gstrate =($stotal_amount*$ser_gst)/ 100;
		$samount=$stotal_amount+$gstrate;
		$columns="(`description`, `shsn`,`suom`, `squantity`, `srate`, `ser_gst`, `ser_gstvalue`,`samount`,`stotal_amount`,`shop`)";
		$values="('$description','$shsn','$suom','$squantity','$srate','$ser_gst','$gstrate','$stotal_amount','$samount','$shop')";
		 $run=$insertObj->insert("`quotservice_temp`",$columns,$values);
		//($columns);
		//print_r($values) die;
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}
	elseif(isset($_POST['qutoservice_invoice'])){
		$billing_mode=$_POST['billing_mode'];
		$date=$_POST['date'];
		$shop=$_POST['shop'];
	    $type=$_POST['type'];
	    $reverse=$_POST['reverse'];
	    $payment_mode=$_POST['payment_mode'];
		$cheque_no=$_POST['cheque_no'];
		$cheque_date=$_POST['cheque_date'];
		$bank=$_POST['bank'];
	    $customer_id=$_POST['customer_id'];
	    $customer_name=$_POST['customer_name'];
	    $customer_mobile=$_POST['customer_mobile'];
	    $add_from=$_POST['add_from'];
	    $add_to=$_POST['add_to'];
	    //$gst=$_POST['gst'];
		$stype=$_POST['stype'];
	    $transport_mode=$_POST['transport_mode'];
	    $state=$_POST['state'];
		$code=$_POST['code'];
	//	$srate=$_POST['srate'];
	    $sgross_amount=$_POST['sgross_amount'];
	    $sroundoff=$_POST['sround'];
		$stotal_amount=$_POST['stotal_amount'];
		$add_terms=$_POST['add_terms'];
		$terms=$_POST['terms'];

		$year=date('Y',strtotime($date));
		$closing="$year-03-31";
		if(date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($closing)))
		{
			$year1=date('y',strtotime("$date -1 year"));	
			$year2=date('y',strtotime($date));
		}
		else{
			$year1=date('y',strtotime($date));
			$year2=date('y',strtotime("$date +1 year"));	
		}
		$shop_details=$insertObj->get_details("`shop`","`abv`","`id`='$shop'");
		$prefix=$shop_details['abv']."/QUO/";
		$prefix.=$year1."-".$year2."/";
		$inv_no=$insertObj->get_details("`qutoservice`","max(`invoice_no`) as invoice_no");
		$invoice_no=$inv_no['invoice_no'];
		if($invoice_no==NULL){
			$invoice_no=1;
		}
		else{
			$invoice_no++;	
		}
		$table="`qutoservice`";
		$columns="(`prefix`,`billing_mode`, `invoice_no`, `date`, `type`,`stype`, `payment_mode`, `cheque_date`, `cheque_no`, `bank`, `customer_id`, `customer_name`, `customer_mobile`,
                   `transport_mode`,  `state`,  `code`,`add_from`, `add_to`, `sgross_amount`, `sroundoff`, `stotal_amount`,`add_terms`,`terms`,`shop`)";

		$values="('$prefix','$billing_mode','$invoice_no','$date','$type','$stype','$payment_mode','$cheque_date','$cheque_no','$bank','$customer_id','$customer_name','$customer_mobile',
     	'$transport_mode','$state','$code',	'$add_from','$add_to','$sgross_amount','$sroundoff','$stotal_amount','$add_terms','$terms','$shop')";

		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
		$select=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$inv_id=$select['id'];
			//print_r($inv_id); die;
			$update=$insertObj->update($table,"`invoice_no`='$invoice_no'","`id`='$inv_id'");
			$array=$insertObj->get_rows("`quotservice_temp`","*","`shop`='$shop'");
			$delete=$insertObj->delete("`quotservice_temp`","`shop`='$shop'");
			$table2="`qutoservicelist`";
			$columns2="(`description`, `shsn`,`suom`, `squantity`, `srate`, `ser_gst`, `ser_gstvalue`,`samount`,`stotal_amount`,`service_id`,`shop`)";
			foreach($array as $product){
				$description=$product['description'];
				$shsn=$product['shsn'];
				$suom=$product['suom'];
				$squantity=$product['squantity'];
				$srate=$product['srate'];
				$ser_gst=$product['ser_gst'];
				$ser_gstvalue=$product['ser_gstvalue'];
				$samount=$product['samount'];
				$stotal_amount=$product['stotal_amount'];
				$values2="('$description','$shsn','$suom','$squantity','$srate','$ser_gst','$ser_gstvalue','$samount','$stotal_amount','$inv_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
			    }
				header("Location:../invoice/print_servicequto.php?page=invoice&inv_id=$inv_id");
			
	      	}
			else{
				$_SESSION['err']=$run;
				header("Location:../invoice?pagename=invoice");	
			}
			
		}
	elseif(isset($_POST['service_invoice'])){
		$billing_mode=$_POST['billing_mode'];
		$date=$_POST['date'];
		$shop=$_POST['shop'];
	    $type=$_POST['type'];
	    $reverse=$_POST['reverse'];
	    $payment_mode=$_POST['payment_mode'];
		$cheque_no=$_POST['cheque_no'];
		$cheque_date=$_POST['cheque_date'];
		$bank=$_POST['bank'];
	    $customer_id=$_POST['customer_id'];
	    $customer_name=$_POST['customer_name'];
	    $customer_mobile=$_POST['customer_mobile'];
	    $add_from=$_POST['add_from'];
	    $add_to=$_POST['add_to'];
	    //$gst=$_POST['gst'];
		$stype=$_POST['stype'];
	    $consignee_name=$_POST['consignee_name'];
	    $consignee_mobile=$_POST['consignee_mobile'];
	    $consignee_address=$_POST['consignee_address'];
	    $consignee_gst=$_POST['consignee_gst'];
	    $consignee_state=$_POST['consignee_state'];
	    $consignee_code=$_POST['consignee_code'];
	    $transport_mode=$_POST['transport_mode'];
	    $state=$_POST['state'];
		$code=$_POST['code'];
		$po=$_POST['po'];
	    $sgross_amount=$_POST['sgross_amount'];
	    $sroundoff=$_POST['sround'];
		$stotal_amount=$_POST['stotal_amount'];
		$inv_no=$insertObj->get_details("`service`","max(`invoice_no`) as invoice_no");
		$year=date('Y',strtotime($date));
		$closing="$year-03-31";
		if(date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($closing)))
		{
			$year1=date('y',strtotime("$date -1 year"));	
			$year2=date('y',strtotime($date));
		}
		else{
			$year1=date('y',strtotime($date));
			$year2=date('y',strtotime("$date +1 year"));	
		}
		$shop_details=$insertObj->get_details("`shop`","`abv`","`id`='$shop'");
		$prefix=$shop_details['abv']."/";
		$prefix.=$year1."-".$year2."/";
		$inv_no=$insertObj->get_details("`service`","max(`invoice_no`) as invoice_no");
		$invoice_no=$inv_no['invoice_no'];
		if($invoice_no==NULL){
			$invoice_no=1;
		}
		else{
			$invoice_no++;	
		}
		$table="`service`";
		$columns="(`prefix`,`billing_mode`, `invoice_no`, `date`, `type`,`stype`, `payment_mode`, `cheque_date`, `cheque_no`, `bank`, `customer_id`, `customer_name`, `customer_mobile`,
		`consignee_name`, `consignee_mobile`, `consignee_address`, `consignee_gst`, `consignee_state`, `consignee_code`,`transport_mode`,`po`,`state`,  `code`, 
					`add_from`, `add_to`, `sgross_amount`, `sroundoff`, `stotal_amount`, `shop`)";

		$values="('$prefix','$billing_mode','$invoice_no','$date','$type','$stype','$payment_mode','$cheque_date','$cheque_no','$bank','$customer_id','$customer_name','$customer_mobile', '$consignee_name','$consignee_mobile','$consignee_address','$consignee_gst','$consignee_state','$consignee_code',
                    '$transport_mode','$po','$state','$code',
					'$add_from','$add_to','$sgross_amount','$sroundoff','$stotal_amount','$shop')";
			//print_r($columns); die;
			//print_r($values); die;
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
		$select=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$inv_id=$select['id'];
			//print_r($inv_id); die;
			$update=$insertObj->update($table,"`invoice_no`='$invoice_no'","`id`='$inv_id'");
			$array=$insertObj->get_rows("`service_temp`","*","`shop`='$shop'");
			$delete=$insertObj->delete("`service_temp`","`shop`='$shop'");
			$table2="`servicelist`";
			$columns2="(`description`, `shsn`,`suom`, `squantity`, `srate`, `ser_gst`, `ser_gstvalue`,`samount`,`stotal_amount`,`service_id`,`shop`)";
			foreach($array as $product){
				$description=$product['description'];
				$shsn=$product['shsn'];
				$suom=$product['suom'];
				$squantity=$product['squantity'];
				$srate=$product['srate'];
				$ser_gst=$product['ser_gst'];
				$ser_gstvalue=$product['ser_gstvalue'];
				$samount=$product['samount'];
				$stotal_amount=$product['stotal_amount'];
				$values2="('$description','$shsn','$suom','$squantity','$srate','$ser_gst','$ser_gstvalue','$samount','$stotal_amount','$inv_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
				}
			$table3="`customer_pay`";
			$columns3="(`date`, `customer_id`, `payment_mode`, `type`, `invoice_id`, `total_amount`, `paid`, `dues`, `shop`)";
			$values3="('$date','$customer_id','$payment_mode','$type','$inv_id','$total_amount','$cpaid','$dues','$shop')";
			$run3=$insertObj->insert($table3,$columns3,$values3);
			header("Location:../invoice/print_service.php?page=invoice&inv_id=$inv_id");
			
	      	}
			else{
				$_SESSION['err']=$run;
				header("Location:../invoice?pagename=invoice");	
			}
			
		}
	
	elseif(isset($_POST['save_invoice'])){
	    $billing_mode=$_POST['billing_mode'];
		$date=$_POST['date'];
		$shop=$_POST['shop'];
	    $type=$_POST['type'];
	    $reverse=$_POST['reverse'];
	    $payment_mode=$_POST['payment_mode'];
		$cheque_no=$_POST['cheque_no'];
		$cheque_date=$_POST['cheque_date'];
		$bank=$_POST['bank'];
	    $customer_id=$_POST['customer_id'];
	    $customer_name=$_POST['customer_name'];
	    $customer_mobile=$_POST['customer_mobile'];
	    $add_from=$_POST['add_from'];
	    $add_to=$_POST['add_to'];
	    $gst=$_POST['gst'];
		$stype=$_POST['stype'];
	    $consignee_name=$_POST['consignee_name'];
	    $consignee_mobile=$_POST['consignee_mobile'];
	    $consignee_address=$_POST['consignee_address'];
	    $consignee_gst=$_POST['consignee_gst'];
	    $consignee_state=$_POST['consignee_state'];
	    $consignee_code=$_POST['consignee_code'];
	    $transport_mode=$_POST['transport_mode'];
	    $state=$_POST['state'];
	    $code=$_POST['code'];
	    $gross_amount=$_POST['gross_amount'];
	    $transport=$_POST['transport'];
	    $roundoff=$_POST['round'];
	    $total_amount=$_POST['total_amount'];
	    $advance=$_POST['advance'];
	    $paid=$cpaid=$_POST['paid'];
		$dues=$_POST['dues'];
		$po=$_POST['po'];
		if(isset($_POST['check_advance'])){ 
			$check_advance=$_POST['check_advance']; 
			$cpaid=$paid+$advance;
			$advance-=$total_amount;
			if($cpaid>$total_amount){$cpaid=$total_amount;}
		}
		else{ $check_advance=''; }
		if($advance<0){$advance=0;}
	    if($paid>$total_amount){
	    	$paid=$total_amount;
	    }
		if($dues<1 ){
			$dues=0;
		}
		
		if($customer_id!=''){
			$update=$insertObj->update("`customer`","`advance`='$advance'","`id`='$customer_id'");
			if($paid!='' && $paid!=0){
				$cols="(`date`, `customer_id`, `payment_mode`, `cheque_no`, `cheque_date`, `bank`, `amount`, `shop`)";
				$vals="('$date','$customer_id','$payment_mode','$cheque_no','$cheque_date','$bank','$paid','$shop')";
				$inspaydetails=$insertObj->insert("`cust_pay_details`",$cols,$vals);
			}
		}
		
	    $next_payment=$_POST['next_payment'];
		$year=date('Y',strtotime($date));
		$closing="$year-03-31";
		if(date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($closing)))
		{
			$year1=date('y',strtotime("$date -1 year"));	
			$year2=date('y',strtotime($date));
		}
		else{
			$year1=date('y',strtotime($date));
			$year2=date('y',strtotime("$date +1 year"));	
		}
		$shop_details=$insertObj->get_details("`shop`","`abv`","`id`='$shop'");
		$prefix=$shop_details['abv']."/";
		$prefix.=$year1."-".$year2."/";
		$inv_no=$insertObj->get_details("`invoice`","max(`invoice_no`) as invoice_no","`prefix`='$prefix'");
		$invoice_no=$inv_no['invoice_no'];
		if($invoice_no==NULL){
			$invoice_no=1;
		}
		else{
			$invoice_no++;	
		}
		$table="`invoice`";
		$columns="(`prefix`, `billing_mode`, `reverse`, `date`, `type`,`stype`, `payment_mode`, `cheque_date`, `cheque_no`, `bank`, `customer_id`, `customer_name`, `customer_mobile`, `gst`,
                    `consignee_name`, `consignee_mobile`, `consignee_address`, `consignee_gst`, `consignee_state`, `consignee_code`, `transport_mode`, `po`, `state`,  `code`, 
					`add_from`, `add_to`, `gross_amount`, `transport`, `roundoff`, `total_amount`, `paid`, `dues`, `next_payment`, `shop`)";

		$values="('$prefix','$billing_mode','$reverse','$date','$type','$stype','$payment_mode','$cheque_date','$cheque_no','$bank','$customer_id','$customer_name','$customer_mobile','$gst',
                    '$consignee_name','$consignee_mobile','$consignee_address','$consignee_gst','$consignee_state','$consignee_code','$transport_mode','$po','$state','$code',
					'$add_from','$add_to','$gross_amount','$transport','$roundoff','$total_amount','$cpaid','$dues','$next_payment','$shop')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$select=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$inv_id=$select['id'];
			$update=$insertObj->update($table,"`invoice_no`='$invoice_no'","`id`='$inv_id'");
			$array=$insertObj->get_rows("`invoice_temp`","*","`shop`='$shop'");
			$delete=$insertObj->delete("`invoice_temp`","`shop`='$shop'");
			$table2="`sales`";
			$columns2="(`category`, `company_id`, `model`,`mrp`, `uom`, `hsn`, `slno`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, `igst`, 
						`cvalue`, `svalue`, `ivalue`, `amount`, `product_id`, `invoice_id`, `shop`)";
			foreach($array as $product){
				$category=$product['category'];$company_id=$product['company_id'];$model=$product['model'];$hsn=$product['hsn'];
				$mrp=$product['mrp'];$uom=$product['uom'];$custdiscount=$product['custdiscount'];$charity=$product['charity'];
				$slno=$product['slno'];$quantity=$product['quantity'];$price=$product['price'];$discount=$product['discount'];
				$taxable=$product['taxable'];$cgst=$product['cgst'];$sgst=$product['sgst'];$igst=$product['igst'];
				$cvalue=$product['cvalue'];$svalue=$product['svalue'];$ivalue=$product['ivalue'];$amount=$product['amount'];
				$product_id=$product['ready_id'];
				$values2="('$category','$company_id','$model','$mrp','$uom','$hsn','$slno','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst','$igst',
						'$cvalue','$svalue','$ivalue','$amount','$product_id','$inv_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
				$table4="`stock_report`";
				$columns4="(`date`, `product_id`, `less`, `amount`, `remarks`, `shop`)";
				$values4="('$date','$product_id','$quantity','$amount','sale','$shop')";
				$run4=$insertObj->insert($table4,$columns4,$values4);
			}
			$table3="`customer_pay`";
			$columns3="(`date`, `customer_id`, `payment_mode`, `type`, `invoice_id`, `total_amount`, `paid`, `dues`, `shop`)";
			$values3="('$date','$customer_id','$payment_mode','$type','$inv_id','$total_amount','$cpaid','$dues','$shop')";
			$run3=$insertObj->insert($table3,$columns3,$values3);
			header("Location:../invoice/print_invoice.php?page=invoice&inv_id=$inv_id");	
		}
		else{
			$_SESSION['err']=$run;
			header("Location:../invoice?pagename=invoice");	
		}
		
	}
	elseif(isset($_POST['add_defective'])){
		$date=$_POST['date'];
		$invoice=$_POST['invoice'];
		$count=$_POST['count'];
		$shop=$_POST['shop'];
		$table="`returns`";
		$columns="(`product_id`, `category`, `company`, `model`, `hsn`, `defect`, `quantity`, `price`, `date`, `invoice_id`, `shop`)";
		for($i=0;$i<$count;$i++){
			$cat="category".$i;
			$pro_id="product_id".$i;
			$comp="company".$i;
			$mod="model".$i;
			$d="defect".$i;
			$r="return".$i;
			$p="price".$i;
			$h="hsn".$i;
			$category=$_POST[$cat];
			$product_id=$_POST[$pro_id];
			$company=$_POST[$comp];
			$model=$_POST[$mod];
			$defect=$_POST[$d];
			$return=$_POST[$r];
			$price=$_POST[$p];
			$hsn=$_POST[$h];
			if($return!=0){
				$check=$insertObj->get_count($table,"`product_id`='$product_id' and `invoice_id`='$invoice'");
				if($check==0){
					$values="('$product_id','$category','$company','$model','$hsn','$defect','$return','$price','$date','$invoice','$shop') ";
					$run=$insertObj->insert($table,$columns,$values);
				}
				else{$run="Product already returned";}
				//$update=$insertObj->update("`stock`","`quantity`=`quantity`+'$return'","`id`='$product_id'");
				//$update2=$insertObj->update("`sales`","`quantity`=`quantity`-'$return'","`ready_id`='$product_id' and `invoice_id`='$invoice'");
			}
		}
		
		if($run===true){
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../returns/print.php?inv_id=$invoice");	
		
	}
	elseif(isset($_POST['add_stock'])){
		$date=$_POST['date'];
		$category=addslashes($_POST['category']);
		$company_id=$_POST['company_id'];
		$model=$_POST['model'];
		$mrp=$_POST['mrp'];
		$uom=$_POST['uom'];
		$hsn=$_POST['hsn'];
		$slno=$_POST['slno'];
		$quantity=$_POST['quantity'];
		$purchase=$_POST['purchase'];
		$base_price=$_POST['base_price'];
		$selling_price=$_POST['selling_price'];
		$description=$_POST['description'];
		$shop=$_POST['shop'];
		$table="`stock`";
		$where="`category`='$category' and `company_id`='$company_id' and `model`='$model' and `shop`='$shop'";
		$count=$insertObj->get_count($table,$where);
		if($count==0){
			$columns="(`date`, `category`, `company_id`, `model`, `mrp`, `uom`, `hsn`, `slno`, `quantity`, `purchase`, `base_price`, `selling_price`, `description`, `shop`)";
			$values="('$date','$category','$company_id','$model','$mrp','$uom','$hsn','$slno','$quantity','$purchase','$base_price','$selling_price','$description','$shop')";
			$run=$insertObj->insert($table,$columns,$values);
		}
		else{
			$run=$insertObj->update($table,"`quantity`=`quantity`+'$quantity',`mrp`='$mrp',`selling_price`='$selling_price'",$where);
		}
		if($run===true){
			$arr=$insertObj->get_last_row($table,"`id`",$where);
			$product_id=$arr['id'];
			$table2="`stock_report`";
			if($base_price!='' && $base_price!=0){
				$amount=$quantity*$base_price;
			}
			else{
				$amount=$quantity*$purchase;
			}
			$columns2="(`date`, `product_id`, `add`, `amount`, `remarks`, `shop`)";
			$values2="('$date','$product_id','$quantity','$amount','add','$shop')";
			$run2=$insertObj->insert($table2,$columns2,$values2);
			$_SESSION['msg']="Successfully Added!";
		
		}else{
			$_SESSION['err']=$run;
		}
		header("Location:../stock?pagename=stock");	
	}
	elseif(isset($_POST['qbutton']) && $_POST['qbutton']=='add'){
		$category=addslashes($_POST['category']);
		$company_id=$_POST['company_id'];
		$model=$_POST['model'];
		$hsn=$_POST['hsn'];
		$mrp=$_POST['mrp'];
		$slno=$_POST['slno'];
		$uom=$_POST['uom'];
		$shop=$_POST['shop'];
		$quantity=$_POST['quantity'];
		$price=$_POST['price'];
		$charity=$_POST['charity'];
		$discount=$_POST['discount'];
		$custdiscount=$_POST['custdiscount'];
		/*$amount=$price*$quantity;
		$taxable=$amount-$discount;
		$taxable-=($custdiscount*$taxable)/100;*/
		if(isset($_POST['gstval'])){$gst=strip_tags($_POST['gstval']);}else{$gst='';}
		$cat_details=$insertObj->get_details("`category`","*","`id`='$category' and `shop`='$shop'");
		$tax=$_POST['tax'];
		$cgst=$sgst=$igst=$cvalue=$svalue=$ivalue=0;
		if($tax=='include'){
			$amount=$price;
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($cgst+$sgst)-$custdiscount*($cgst+$sgst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
			elseif($gst=='igst'){
				$igst=$cat_details['igst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($igst)-$custdiscount*($igst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
		}
		else{
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
			}elseif($gst=='igst'){
				$igst=$cat_details['igst'];
			}
		}
		
		$amount=$price*$quantity;
		$taxable=$amount+$charity;
		$taxable=$taxable-$discount;
		$taxable-=($custdiscount*$taxable)/100;
		if($gst=='cgst'){
			$cvalue=($taxable*$cgst)/100;
			$svalue=($taxable*$sgst)/100;
		}elseif($gst=='igst'){
			$igst=$cat_details['igst'];
			$ivalue=($taxable*$igst)/100;
		}
		$price=twoDigits($price);
		$taxable=twoDigits($taxable);
		$cvalue=twoDigits($cvalue);
		$svalue=twoDigits($svalue);
		$ivalue=twoDigits($ivalue);
		$amount=$taxable+$cvalue+$svalue+$ivalue;
		$amount=twoDigits($amount);
		
		$select=$insertObj->get_details("`stock`","`id`,`quantity`","`category`='$category' and `company_id`='$company_id' and `model`='$model' and `shop`='$shop'");
		$ready_id=$select['id'];
		$columns="(`category`, `company_id`, `model`, `mrp`, `slno`, `uom`, `hsn`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, 
				`igst`, `cvalue`, `svalue`, `ivalue`, `amount`, `ready_id`,`shop`)";
		$values="('$category','$company_id','$model','$mrp','$slno','$uom','$hsn','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst',
				'$igst','$cvalue','$svalue','$ivalue','$amount','$ready_id','$shop')";
	
		$run=$insertObj->insert("`quot_temp`",$columns,$values);
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}
	elseif(isset($_POST['save_quotation'])){
	    $billing_mode=$_POST['billing_mode'];
		$date=$_POST['date'];
		$shop=$_POST['shop'];
	    $type=$_POST['type'];
	    $reverse=$_POST['reverse'];
	    $payment_mode=$_POST['payment_mode'];
		$cheque_no=$_POST['cheque_no'];
		$cheque_date=$_POST['cheque_date'];
		$bank=$_POST['bank'];
	    $customer_id=$_POST['customer_id'];
	    $customer_name=$_POST['customer_name'];
	    $customer_mobile=$_POST['customer_mobile'];
	    $add_from=$_POST['add_from'];
	    $add_to=$_POST['add_to'];
	    $gst=$_POST['gst'];
	    $transport_mode=$_POST['transport_mode'];
	    $state=$_POST['state'];
	    $code=$_POST['code'];
	    $gross_amount=$_POST['gross_amount'];
	    $transport=$_POST['transport'];
	    $roundoff=$_POST['round'];
	    $total_amount=$_POST['total_amount'];
	    $advance=$_POST['advance'];
	    $paid=$cpaid=$_POST['paid'];
	    $dues=$_POST['dues'];
	    $add_terms=$cpaid=$_POST['add_terms'];
	    $terms=$cpaid=$_POST['terms'];
		if(isset($_POST['check_advance'])){ 
			$check_advance=$_POST['check_advance']; 
			$cpaid=$paid+$advance;
			$advance-=$total_amount;
			if($cpaid>$total_amount){$cpaid=$total_amount;}
		}
		else{ $check_advance=''; }
		if($advance<0){$advance=0;}
	    if($paid>$total_amount){
	    	$paid=$total_amount;
	    }
		if($dues<1 ){
			$dues=0;
		}
		
		
	    $next_payment=$_POST['next_payment'];
		$year=date('Y',strtotime($date));
		$closing="$year-03-31";
		if(date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($closing)))
		{
			$year1=date('y',strtotime("$date -1 year"));	
			$year2=date('y',strtotime($date));
		}
		else{
			$year1=date('y',strtotime($date));
			$year2=date('y',strtotime("$date +1 year"));	
		}
		$shop_details=$insertObj->get_details("`shop`","`abv`","`id`='$shop'");
		$prefix=$shop_details['abv']."/QUO/";
		$prefix.=$year1."-".$year2."/";
		$inv_no=$insertObj->get_details("`quotation`","max(`invoice_no`) as invoice_no","`prefix`='$prefix'");
		$invoice_no=$inv_no['invoice_no'];
		if($invoice_no==NULL){
			$invoice_no=1;
		}
		else{
			$invoice_no++;	
		}
		$table="`quotation`";
		$columns="(`prefix`, `billing_mode`, `reverse`, `date`, `type`, `payment_mode`, `cheque_date`, `cheque_no`, `bank`, `customer_id`, `customer_name`, `customer_mobile`, `gst`,  `transport_mode`,  `state`,  `code`, 
					`add_from`, `add_to`, `gross_amount`, `transport`, `roundoff`, `total_amount`, `paid`, `dues`, `add_terms`, `terms`, `next_payment`, `shop`)";
		$values="('$prefix','$billing_mode','$reverse','$date','$type','$payment_mode','$cheque_date','$cheque_no','$bank','$customer_id','$customer_name','$customer_mobile','$gst','$transport_mode','$state','$code',
					'$add_from','$add_to','$gross_amount','$transport','$roundoff','$total_amount','$cpaid','$dues','$add_terms','$terms','$next_payment','$shop')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$select=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$quot_id=$select['id'];
			$update=$insertObj->update($table,"`invoice_no`='$invoice_no'","`id`='$quot_id'");
			$array=$insertObj->get_rows("`quot_temp`","*","`shop`='$shop'");
			$delete=$insertObj->delete("`quot_temp`","`shop`='$shop'");
			$table2="`quot_list`";
			$columns2="(`category`, `company_id`, `model`,`mrp`, `uom`, `hsn`, `slno`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, `igst`, 
						`cvalue`, `svalue`, `ivalue`, `amount`, `product_id`, `quot_id`, `shop`)";
			foreach($array as $product){
				$category=$product['category'];$company_id=$product['company_id'];$model=$product['model'];$hsn=$product['hsn'];
				$mrp=$product['mrp'];$uom=$product['uom'];$custdiscount=$product['custdiscount'];$charity=$product['charity'];
				$slno=$product['slno'];$quantity=$product['quantity'];$price=$product['price'];$discount=$product['discount'];
				$taxable=$product['taxable'];$cgst=$product['cgst'];$sgst=$product['sgst'];$igst=$product['igst'];
				$cvalue=$product['cvalue'];$svalue=$product['svalue'];$ivalue=$product['ivalue'];$amount=$product['amount'];
				$product_id=$product['ready_id'];
				$values2="('$category','$company_id','$model','$mrp','$uom','$hsn','$slno','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst','$igst',
						'$cvalue','$svalue','$ivalue','$amount','$product_id','$quot_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
			}
			header("Location:../invoice/print_quotation.php?page=invoice&quot_id=$quot_id");	
		}
		else{
			$_SESSION['err']=$run;
			header("Location:../invoice?pagename=invoice");	
		}
		
	}
	elseif(isset($_POST['pbutton']) && $_POST['pbutton']=='add'){
		$category=addslashes($_POST['category']);
		$company_id=$_POST['company_id'];
		$model=$_POST['model'];
		$hsn=$_POST['hsn'];
		$mrp=$_POST['mrp'];
		$slno=$_POST['slno'];
		$uom=$_POST['uom'];
		$shop=$_POST['shop'];
		$quantity=$_POST['quantity'];
		$price=$_POST['price'];
		$charity=$_POST['charity'];
		$discount=$_POST['discount'];
		$custdiscount=$_POST['custdiscount'];
		/*$amount=$price*$quantity;
		$taxable=$amount-$discount;
		$taxable-=($custdiscount*$taxable)/100;*/
		if(isset($_POST['gstval'])){$gst=strip_tags($_POST['gstval']);}else{$gst='';}
		$cat_details=$insertObj->get_details("`category`","*","`id`='$category' and `shop`='$shop'");
		$tax=$_POST['tax'];
		$cgst=$sgst=$igst=$cvalue=$svalue=$ivalue=0;
		if($tax=='include'){
			$amount=$price;
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($cgst+$sgst)-$custdiscount*($cgst+$sgst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
			elseif($gst=='igst'){
				$igst=$cat_details['igst'];
				$price=(10000*$amount)/(10000-100*$custdiscount+100*($igst)-$custdiscount*($igst));
				$price-=($charity)/$quantity;
				$price+=$discount/$quantity;
			}
		}
		else{
			if($gst=='cgst'){
				$cgst=$cat_details['cgst'];
				$sgst=$cat_details['sgst'];
			}elseif($gst=='igst'){
				$igst=$cat_details['igst'];
			}
		}
		
		$amount=$price*$quantity;
		$taxable=$amount+$charity;
		$taxable=$taxable-$discount;
		$taxable-=($custdiscount*$taxable)/100;
		if($gst=='cgst'){
			$cvalue=($taxable*$cgst)/100;
			$svalue=($taxable*$sgst)/100;
		}elseif($gst=='igst'){
			$igst=$cat_details['igst'];
			$ivalue=($taxable*$igst)/100;
		}
		$price=twoDigits($price);
		$taxable=twoDigits($taxable);
		$cvalue=twoDigits($cvalue);
		$svalue=twoDigits($svalue);
		$ivalue=twoDigits($ivalue);
		$amount=$taxable+$cvalue+$svalue+$ivalue;
		$amount=twoDigits($amount);
		
		$select=$insertObj->get_details("`stock`","`id`,`quantity`","`category`='$category' and `company_id`='$company_id' and `model`='$model' and `shop`='$shop'");
		$ready_id=$select['id'];
		$columns="(`category`, `company_id`, `model`, `mrp`, `slno`, `uom`, `hsn`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, 
				`igst`, `cvalue`, `svalue`, `ivalue`, `amount`, `ready_id`,`shop`)";
		$values="('$category','$company_id','$model','$mrp','$slno','$uom','$hsn','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst',
				'$igst','$cvalue','$svalue','$ivalue','$amount','$ready_id','$shop')";
	
		$run=$insertObj->insert("`performa_temp`",$columns,$values);
		if($run===true){
			echo "Added Successfully!";	
		}
		else{
			echo $run;	
		}
		
	}
	elseif(isset($_POST['save_performa_invoice'])){
	    $billing_mode=$_POST['billing_mode'];
		$date=$_POST['date'];
		$shop=$_POST['shop'];
	    $type=$_POST['type'];
	    $reverse=$_POST['reverse'];
	    $payment_mode=$_POST['payment_mode'];
		$cheque_no=$_POST['cheque_no'];
		$cheque_date=$_POST['cheque_date'];
		$bank=$_POST['bank'];
	    $customer_id=$_POST['customer_id'];
	    $customer_name=$_POST['customer_name'];
	    $customer_mobile=$_POST['customer_mobile'];
	    $add_from=$_POST['add_from'];
	    $add_to=$_POST['add_to'];
	    $gst=$_POST['gst'];
	    $transport_mode=$_POST['transport_mode'];
	    $state=$_POST['state'];
	    $code=$_POST['code'];
	    $gross_amount=$_POST['gross_amount'];
	    $transport=$_POST['transport'];
	    $roundoff=$_POST['round'];
	    $total_amount=$_POST['total_amount'];
	    $advance=$_POST['advance'];
	    $paid=$cpaid=$_POST['paid'];
	    $dues=$_POST['dues'];
		if(isset($_POST['check_advance'])){ 
			$check_advance=$_POST['check_advance']; 
			$cpaid=$paid+$advance;
			$advance-=$total_amount;
			if($cpaid>$total_amount){$cpaid=$total_amount;}
		}
		else{ $check_advance=''; }
		if($advance<0){$advance=0;}
	    if($paid>$total_amount){
	    	$paid=$total_amount;
	    }
		if($dues<1 ){
			$dues=0;
		}
		
		
	    $next_payment=$_POST['next_payment'];
		$year=date('Y',strtotime($date));
		$closing="$year-03-31";
		if(date('Y-m-d',strtotime($date))<=date('Y-m-d',strtotime($closing)))
		{
			$year1=date('y',strtotime("$date -1 year"));	
			$year2=date('y',strtotime($date));
		}
		else{
			$year1=date('y',strtotime($date));
			$year2=date('y',strtotime("$date +1 year"));	
		}
		$shop_details=$insertObj->get_details("`shop`","`abv`","`id`='$shop'");
		$prefix=$shop_details['abv']."/PINV/";
		$prefix.=$year1."-".$year2."/";
		$inv_no=$insertObj->get_details("`performa_invoice`","max(`invoice_no`) as invoice_no","`prefix`='$prefix'");
		$invoice_no=$inv_no['invoice_no'];
		if($invoice_no==NULL){
			$invoice_no=1;
		}
		else{
			$invoice_no++;	
		}
		$table="`performa_invoice`";
		$columns="(`prefix`, `billing_mode`, `reverse`, `date`, `type`, `payment_mode`, `cheque_date`, `cheque_no`, `bank`, `customer_id`, `customer_name`, `customer_mobile`, `gst`,  `transport_mode`,  `state`,  `code`, 
					`add_from`, `add_to`, `gross_amount`, `transport`, `roundoff`, `total_amount`, `paid`, `dues`, `next_payment`, `shop`)";
		$values="('$prefix','$billing_mode','$reverse','$date','$type','$payment_mode','$cheque_date','$cheque_no','$bank','$customer_id','$customer_name','$customer_mobile','$gst','$transport_mode','$state','$code',
					'$add_from','$add_to','$gross_amount','$transport','$roundoff','$total_amount','$cpaid','$dues','$next_payment','$shop')";
		$run=$insertObj->insert($table,$columns,$values);
		if($run===true){
			$select=$insertObj->get_last_row($table,"`id`","`shop`='$shop'");
			$inv_id=$select['id'];
			$update=$insertObj->update($table,"`invoice_no`='$invoice_no'","`id`='$inv_id'");
			$array=$insertObj->get_rows("`performa_temp`","*","`shop`='$shop'");
			$delete=$insertObj->delete("`performa_temp`","`shop`='$shop'");
			$table2="`performa_list`";
			$columns2="(`category`, `company_id`, `model`,`mrp`, `uom`, `hsn`, `slno`, `quantity`, `price`, `charity`, `discount`, `custdiscount`, `taxable`, `cgst`, `sgst`, `igst`, 
						`cvalue`, `svalue`, `ivalue`, `amount`, `product_id`, `invoice_id`, `shop`)";
			foreach($array as $product){
				$category=$product['category'];$company_id=$product['company_id'];$model=$product['model'];$hsn=$product['hsn'];
				$mrp=$product['mrp'];$uom=$product['uom'];$custdiscount=$product['custdiscount'];$charity=$product['charity'];
				$slno=$product['slno'];$quantity=$product['quantity'];$price=$product['price'];$discount=$product['discount'];
				$taxable=$product['taxable'];$cgst=$product['cgst'];$sgst=$product['sgst'];$igst=$product['igst'];
				$cvalue=$product['cvalue'];$svalue=$product['svalue'];$ivalue=$product['ivalue'];$amount=$product['amount'];
				$product_id=$product['ready_id'];
				$values2="('$category','$company_id','$model','$mrp','$uom','$hsn','$slno','$quantity','$price','$charity','$discount','$custdiscount','$taxable','$cgst','$sgst','$igst',
						'$cvalue','$svalue','$ivalue','$amount','$product_id','$inv_id','$shop')";
				$run2=$insertObj->insert($table2,$columns2,$values2);
			}
			header("Location:../invoice/print_performa.php?page=invoice&inv_id=$inv_id");	
		}
		else{
			$_SESSION['err']=$run;
			header("Location:../invoice/performa.php?pagename=invoice");	
		}
		
	}
elseif(isset($_POST['add_additionalterms'])){
	$terms=$_POST['terms'];
	$table="`add_terms`";
	$run="";
	$columns="(`terms`)";
	$values=array();
	foreach($terms as $term){
		$values[]="('$term')";
	}
	$values=implode(',',$values);
	$run=$insertObj->insert($table,$columns,$values);
	if($run===true){
		$_SESSION['msg']="Successfully Added!";	
	}else{
		$_SESSION['err'].=$run;
	}
	header("Location:../masterkey/?pagename=master");	
}
	else{
		header("Location:../home.php?pagename=home");	
	}
?>