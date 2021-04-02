<?php
include('connection.php');
?>
<div class="table-responsive">
	<table class="table table-striped">
    	<tr>
        	<th style="text-align:center">Sl No</th>
        	<th style="text-align:center">Date</th>
        	<th style="text-align:center">Name</th>
        	<th style="text-align:center">Basic Salary</th>
        	<th style="text-align:center">HRA</th>
        	<th style="text-align:center">Medical Allowance</th>
        	<th style="text-align:center">Conveyance Allowance</th>
        	<th style="text-align:center">Special Allowance</th>
        	<th style="text-align:center">Total Earning</th>
        	<th style="text-align:center">EPF</th>
        	<th style="text-align:center">ESIC</th>
        	<th style="text-align:center">Total Deduction</th>
        	<th style="text-align:center">Salary</th>
        	<th style="text-align:center">Deducted Leave</th>
        	<th style="text-align:center">Paid Attendance</th>
        	<th style="text-align:center">Net Payment</th>
        </tr>
 		<?php
        $count=15;
		$offset=0;
		if(isset($_GET['page'])){
			$page=$_GET['page'];
		}
		else{
		  	$page=1;
		}
		$offset=($page-1)*$count;
		if(isset($_GET['name']) && $_GET['name']!=""){
			$name=$_GET['name'];
			$sql="Select * from salary where name like '%$name%' order by id limit $offset,$count";
			$sql2="SELECT count(id) as count from salary where name like '%$name%'";
		}
		else{
			$sql="Select * from salary order by id limit $offset,$count";
			$sql2="SELECT count(id) as count from salary";
		}
		
		$rowcount=mysqli_query($link,$sql2);
		$data= mysqli_fetch_assoc($rowcount);
		$rownum= $data['count'];
		$pages= $rownum/$count;
		$rs=mysqli_query($link,$sql);
		$i=$offset;
		if(mysqli_num_rows($rs)){
			while($data=mysqli_fetch_array($rs)){
				$i++;
		?>
        <tr>
        	<td align="center"><?php echo $i; ?></td>
        	<td align="center"><?php if($data['date']!='')echo date('d-m-Y',strtotime($data['date'])); ?></td>
        	<td align="center"><?php echo $data['name']; ?></td>
        	<td align="center"><?php echo $data['basic']; ?></td>
        	<td align="center"><?php echo $data['hra']; ?></td>
        	<td align="center"><?php echo $data['medical']; ?></td>
        	<td align="center"><?php echo $data['conveyance']; ?></td>
        	<td align="center"><?php echo $data['special']; ?></td>
        	<td align="center"><?php echo $data['total']; ?></td>
        	<td align="center"><?php echo $data['epf']; ?></td>
        	<td align="center"><?php echo $data['esic']; ?></td>
        	<td align="center"><?php echo $data['deduction']; ?></td>
        	<td align="center"><?php echo $data['salary']; ?></td>
        	<td align="center"><?php echo round(($data['salary']/30)*$data['deducted_leave']); ?></td>
        	<td align="center"><?php echo round(($data['salary']/30)*$data['paid_attendance']); ?></td>
        	<td align="center"><?php echo $data['net_payment']; ?></td>
        </tr>
        <?php	
			}
		}
		else{
		?>
		<tr>
        	<td align="center" class="text-danger" colspan="18">No Records Found!</td>
        </tr>
		<?php	
		}
		echo "<tr><td colspan='18' align='center'>";
		if($pages>1){
		if($page!=1){
		?>
		<ul class="pagination pagination-sm">
        	<li> 
          		<a href="salary_report.php?pagename=staff&page=<?php echo $page-1;
				if(isset($_GET['name'])){ echo "&name=".$_GET['name']; } ?>" style="color:#000000">Previous</a>
          	</li>
        </ul>
        <?php
		}
		for($j=1;$j<=ceil($pages);$j++){
			if($j==$page){
		?>
		<ul class="pagination pagination-sm">
        	<li class="active"> 
          		<a href="salary_report.php?pagename=staff&page=<?php echo $j;
				if(isset($_GET['name'])){ echo "&name=".$_GET['name']; } ?>" style="color:#FFFFFF"><?php echo $j; ?></a>
          	</li>
        </ul>
        <?php
			}
			else{
		?>
		<ul class="pagination pagination-sm">
        	<li> 
          		<a href="salary_report.php?pagename=staff&page=<?php echo $j;
				if(isset($_GET['name'])){ echo "&name=".$_GET['name']; } ?>" style="color:#000000"><?php echo $j; ?></a>
          	</li>
        </ul>
        <?php
			}
		}
		if($page<ceil($pages)){
		?>
		<ul class="pagination pagination-sm">
        	<li> 
          		<a href="salary_report.php?pagename=staff&page=<?php echo $page+1;
				if(isset($_GET['name'])){ echo "&name=".$_GET['name']; } ?>" style="color:#000000">Next</a>
          	</li>
        </ul>
        <?php
		}
		}
		?>
    	</td></tr>
    </table>
</div>