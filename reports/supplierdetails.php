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
	include_once "../action/class.php";
	$obj=new database();
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$supplier=$obj->get_details("`supplier`","`name`","`id`='$id' and `shop`='$shop'");
	}
	else{
		header("Location:supplier.php?pagename=report");	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Supplier Details</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		<style>
			
        </style>
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
	</head>

    <body>
    <?php include "../header.php";?>
   		<div class="container">
     		<div class="row">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
                <div class="col-md-2" style="margin-top:50px;">
                	<?php include('sidebar.php'); ?>
                </div><!-- end of col-md-3 -->
                <div class="col-md-10">
                	<center><font size="+2">Supplier Details</font></center><hr />
                    <div class="row">
                        <div id="query_result" class="table-responsive col-md-12">
                            <legend><?php echo $supplier['name']; ?></legend>
                            <?php
								$arr=$obj->get_details("`purchase`","sum(`dues`) as `total`","`supplier`='$id'");
                                $total = $arr['total'];
                                if($total>0){
                                    echo "<font size='+1' class='text-danger'>Total Dues : ".toDecimal($total)."</font>";	
                                ?><a href="supplier_pay.php?id=<?php echo $id; ?>&pagename=report" class="btn btn-info btn-sm pull-right">Pay Dues</a><?php
                                }
                                else{
									$total=0-$total;
									echo "<h4 class='text-success'>Total Advance : ".toDecimal($total)."</h4>";	
								}
									$count=20;
									$offset =0;
									if(isset($_GET['page'])){
										$page=$_GET['page'];
									}
									else{
										$page=1;	
									}
									$offset=($page-1)*$count;
									$table="`purchase`";
									$columns="*";
									$where="`supplier`='$id' and shop='$shop'";
									
									$order="id";
									$limit="$offset,$count";
									$array=$obj->get_rows($table,$columns,$where,$order,$limit);
									$rowcount=$obj->get_count($table,$where);
									$pages=ceil($rowcount/$count);
								
							?>
							<table class="table-striped table-bordered table-hover table-condensed" id="c_list" style="width:100%" >
								<thead>
									<tr>
										<th style="text-align:center">Date</th>
										<th style="text-align:center">Bill</th>
										<th style="text-align:center">Gross Amount</th>
										<th style="text-align:center">Transport</th>
										<th style="text-align:center">Total Amount</th>
										<th style="text-align:center">Paid</th>
										<th style="text-align:center">Dues</th>
									</tr>
								</thead>
								<?php
									if(is_array($array)){
										foreach($array as $purchase){
								?>
								<tr>
									<td align="center"><?php echo date('d-m-Y',strtotime($purchase['date'])); ?></td>
									<td align="center"><?php echo $purchase['invoice']; ?></td>
									<td align="center"><?php echo toDecimal($purchase['gross_amount']); ?></td>
									<td align="center"><?php echo toDecimal($purchase['transport']); ?></td>
									<td align="center"><?php echo toDecimal($purchase['total_amount']); ?></td>
									<td align="center"><?php echo toDecimal($purchase['paid']); ?></td>
									<td align="center"><?php echo toDecimal($purchase['dues']); ?></td>
								</tr>
								<?php
										}	
									}
									else{
								?>
								<tr>
									<td align="center" class="text-danger" colspan="13">No Records Found!!</td>
								</tr>
								<?php
									}
									if($pages>1){
								?>
								<tr>
									<td colspan="13" align="center">
								<?php
										if($page!=1){
								?>	
										<ul class="pagination pagination-sm">
											<li><a href="supplierdetails.php?pagename=report&page=<?php echo $page-1; 
													if(isset($_GET['id'])){echo "&id=".$_GET['id'];}
											?>">Prev</a></li>
										</ul>
								<?php
										}
										for($i=1;$i<=$pages;$i++){
								?>	
										<ul class="pagination pagination-sm">
											<li <?php if($i==$page){echo "class='active'";} ?>>
												<a href="supplierdetails.php?pagename=report&page=<?php echo $i; 
													if(isset($_GET['id'])){echo "&id=".$_GET['id'];}
												?>"><?php echo $i; ?></a>
											</li>
										</ul>
								<?php				
										}
										if($page!=$pages){
								?>
										<ul class="pagination pagination-sm">
											<li><a href="supplierdetails.php?pagename=report&page=<?php echo $page+1; 
													if(isset($_GET['id'])){echo "&id=".$_GET['id'];}
											?>">Next</a></li>
										</ul>
								<?php
										}
								?>
									</td>
								</tr>
								<?php
									}
								?>
							</table>
                        </div>
                    </div>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
			
			
			
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
