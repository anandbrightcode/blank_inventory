<?php
include('connection.php');

	  $nameOrId=$_GET['nameOrId'];
	 $type=$_GET['type'];
	// echo $type;
	    if($type=="s_det"){
		     $sql="SELECT staff_id,staff_name,staff_father_name,staff_age,sex,staff_address,city,state,staff_mobile,designation,doj from staff where staff_id='$nameOrId' or staff_name like '%$nameOrId%'"; 
	  $rs=mysqli_query($link,$sql);
	//?>
    				<br />
                     <div id="top_inputting_search_box" class="col-md-12 table-responsive">
								<table height="auto" border="1px" class="table table-striped table-bordered" >
                                <tr bgcolor="#00FF99">
                                  <th style="text-align:center">Staff Id</th>
                                  <th style="text-align:center">Staff Name</th>
                                   <th style="text-align:center">Age</th>
                                   <th style="text-align:center">Gender</th>
                                   <th style="text-align:center">Address</th>
                                   <th style="text-align:center">City </th>
                                  <th style="text-align:center">Mobile</th>
                                  <th style="text-align:center">Designation </th>
                                  <th style="text-align:center">Date Of Joining</th>
                                </tr>
                                
                              </div><!--end of top_inputing_part-->   
                   <?php
				     if(mysqli_num_rows($rs)>0){
				     while($result=mysqli_fetch_array($rs)){
					 ?>
                       <tr>
                         <td align="center"><?php echo $result['staff_id'];?></td>
                         <td align="center"><?php
						    echo str_ireplace($query, "<span style='color:red;'>$query</span>", $result['staff_name']);
						 ?></td>
                         <td align="center"><?php echo $result['staff_age'];?></td>
                         <td align="center"><?php echo $result['sex'];?></td>
                         <td align="center"><?php echo $result['staff_address'];?></td>
                         <td align="center"><?php echo $result['city'];?></td>
                         <td align="center"><?php echo $result['staff_mobile'];?></td>
                         <td align="center"><?php echo $result['designation'];?></td>
                          <td align="center"><?php if($result['doj']!='')echo date('d-m-Y',strtotime($result['doj']));?></td>
                       </tr>
                  
                     <?php
					 } //while closed
					 }//if closed
					 else{
					    ?>
                          <tr>
                          	<td colspan="9"><center><span class="text-danger">Sorry!! No Records Found</span></center></td>
                          </tr>
                        <?php
					 }
					 echo "</table>";
		                
		}
		else if($type=="sal_det"){
		 
     $sql="SELECT staff_id,staff_name,basic_salary,pf,hra,com_pf_no,pf_acco_no,income_tax_no,medical_allowance,conveyance_allowance,special_allowance,
      net_payment from staff where staff_id='$nameOrId' or staff_name like '%$nameOrId%'"; 
	  $rs=mysqli_query($link,$sql);
	//?>
    				<br />
                     <div id="top_inputting_search_box" class="col-md-12 table-responsive">
								<table height="auto" border="1px" class="table table-stripped table-bordered">
                                <tr>
                                  <th style="text-align:center">Staff Id</th>
                                  <th style="text-align:center">Staff Name</th>
                                   <th style="text-align:center">Basic Salary</th>
                                   <th style="text-align:center">PF</th>
                                   <th style="text-align:center">HRA</th>
                                   <th style="text-align:center">Medical Allowance</th>
                                  <th style="text-align:center">Conveyance Allowance</th>
                                  <th style="text-align:center">Special Allowance</th>
                                  <th style="text-align:center">Net Payment</th>
                                  <th style="text-align:center">Company PF Num</th>
                                  <th style="text-align:center">PF Account Num</th>
                                  <th style="text-align:center">Income Tax No</th>
                                </tr>
                                
                              </div><!--end of top_inputing_part-->   
                   <?php
				    if(mysqli_num_rows($rs)>0){
				     while($result=mysqli_fetch_array($rs)){
					 ?>
                       <tr>
                         <td align="center"><?php echo $result['staff_id'];?></td>
                         <td align="center"><?php
						    echo str_ireplace($query, "<span style='color:red;'>$query</span>", $result['staff_name']);
						 ?></td>
                         <td align="center"><?php echo $result['basic_salary'];?></td>
                         <td align="center"><?php echo $result['pf'];?></td>
                         <td align="center"><?php echo $result['hra'];?></td>
                         <td align="center"><?php echo $result['medical_allowance'];?></td>
                         <td align="center"><?php echo $result['conveyance_allowance'];?></td>
                         <td align="center"><?php echo $result['special_allowance'];?></td>
                          <td align="center"><?php echo $result['net_payment'];?></td>
                         <td align="center"><?php echo $result['com_pf_no'];?></td>
                         <td align="center"><?php echo $result['pf_acco_no'];?></td>
                         <td align="center"><?php echo $result['income_tax_no'];?></td>
                       </tr>
                  
                     <?php
					 } //while closed
				}//if closed
				else{
					    ?>
                          <tr>
                          	<td colspan="12"><center><span class="text-danger">Sorry!! No Records Found</span></center></td>
                          </tr>
                        <?php
					 }
					 
					 echo "</table>";
		                
		}//else if closed
	    
	
	?>