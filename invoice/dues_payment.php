<?php
session_start();
error_reporting(0);
 if(isset($_SESSION['user'])){
	  $role=$_SESSION['role'];
	  $user=$_SESSION['user'];
	  $shop=$_SESSION['shop'];
  }
  else{
    header("Location:../index.php");
  }
  include_once("../action/class.php");
  $obj=new database();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Invoice</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
		<style>
         .sexy_line { 
           margin-right:100px;
            height: 1px;
            background: black;
            background: -webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
        }
        </style>
	</head>

	<body style="background-color:#F6F6F6">
		<?php include "../header.php"; ?>
        <div class="container">
          <div class="row">
            <div class="col-md-2">
              <div style="margin-top:50px;">
                <a href="../invoice?pagename=invoice" class="btn btn-danger" style="width:130px;">New Invoice</a><br /><br />
<!--                   <a href="../invoice/performa.php?pagename=invoice" class="btn btn-danger" style="width:130px;">Performa Invoice</a><br /><br />
                  <a href="../invoice/quotation.php?pagename=invoice" class="btn btn-danger" style="width:130px;">New Quotation</a><br /><br /> -->
                  <a href="dues_payment.php?pagename=invoice" class="btn btn-warning"  style="width:130px;">Dues Payment</a><br /><br />
              </div>
            </div>
            <div class="col-md-6">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
                <form style="padding-top:5px;" action="../action/update.php" method="post" onsubmit="return validate()">
                   <h3 style="color:#ca6104;" class="text-center">Dues Payment</h3>
                      <div class="sexy_line"></div>
                       <br />
                                        <table class=" table table-bordered">
                                            <tr>
                                                <td>Billing Mode:</td>
                                                <td>
                                                    <select name="pay_mode" class="form-control">
                                                        <option value="cash">Cash</option>
                                                        <option value="cheque">Cheque</option>
                                                        <option value="online">Online</option>
                                                    </select>
                                                </td> 
                                            </tr>
                                            <tr>
                                            	<td>Date:</td>
                                                <td>
                                                	<input type="date" name="date" class="form-control" required="required" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Enter Invoice Number:</td>
                                                <td>
                                                	<select name="prefix" id="prefix" class="form-control" style="float:left; position:relative; width:40%;">
														<?php 
                                                            $selpre=$obj->get_rows("`invoice`","distinct(`prefix`)","`shop`='$shop'","id desc");
                                                            if(is_array($selpre)){
                                                                foreach($selpre as $prefix){
                                                        ?>
                                                        <option value="<?php echo $prefix['prefix']; ?>"><?php echo $prefix['prefix']; ?></option>
                                                        <?php 
                                                                } 
                                                            }
                                                            else{echo "<option value=''>No Invoice</option>";}
                                                        ?>
                                                    </select>
                                                    <input type="text" name="invoice" value="" id="invoice" class="form-control" autocomplete="off" 
                                                    			style="float:left; position:relative; width:60%;"/>
                                                    <input type="hidden" name="shop" value="<?php echo $shop; ?>" id="shop" />
                                                    <input type="hidden" name="customer_id"  id="customer_id" />
                                                    <input type="hidden" name="id"  id="id" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Amount:</td>
                                                <td><input type="text" name="total_amount" value="" id="total_amt" class="form-control" readonly="readonly"/></td>
                                            </tr>
                                            <tr>
                                                <td>Paid Amount:</td>
                                                <td><input type="text" name="paid" value="" id="paid_amt" class="form-control" readonly="readonly"/></td>
                                            </tr>
                                            <tr>
                                                <td>Dues Amount:</td>
                                                <td><input type="text" name="dues" value=""  id="dues_amt" class="form-control" readonly="readonly"/></td>
                                            </tr>
                                            <tr>
                                                <td>Re-Paid Amount:</td>
                                                <td><input type="text" name="repaid" id="repaid" value="" class="form-control" required="required" /></td>
                                            </tr>
                                            <tr>
                                                <td>Next Payment:</td>
                                                <td><input type="date" name="next_payment" id="next_payment" class="form-control" /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center">
                                                    <input type="submit" name="update_invoice" value="Save" class="btn btn-success" style="width:50%">
                                                </td>
                                            </tr>
                                        </table>
                </form>
            </div>
            <div class="col-md-4">
            </div>
          </div><!-- end of row -->
        </div><!-- end of container -->
<script>
	 $(function () {
		$('#invoice').keyup(function () {
			var shop=$('#shop').val()
			var prefix=$('#prefix').val()
			$.ajax({
				type: 'POST',
				url: '../ajax_returns.php',
				data: {
					query: $(this).val(),
					shop:shop,
					prefix:prefix,
					getDues:'getDues'
				},
				dataType: 'json',
				success: function (data) //on recieve of reply
				{
					var total_amount = data['total_amount']; 
					var dues_amount = data['dues']; 
					var paid_amount = data['paid'];
					var customer_id = data['customer_id'];
					$('#id').val(data['id']);
					$('#customer_id').val(customer_id);
					$('#total_amt').val(total_amount);
					$('#paid_amt').val(paid_amount);
					$('#dues_amt').val(dues_amount);
				}
			});
		});  
	});  
	
	function validate(){
		var total=$('#total_amt').val();
		var paid=$('#paid_amt').val();
		var repaid=$('#repaid').val();
		var next_payment=$('#next_payment').val();
		var dues=parseFloat(total)-parseFloat(paid)-parseFloat(repaid);
		if(dues!=0){
			if(next_payment==''){
				alert("Select Next Payment Date!");
				$('#next_payment').focus();
				return false;
			}		
			else{
				return true;
			}		
		}
		else{
			$('#next_payment').val('');
			return true;
		}
	
	}
  </script>
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
	</body>
</html>
