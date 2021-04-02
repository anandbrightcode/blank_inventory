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
        <title>Supplier Payment</title>
        
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
                <div class="col-md-2" style="margin-top:50px;">
                	<?php include('sidebar.php'); ?>
                </div><!-- end of col-md-3 -->
                <div class="col-md-10">
                	<center><font size="+2">Supplier Payment</font></center><hr />
					<?php
                        if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                        if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
                    ?>
                     <form action="../action/update.php" method="post" class="row" onsubmit="return Validate()">
                        <div class="col-md-12 table-responsive">
                        	<legend><?php echo $supplier['name']; ?></legend>
                            <table class="table">
                                <tr>
                                    <th style="text-align:center">Invoice No.</th>
                                    <th style="text-align:center">Date</th>
                                    <th style="text-align:center">Amount</th>
                                    <th style="text-align:center">Paid</th>
                                    <th style="text-align:center">Dues</th>
                                </tr>
                                <?php
									$arr=$obj->get_details("`purchase`","sum(`total_amount`) as `total`, sum(`paid`) as `paid`, sum(`dues`) as `dues`","`supplier`='$id' and `shop`='$shop'");
									$array=$obj->get_rows("`purchase`","`invoice`, `date`, `total_amount`, `paid`, `dues`","`supplier`='$id' and `dues`!=0 and `shop`='$shop'");
                                    if(is_array($array)){
										foreach($array as $purchase){
                                ?>
                                <tr>
                                    <td align="center"><?php echo $purchase['invoice']; ?></td>
                                    <td align="center"><?php echo date('d-m-Y',strtotime($purchase['date'])); ?></td>
                                    <td align="center"><?php echo toDecimal($purchase['total_amount']); ?></td>
                                    <td align="center"><?php echo toDecimal($purchase['paid']); ?></td>
                                    <td align="center"><?php echo toDecimal($purchase['dues']); ?></td>
                                </tr>
                                <?php			
                                    	}
									}
									$getdues=$obj->get_details("`supplier`","`advance`","`id`='$id'");
									$advance=$getdues['advance'];
									$dues=$arr['dues'];
									if($advance<0){
										$advance=0-$advance;
								?>
                                <tr>
                                	<th style="text-align:center;" colspan="4">Previous Dues</th>
                                    <td align="center"><?php echo toDecimal($advance); ?></td>
                                </tr>
                                <?php
										$dues+=$advance;
										$advance=0;
									}
                                ?>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table">
                            	<tr>
                                	<td>Date</td>
                                    <td><input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required="required" /></td>
                                    <td>Payment Mode</td>
                                    <td>
                                        <select name="payment_mode" id="payment_mode" class="form-control" required="required" onchange="getCheque(this.value)">
                                            <option value="">Select</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="cash+cheque">Cash + Cheque</option>
                                            <option value="pos">POS</option>
                                            <option value="neft">NEFT</option>
                                            <option value="rtgs">RTGS</option>
                                        </select>
                                    </td>
                                    <td></td><td></td>
                                </tr>
                                 <tr class="cheque" style="display:none;">
                                	<td>Cheque Number</td>
                                    <td><input type="text" name="cheque_no" class="form-control"/></td>
                                    <td>Cheque Date</td>
                                    <td>
                                       <input type="date" name="cheque_date" class="form-control"/>
                                    </td>
                                    <td>Bank</td>
                                    <td><input type="text" name="bank" class="form-control"/></td>
                                </tr>
                                <tr>
                                    <td>Total Amount</td>
                                    <td><?php echo toDecimal($arr['total']); ?></td>
                                    <td>Total Paid</td>
                                    <td><?php echo toDecimal($arr['paid']); ?></td>
                                    <td>Total Dues</td>
                                    <td><?php echo toDecimal($dues); ?></td>
                                </tr>
                                <tr>
                                	<td>Advance</td>
                                    <td width="18%"><input type="text" name="advance" id="advance" class="form-control" value="<?php echo $advance; ?>" readonly="readonly" /></td>
                                    <th colspan="2" style="text-align:center;">
                                        <label class="checkbox-inline">
                                        	<input type="checkbox" name="check_advance" id="check_advance" value="1"  <?php if($advance>0){echo "checked";} ?> />Use Advance
                                        </label>
                                     </th>
                                </tr>
                                <tr>
                                    <td>Total Dues</td>
                                    <td width="18%"><input type="text" name="total" id="total" class="form-control" value="<?php echo $dues; ?>" readonly="readonly" /></td>
                                    <td>Pay Dues</td>
                                    <td width="18%"><input type="number" name="paid" id="paid" class="form-control" autocomplete="off" onkeyup="getDues(this.value);" /></td>
                                    <td>Dues</td>
                                    <td width="18%"><input type="text" name="dues" id="dues" class="form-control" readonly="readonly" /></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                            <input type="submit" name="update_dues" value="Save" class="btn btn-success btn-sm" />
                        </div>
                    </form>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
		
			$(document).ready(function(e) {
				$('#check_advance').click(function(){
					var total_amount=$('#total').val();
					var advance=$('#advance').val();
					$('#paid').val('');
					if($(this).is(":checked")){
						var dues=total_amount-advance;
						if(dues<0){dues=0;}
					}
					else{dues=total_amount;}
					$('#dues').val(dues);
					if(dues==0){
						$('#next_payment').attr('readonly',true);
					}
					else{
						$('#next_payment').attr('readonly',false);
					}
				});
			});
	
        	function getDues(str){
				var paid=parseFloat(str);
				if(isNaN(paid)){paid=0;}
				var total=parseFloat($('#total').val());
				if($('#check_advance').is(":checked")){
					var advance=parseFloat($('#advance').val());
					if(isNaN(advance)){advance=0;}
				}
				else{
					var advance=0;
				}
				var dues=total-advance-paid;
				$('#dues').val(dues)
			
			}
			
			function Validate(){
				var total_amount=$('#total').val();
				var advance=$('#advance').val();
				if(advance!=0 && total_amount==0){
					$('#check_advance').attr("checked","true");
				}
			}
			 function getCheque(str){
				var mode=str;
				if(mode=='cheque'){
					$('.cheque').show();
				}else{
					$('.cheque').hide();
				}
			}
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
