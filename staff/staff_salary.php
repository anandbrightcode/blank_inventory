<?php
session_start();
  if(isset($_SESSION['user'])){
	   	$role=$_SESSION['role'];
	   	$user=$_SESSION['user'];
	   	$shop=$_SESSION['shop'];
  }
  else{
	   header("Location:../index.php");
	   echo "<script>location='../index.php'</script>";
	 
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Staff</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
	</head>

    <body style="background-color:#F6F6F6">
    <?php include "../header.php";?>
<div class="container">
   <div class="row" style="height:20px;">
     <div class="col-md-12">
       <div id="msg">
                 <div class="text-center text-success" style="font-size:18px;">
					   <?php if(isset($_GET['success'])){
                           echo $msg= $_GET['success'];
                           echo " <i class='fa fa-check'></i>";
                           
                       }?>
                    </div>
                    <div class="text-center text-danger" style="font-size:18px;">
                       <?php if(isset($_GET['err'])){
                           echo "Error:".$_GET['err'];
						   echo " <i class='fa fa-times'></i>";
                       }?>
                    </div>
                </div><!-- msg closed -->     
     </div><!-- end of column for message -->
   </div><!-- end of row for message -->
  <div class="row">
    <div class="col-md-2">
        <div style="margin-top:50px;">
            <a href="../staff?pagename=staff" class="btn btn-success" style="width:130px;">Add Staff</a><br /><br />
            <a href="staff_details.php?pagename=staff" class="btn btn-warning" style="width:130px;">Staff Details</a><br /><br />
            <a href="staff_salary.php?pagename=staff" class="btn btn-info"  style="width:130px;">Staff Salary</a><br /><br />
            <a href="salary_report.php?pagename=staff" class="btn btn-primary"  style="width:130px;">Salary Report</a><br />
        </div>
    </div><!-- end of sidebar column-->
    <div class="col-md-10">
         <div class="row">
           <div class="col-md-12">
              <span class="text-center text-danger"><h2><i class="fa fa-file-text-o"></i> Staff Salary</h2></span>
           </div><!--end of column for staff_salary -->
         </div><!-- end of row for heading staff_salary -->
         <hr />
         <form action="addstaff.php" method="post" >
         	<div class="row" style="border:1px solid #c6c6c6; border-radius:5px;">
            	<div class="col-md-12"> 
                	<br />
                    <div class="row">
                    	<div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4"><b>Enter Staff Id :</b></div>
                                <div class="col-md-8"><input type="text" id="staff_id" name="staff_id" class="form-control" required="required"/></div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-4"><b>Name of Staff :</b></div>
                                <div class="col-md-8"><input type="text" id="staff_name" name="staff_name" class="form-control"/></div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-4"><b>Mobile No. :</b></div>
                                <div class="col-md-8"><input type="number" id="staff_mobile_no" name="staff_mobile_no" class="form-control"/></div>
                            </div>
                        </div><!--end of left details-->
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                        	<img id="photo" style="height:180px; width:160px;" />
                        </div><!--end of right image-->
                    </div><!--end of top details row-->
                	<br />
                    <div class="row">
                        <div class="col-md-2"><b>Designation :</b></div>
                        <div class="col-md-4"><input type="text" id="designation" name="designation" class="form-control"/></div>
                        <div class="col-md-2"><b>Company PF No. :</b></div>
                        <div class="col-md-4"><input type="text" id="company_pf_no" name="company_pf_no" class="form-control"/></div>
                    </div>
                	<br />
                    <div class="row">
                        <div class="col-md-2"><b>Location :</b></div>
                        <div class="col-md-4"><input type="text" id="staff_location" name="staff_location" class="form-control"/></div>
                        <div class="col-md-2"><b>PF Account No. :</b></div>
                        <div class="col-md-4"><input type="text" id="pf_ac_no" name="pf_ac_no" class="form-control"/></div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-2"><b>Date of Joining :</b></div>
                        <div class="col-md-4"><input type="date" id="date_of_joining" name="date_of_joining" class="form-control"/></div>
                        <div class="col-md-2"><b>Income Tax No. :</b></div>
                        <div class="col-md-4"><input type="text" id="income_tax_no" name="income_tax_no" class="form-control"/></div>
                    </div>
                	<br />
                </div>
            </div><!--end of frst row-->
            <br />
            <div class="row">
            	<div class="col-md-6" style="border:1px solid #c6c6c6; border-radius:5px;">
                	<br />
                	<div class="row">
                        <div class="col-md-5"><b>Earning :</b></div>
					</div>
                	<br /><br />
                	<div class="row">
                        <div class="col-md-5"><b>1. Basic Salary :</b></div>
                        <div class="col-md-7"><input type="number" id="basic_salary" name="basic_salary" class="calc_payment form-control"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>2. HRA :</b></div>
                        <div class="col-md-7"><input type="text" name="hra" id="hra" class="calc_payment form-control" onblur="resethra(this.value)"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>3. Medical Allowance :</b></div>
                        <div class="col-md-7"><input type="text" id="medical_allowance"  name="medical_allowance"  class="calc_payment form-control" onblur="resetma(this.value)"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>4. Conv Allowance :</b></div>
                        <div class="col-md-7"><input type="text" id="conv_allowance"  name="conv_allowance" class="calc_payment form-control" onblur="resetca(this.value)"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>5. Special Allowance :</b></div>
                        <div class="col-md-7"><input type="text" id="special_allowance" name="special_allowance" class="calc_payment form-control" onblur="resetsa(this.vaue)"/></div>
					</div>
                    <br />
                </div>
            	<div class="col-md-5" style="border:1px solid #c6c6c6; border-radius:5px; margin-left:70px;">
                	<br />
                	<div class="row">
                        <div class="col-md-5"><b>Deduction :</b></div>
					</div>
                	<br />
                	<div class="row">
                        <div class="col-md-3"><b>EPF :</b></div>
                        <div class="col-md-9"><input type="number" id="epf" name="epf" class="calc_payment form-control"  onblur="resetepf(this.value)"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-3"><b>ESIC :</b></div>
                        <div class="col-md-9"><input type="number" id="esic" name="esic"  class="calc_payment form-control" onblur="resetesic(this.vaue)"/></div>
					</div><br />
                </div>
            	<div class="col-md-5" style="border:1px solid #c6c6c6; border-radius:5px; margin-top:12px; margin-left:70px;">
                	<br />
                	<div class="row">
                        <div class="col-md-5"><b>Attendance :</b></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>Deducted Leave :</b></div>
                        <div class="col-md-7"><input type="number" id="deducted_leave" name="deducted_leave"  class="calc_payment form-control" onblur="resetdl(this.vaue)"/></div>
					</div><br />
                	<div class="row">
                        <div class="col-md-5"><b>Paid Attendance :</b></div>
                        <div class="col-md-7"><input type="number" id="paid_attendence" name="paid_attendence"  class="calc_payment form-control" onblur="resetpa(this.vaue)"/></div>
					</div><br />
                </div>
            </div><!--end of second row-->
            <div class="row">
            	<br />
            	<div class="row">
                    <div class="col-md-2"><b>Total Earning :</b></div>
                    <div class="col-md-4"><input type="number" name="total_earning" id="total_earning" class="form-control"/></div>
                    <div class="col-md-2"><b>Total Deduction :</b></div>
                    <div class="col-md-4"><input type="number" name="total_deduction" id="total_deduction" class="form-control"/></div>
                </div>
                <br />
            	<div class="row">
                    <div class="col-md-2"><b>Salary :</b></div>
                    <div class="col-md-4"><input type="number" name="salary" id="salary" class="form-control"/></div>
                    <div class="col-md-2"><b>Net Payment :</b></div>
                    <div class="col-md-4">
                        <input type="number" name="net_payment" id="net_payment" class="form-control"/>
                        <input type="hidden" name="payid" id="payid" class="form-control"/>
                    </div>
                </div><br />
            </div><!--end of third row-->
            <br />
            <div class="row">
            	<div class="col-md-5"></div>
                <div class="col-md-2" id="updiv">
                    <input type="submit" name="update" id="update" value="Update" class="btn btn-warning"  />
                </div>
                <div class="col-md-3">
                    <div id="pay"><input type="submit" name="pay" value="Pay & Print" class="btn btn-lg btn-danger fa fa-print" /></div>
                    <button type="button" id="print" class="btn btn-lg btn-danger fa fa-print" style="display:none" onclick="printThis()">Print</button>
                </div>
            </div><!--end of submit row-->
            <br />
         </form>
	</div><!-- end of right column-->
   </div><!-- end of row-->
 </div><!-- end of container-->
 
 
 <script type="text/javascript" language="javascript"> 
  $(function () {
    $('#staff_id').keyup(function () {
	  id=$(this).val();
	//  alert(id);
	if(isNaN(id) || id==""){
		//alert('1')
		$('#updiv').show();
		$('#pay').show();
		$('#print').hide();
		$('#staff_id').val("");
		$('#staff_name').val("");
                $('#company_pf_no').val("");
				$('#pf_ac_no').val("");
				$('#income_tax_no').val("");
				
				$('#designation').val("");
				$('#staff_location').val("");
				$('#date_of_joining').val("");
				$('#staff_mobile_no').val("");
				$('#basic_salary').val("");
				$('#hra').val("");
				$('#medical_allowance').val("");
				$('#conv_allowance').val("");
				$('#special_allowance').val("");
				$('#epf').val("");
				$('#esic').val("");
				$('#total_earning').val("");
				$('#total_deduction').val("");
				$('#deducted_leave').val("");
				$('#paid_attendence').val("");
				$('#salary').val("");
				$('#net_payment').val("");
				$('#photo').attr('src',"");
		
		}
        $.ajax({
            type: 'POST',
            url: 'staff_json.php',
            data: {
                id: $(this).val()
            },
            dataType: 'json',
            success: function (data) //on recieve of reply
            {
                var name = data['staff_name']; 
				var com_pf_no = data['com_pf_no']; 
				var pf_acco_no = data['pf_acco_no']; 
				var income_tax_no = data['income_tax_no']; 
				
				var designation = data['designation']; 
				var city = data['city']; 
				var doj = data['doj']; 
				var staff_mobile = data['staff_mobile']; 
				var basic_salary = data['basic_salary']; 
				var hra = data['hra']; 
				var medical_allowance = data['medical_allowance']; 
				var conveyance_allowance = data['conveyance_allowance']; 
				var special_allowance = data['special_allowance'];
				var epf= data['pf'];
				var esic=data['esic'];
				var total_earning=data['total_earning'];
				var total_deduction=data['total_deduction'];
				var deducted_leave=data['deducted_leave'];
				var paid_attendence=data['paid_attendence'];
				var salary=data['salary'];
				var net_payment=data['net_payment'];
				var photo=data['photo'];
				var pay=data['payId'];
				//alert(pay)
				//alert(photo);
				if(name!=''){
					$('#staff_name').val(name);
					$('#company_pf_no').val(com_pf_no);
					$('#pf_ac_no').val(pf_acco_no);
					$('#income_tax_no').val(income_tax_no);
					
					$('#designation').val(designation);
					$('#staff_location').val(city);
					$('#date_of_joining').val(doj);
					$('#staff_mobile_no').val(staff_mobile);
					$('#basic_salary').val(basic_salary);
					$('#hra').val(hra);
					$('#medical_allowance').val(medical_allowance);
					$('#conv_allowance').val(conveyance_allowance);
					$('#special_allowance').val(special_allowance);
					$('#epf').val(epf);
					$('#esic').val(esic);
					$('#total_earning').val(total_earning);
					$('#total_deduction').val(total_deduction);
					$('#deducted_leave').val(deducted_leave);
					$('#paid_attendence').val(paid_attendence);
					$('#salary').val(salary);
					$('#net_payment').val(net_payment);
					$('#photo').attr('src',""+photo);
					if(pay!='0'){
						$('#payid').val(pay);
						$('#pay').hide();
						$('#print').show();
						$('#updiv').hide();
					}
					else{
						$('#payid').val("");
						$('#updiv').show();
						$('#pay').show();
						$('#print').hide();
					}
				}else{
					$('#staff_name').val("");$('#company_pf_no').val("");$('#pf_ac_no').val("");$('#income_tax_no').val("");$('#designation').val("");$('#staff_location').val("");
					$('#date_of_joining').val("");$('#staff_mobile_no').val("");$('#basic_salary').val("");$('#hra').val("");$('#medical_allowance').val("");
					$('#conv_allowance').val("");$('#special_allowance').val("");$('#epf').val("");$('#esic').val("");$('#total_earning').val("");
					$('#total_deduction').val("");$('#deducted_leave').val("");$('#paid_attendence').val("");$('#salary').val("");$('#net_payment').val("");
					$('#photo').attr('src',"");$('#updiv').show();$('#pay').show();$('#print').hide();
				}
				//$('#photo').prepend('<img id="theImg" src="photo" />')
            }
        });
    });  
});  

     $(function () {

	   $('.calc_payment').keyup(function () {
	   var basic_salary= parseInt($("#basic_salary").val());
	   var hra=parseInt($("#hra").val());
	   var medical_allowance=parseInt($("#medical_allowance").val());
	   var conv_allowance=parseInt($("#conv_allowance").val());
	   var special_allowance=parseInt($("#special_allowance").val());
	   
	   var total_earning=basic_salary+hra+medical_allowance+conv_allowance+special_allowance;
	  
	   var epf= parseInt($("#epf").val());
	   var esic=parseInt($("#esic").val());
	  
	   var total_deduction=epf+esic;
	 
	   var deducted_leave= parseInt($("#deducted_leave").val());
	   var paid_attendence=parseInt($("#paid_attendence").val());
	  
	   var salary=total_earning-total_deduction;
	   var net_payment=salary+((salary/30)*paid_attendence-(salary/30)*deducted_leave);
       
	      if(isNaN(basic_salary)){
		    basic_salary=0;
			// $("#basic_salary").val(basic_salary);
		  }
	  
	     if(isNaN(hra)){
		    hra=0;
			//$("#hra").val(hra);
		  }
	   
	     if(isNaN(medical_allowance)){
		    medical_allowance=0;
			//$("#medical_allowance").val(medical_allowance);
		  }
		  
	     if(isNaN(conv_allowance)){
		    conv_allowance=0;
			//$("#conv_allowance").val(conv_allowance);
		  }
		  
	     if(isNaN(special_allowance)){
		    special_allowance=0;
			//$("#special_allowance").val(special_allowance);
		  }

	   
	  
	      if(isNaN(total_earning)){
		    total_earning=0;
			//$("#total_earning").val(total_earning);
		  }
		  
	      if(isNaN(epf)){
		    epf=0;
			//$("#epf").val(epf);
		  }
	  
	     if(isNaN(esic)){
		    esic=0;
		//	$("#esic").val(esic);
		  }
		  if(isNaN(total_deduction)){
		    total_deduction=0;
			//$("#total_deduction").val(total_deduction);
		  }
		   if(isNaN(deducted_leave)){
		    deducted_leave=0;
			//$("#deducted_leave").val(deducted_leave);
		  }
	      if(isNaN(paid_attendence)){
		    paid_attendence=0;
			//$("#paid_attendence").val(paid_attendence);
		  }
	  
	   if(deducted_leave>30 || deducted_leave<0){
		    alert("Please Enter Valid value");
			$("#net_payment").val("");
			$("#deducted_leave").val("0");
			
			
			//$("#deducted_leave").val(0);
		
		  }else{
	  $("#total_earning").val(total_earning);
	  $("#salary").val(salary);
	  $("#total_deduction").val(total_deduction);
	  net_payment=Math.round(net_payment);
	  $("#net_payment").val(net_payment);
		  }
	 });//end of .calcnet_payment
	   
	}); 
	
	
	function calcfun(){
		var basic_salary= parseInt($("#basic_salary").val());
	   var hra=parseInt($("#hra").val());
	   var medical_allowance=parseInt($("#medical_allowance").val());
	   var conv_allowance=parseInt($("#conv_allowance").val());
	   var special_allowance=parseInt($("#special_allowance").val());
	   
	   var total_earning=basic_salary+hra+medical_allowance+conv_allowance+special_allowance;
	  
	   var epf= parseInt($("#epf").val());
	   var esic=parseInt($("#esic").val());
	  
	   var total_deduction=epf+esic;
	 
	   var deducted_leave= parseInt($("#deducted_leave").val());
	   var paid_attendence=parseInt($("#paid_attendence").val());
	  
	   var salary=total_earning-total_deduction;
	   var net_payment=salary+((salary/30)*paid_attendence-(salary/30)*deducted_leave);
	   $("#total_earning").val(total_earning);
	  		$("#salary").val(salary);
	  		$("#total_deduction").val(total_deduction);
	  		$("#net_payment").val(net_payment);
	}
	
	
	function resethra(data){
		var rhra=data;
		
		if(rhra==""){
			$("#hra").val(0);
			calcfun();
			
		}
	}
	function resetma(data){
		var rma=data;
		
		if(rma==""){
			$("#medical_allowance").val(0);
			calcfun();
			
		}
	}
	function resetca(data){
		var rca=data;
		
		if(rca==""){
			$("#conv_allowance").val(0);
			calcfun();
			
		}
	}
	function resetsa(data){
		var rsa=data;
		
		if(rsa==""){
			$("#special_allowance").val(0);
			calcfun();
			
		}
	}
	function resetdl(data){
		var rdl=data;
		
		if(rdl==""){
			$("#deducted_leave").val(0);
			calcfun();
			
		}
	}
	function resetpa(data){
		var rpa=data;
		
		if(rpa==""){
			$("#paid_attendence").val(0);
			calcfun();
			
		}
	}
	function resetepf(data){
		var repf=data;
		
		if(repf==""){
			$("#epf").val(0);
			calcfun();
			
		}
	}
	function resetesic(data){
		var resic=data;
		
		if(resic==""){
			$("#esic").val(0);
			calcfun();
			
		}
	}
	
	function printThis(){
		var id=$('#payid').val();
		//alert(id)
		window.location="print_pay.php?id="+id+"&page=staff";	
	}
   
</script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   
</body>
</html>