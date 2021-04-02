<?php
 session_start();
 $shop=$_SESSION['shop'];
 include('connection.php');
 
 if(isset($_POST['submit']) && $_POST['submit']=='Save')
 {  
     $staff_id=$_POST['staff_id'];
	 $staff_name=$_POST['staff_name'];
	 $staff_father_name=$_POST['staff_father_name'];
	 $staff_age=$_POST['staff_age'];
	 $sex=$_POST['sex'];
	 $staff_address=$_POST['staff_address'];
	 $city=$_POST['city'];
	 $state=$_POST['state'];
	 $staff_mobile=$_POST['staff_mobile'];
	 $basic_salary=$_POST['basic_salary'];
	 $pf=$_POST['pf'];
	 $hra=$_POST['hra'];
	 $com_pf_no=$_POST['com_pf_no'];
	 $pf_acco_no=$_POST['pf_acco_no'];
	 $income_tax_no=$_POST['income_tax_no'];
	 $designation=$_POST['designation'];
	 $doj=$_POST['doj'];
     $file=$_FILES['photo']['name'];
	 $ext = end((explode(".", $file)));
	 $staff=explode(" ",$staff_name);
	 $path=$staff[0].substr(time(),5).".".$ext;
	 $temp=$_FILES['photo']['tmp_name'];
	 $folder='uploads/'.$path;
	 
	 $salary=($basic_salary+$hra)-$pf;
	     $netpayment=$salary;
		 $total_deduction=$pf;
		 $total_earning=$basic_salary+$hra;
	 

	
 $sql="insert into staff(staff_name,staff_father_name,staff_age,sex,staff_address,city,state,staff_mobile,basic_salary,salary,total_earning,net_payment,total_deduction,pf,
							hra,com_pf_no,pf_acco_no,income_tax_no,designation,doj,photo,shop)
							 values('$staff_name',
								    '$staff_father_name',
									'$staff_age',
									'$sex',
									'$staff_address',
									'$city',
									'$state',
									'$staff_mobile',
									'$basic_salary',
									'$salary',
									'$total_earning',
									'$netpayment',
									'$total_deduction',
									'$pf',
									'$hra',
									'$com_pf_no',
									'$pf_acco_no',
									'$income_tax_no',
									'$designation',
									'$doj',
									'$folder',
									'$shop')";
			$query=mysqli_query($link,$sql);	
			
			if($query)
			{
				move_uploaded_file($temp,$folder);
				$msg="Data added successfully";
		        header("Location:staff.php?success=$msg&pagename=staff");	
			}
			
	}
	else if(isset($_POST['update']) && $_POST['update']=='Update'){
		 $staff_id=$_POST['staff_id'];
		 $staff_name=$_POST['staff_name'];
		 $staff_mobile=$_POST['staff_mobile_no'];
		 $designation=$_POST['designation'];
		 $com_pf_no=$_POST['company_pf_no'];
		 $city=$_POST['staff_location'];
		 $pf_acco_no=$_POST['pf_ac_no'];
		 $doj=$_POST['date_of_joining'];
		 $income_tax_no=$_POST['income_tax_no'];
		 $basic_salary=$_POST['basic_salary'];
		 $hra=$_POST['hra'];
		 $medical_allowance=$_POST['medical_allowance'];
		 $conv_allowance=$_POST['conv_allowance'];
		 $special_allowance=$_POST['special_allowance'];
		 $deducted_leave=$_POST['deducted_leave'];
		 $paid_attendance=$_POST['paid_attendence'];
		 $pf=$_POST['epf'];
		 $esic=$_POST['esic'];
		 $total_earning=$_POST['total_earning'];
		 $total_deduction=$_POST['total_deduction'];
		 $salary=$_POST['salary'];
		 $net_payment=$_POST['net_payment'];
		 
		 $update="UPDATE `staff` SET `staff_id`='$staff_id',`staff_name`='$staff_name',
		 	`staff_mobile`='$staff_mobile',`city`='$city',
		 	`basic_salary`='$basic_salary',`pf`='$pf',
			`esic`='$esic',`hra`='$hra',`com_pf_no`='$com_pf_no',
			`pf_acco_no`='$pf_acco_no', `income_tax_no`='$income_tax_no',
			`medical_allowance`='$medical_allowance',`conveyance_allowance`='$conv_allowance',
		 	`special_allowance`='$special_allowance',`salary`='$salary',
			`total_earning`='$total_earning',`net_payment`='$net_payment',
		 	`total_deduction`='$total_deduction',`designation`='$designation',
			`doj`='$doj',`deducted_leave`='$deducted_leave',
		 	`paid_attendence`='$paid_attendance' where staff_id='$staff_id' and shop='$shop'";
		
		$rs=mysqli_query($link,$update);
		if($rs){
			 $msg="Data added successfully";
			 header("Location:staff_salary.php?success=$msg&pagename=staff");
			}
		else{
	     echo $msg=mysqli_error($link);
		 header("Location:staff_salary.php?err=$msg&pagename=staff");	
		}
	}
	elseif($_POST['pay']){ 
		$staff_id=$_POST['staff_id'];
		 $staff_name=$_POST['staff_name'];
		 $staff_mobile=$_POST['staff_mobile_no'];
		 $designation=$_POST['designation'];
		 $com_pf_no=$_POST['company_pf_no'];
		 $city=$_POST['staff_location'];
		 $pf_acco_no=$_POST['pf_ac_no'];
		 $doj=$_POST['date_of_joining'];
		 $income_tax_no=$_POST['income_tax_no'];
		 $basic_salary=$_POST['basic_salary'];
		 $hra=$_POST['hra'];
		 $medical_allowance=$_POST['medical_allowance'];
		 $conv_allowance=$_POST['conv_allowance'];
		 $special_allowance=$_POST['special_allowance'];
		 $deducted_leave=$_POST['deducted_leave'];
		 $paid_attendance=$_POST['paid_attendence'];
		 $pf=$_POST['epf'];
		 $esic=$_POST['esic'];
		 $total_earning=$_POST['total_earning'];
		 $total_deduction=$_POST['total_deduction'];
		 $salary=$_POST['salary'];
		 $net_payment=$_POST['net_payment'];
		 $today=date('Y-m-d'); 
		 
		 $update="UPDATE `staff` SET `staff_id`='$staff_id',`staff_name`='$staff_name',
		 	`staff_mobile`='$staff_mobile',`city`='$city',
		 	`basic_salary`='$basic_salary',`pf`='$pf',
			`esic`='$esic',`hra`='$hra',`com_pf_no`='$com_pf_no',
			`pf_acco_no`='$pf_acco_no', `income_tax_no`='$income_tax_no',
			`medical_allowance`='$medical_allowance',`conveyance_allowance`='$conv_allowance',
		 	`special_allowance`='$special_allowance',`salary`='$salary',
			`total_earning`='$total_earning',`net_payment`='$net_payment',
		 	`total_deduction`='$total_deduction',`designation`='$designation',
			`doj`='$doj',`deducted_leave`='$deducted_leave',
		 	`paid_attendence`='$paid_attendance' where staff_id='$staff_id' and shop='$shop'";
		
		$rs=mysqli_query($link,$update);
		$insert="INSERT INTO `salary`(`id`, `date`, `name`, `staff_id`, `basic`, `hra`, `medical`, `conveyance`, `special`, `deducted_leave`, `paid_attendance`, `epf`, `esic`, 
				`total`, `deduction`, `salary`, `net_payment`,`shop`) VALUES ('','$today','$staff_name','$staff_id','$basic_salary','$hra','$medical_allowance',
				'$conv_allowance','$special_allowance','$deducted_leave','$paid_attendance','$pf','$esic','$total_earning','$total_deduction','$salary','$net_payment','$shop')";
		$ri=mysqli_query($link,$insert);	
		if($ri){
			$sel=mysqli_fetch_array(mysqli_query($link,"select id from salary where staff_id='$staff_id' and date='$today' and `shop`='$shop'"));
			$id=$sel['id'];
			header("Location:print_pay.php?id=$id&page=staff");	
		}
	}
	
	else{
		 header("Location:staff.php?pagename=staff");	
	}
 