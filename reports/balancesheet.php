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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Balance Sheet</title>
        
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
                	<center><font size="+2">Balance Sheet</font></center><hr />
                    <ul class="nav nav-pills">
                        <li <?php if(!isset($_GET['spage'])){ echo 'class="active"'; } ?>><a data-toggle="pill" href="#customerDiv">Customer</a></li>
                        <li <?php if(isset($_GET['spage'])){ echo 'class="active"'; } ?>><a data-toggle="pill" href="#supplierDiv">Supplier</a></li>
  					</ul>
  					<div class="tab-content">
                        <div id="customerDiv" class="tab-pane fade  <?php if(!isset($_GET['spage'])){ echo 'in active'; } ?>"><br />
                        	<div class="row">
                            	<div class="col-md-4">
                                    <select id="customer" class="form-control" onchange="getCustData()">
                                        <option value="">Select Customer</option>
                                        <?php
                                            $arr=$obj->get_rows("`customer`","`id`,`name`","`shop`='$shop'");
                                            if(is_array($arr)){
                                                foreach($arr as $customer){
                                        ?>
                                            <option value="<?php echo $customer['id']; ?>" <?php if(isset($_GET['customer']) && $_GET['customer']==$customer['id']){echo "selected";} ?>>
                                                <?php echo $customer['name']; ?>
                                            </option>
                                        <?php
                                                }	
                                            }
                                        ?>
                                    </select>
                                </div>
                                 
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="from" onchange="getCustData();" value="<?php if(isset($_GET['from']))echo $_GET['from']; ?>" >
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="to" onchange="getCustData();" value="<?php if(isset($_GET['to']))echo $_GET['to']; ?>" >
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm" id="cexport"
                                    	style="padding:9px 13px; <?php if(!(isset($_GET['from']) && isset($_GET['to']) && $_GET['from']!='' && $_GET['to']!='')){echo "display:none;";}?>">
                                        <i class="fa fa-download"></i></button>
                                </div>
                                 <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm fa fa-print" id="custprint"
                                    	style="padding:9px 13px;">
                                        </button>
                                </div>
                            </div><br />
                            <div class="row">
                            	<div class="col-md-12" id="custlist">
                                	<?php include("bscustlist.php"); ?>
                                </div>
                            </div>
                        </div>
                        <div id="supplierDiv" class="tab-pane fade <?php if(isset($_GET['spage'])){ echo 'in active'; } ?>"><br />
                        	<div class="row">
                            	<div class="col-md-4">
                                    <select id="supplier" class="form-control" onchange="getSupData()">
                                        <option value="">Select Supplier</option>
                                        <?php
                                            $arr=$obj->get_rows("`supplier`","`id`,`name`","`shop`='$shop'");
                                            if(is_array($arr)){
                                                foreach($arr as $supplier){
                                        ?>
                                            <option value="<?php echo $supplier['id']; ?>" <?php if(isset($_GET['supplier']) && $_GET['supplier']==$supplier['id']){echo "selected";} ?>>
                                                <?php echo $supplier['name']; ?>
                                            </option>
                                        <?php
                                                }	
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                            	<div class="col-md-3">
                                <input type="hidden" name="shop" id="shop" value="<?php echo $shop;?>"/>
                                	<input type="date" class="form-control" id="sfrom" onchange="getSupData();" value="<?php if(isset($_GET['sfrom']))echo $_GET['sfrom']; ?>" >
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="sto" onchange="getSupData();" value="<?php if(isset($_GET['sto']))echo $_GET['sto']; ?>" >
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm" id="sexport"
                                    	style="padding:9px 13px; <?php if(!(isset($_GET['sfrom']) && isset($_GET['sto']) && $_GET['sfrom']!='' && $_GET['sto']!='')){echo "display:none;";}?>">
                                        <i class="fa fa-download"></i></button>
                                </div>
                                 <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm fa fa-print" id="supp_print"
                                    	style="padding:9px 13px;">
                                        </button>
                                </div>
                            </div><br />
                           
                            <div class="row">
                            	<div class="col-md-12" id="suplist">
                                	<?php include("bssuplist.php"); ?>
                                </div>
                            </div>
                        </div>
  					</div>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
		   
		   var shop='<?php echo $shop; ?>';
			
			
			function getCust1Data(){
				var customer=$('#customer1').val();
				var from=$('#from1').val();
				var to=$('#to1').val();
				
				if(from!="" && to!=""){
					$('#paymentcust_export').show();
				    $('#paymentcust_print').show();
				}
				else{
					 $('#paymentcust_export').hide();
					 $('#paymentcust_print').hide();
					 
					 }
				$.ajax({
					type:"GET",
					url:"paycust1list.php",
					data:{shop:shop,customer1:customer,from1:from,to1:to},
					success: function(data){
						
						$('#custlist1').html(data);
					}	
				});
		  	}
			
			function getSup1Data(){
				var supplier=$('#supplier1').val();
				var from=$('#sfrom1').val();
				var to=$('#sto1').val();
				
				if(from!="" && to!=""){ 
				$('#paymentsupp_export').show();
				$('#paymentsupp_print').show();
				 }
				else{
					 $('#paymentsupp_export').hide();
					 $('#paymentsupp_print').hide();
					  }
				$.ajax({
					type:"GET",
					url:"paysup1list.php",
					data:{shop:shop,supplier1:supplier,sfrom1:from,sto1:to},
					success: function(data){
						
						$('#suplist1').html(data);
					}	
				});
		  	}
			
        	function getModel()
			{
				
			  var comp=$('#company').val();
				var cat=$('#category').val();
				getDates();
				$.ajax({
						type:"POST",
						url:"../ajax_returns.php",
						data:{comp:comp,cat:cat,getModel:'getModel',page:'balancesheet'},
						success: function(data){
							
							$('#model').html(data);
						}
					});
					
			}
			
        	function getCategory(str)
			{
			    var company=str;
				
				getDates();
				$.ajax({
						type:"POST",
						url:"../ajax_returns.php",
						data:{company:company,getCategory:'getCategory',page:'balancesheet'},
						success: function(data){
							
							$('#category').html(data);
						}
					});
			}
			
			var shop='<?php echo $shop; ?>';
			
			$(document).ready(function(e) {
                $('#cexport').click(function(){
					var from=$('#from').val();
					var to=$('#to').val();
					var customer=$('#customer').val();
					window.location="export_bs.php?from="+from+"&to="+to+"&shop="+shop+"&customer="+customer;
				});
                $('#sexport').click(function(){
					var from=$('#sfrom').val();
					var to=$('#sto').val();
					var supplier=$('#supplier').val();
					window.location="export_bs.php?from="+from+"&to="+to+"&shop="+shop+"&supplier="+supplier;
				});
				 $('#stexport').click(function(){
					var company=$('#company').val();
					var category=$('#category').val();
					var model=$('#model').val();
					window.location="exportst.php?company="+company+"&category="+category+"&model="+model;
				});
				$('#stprint').click(function(){
					var company=$('#company').val();
					var category=$('#category').val();
					var model=$('#model').val();
					var shop=$('#shop').val();
					window.location="print_stockreport.php?company="+company+"&category="+category+"&model="+model+"&shop="+shop;
				});
				$('#custprint').click(function(){
					var customer=$('#customer').val();
					var from=$('#from').val();
					var to=$('#to').val();
					var shop=$('#shop').val();
					window.location="print_customerreport.php?customer="+customer+"&from="+from+"&to="+to+"&shop="+shop;
				});
				$('#supp_print').click(function(){
					var supplier=$('#supplier').val();
					var from=$('#sfrom').val();
					var to=$('#sto').val();
					var shop=$('#shop').val();
			
					window.location="print_supplierreport.php?supplier="+supplier+"&from="+from+"&to="+to+"&shop="+shop;
				});
				$('#paymentcust_export').click(function(){
					var customer=$('#customer1').val();
					var from=$('#from1').val();
					var to=$('#to1').val();
					window.location="exportbs.php?customer="+customer+"&from="+from+"&to="+to;
				});
				$('#paymentsupp_export').click(function(){
					var supplier=$('#supplier1').val();
					var from=$('#sfrom1').val();
					var to=$('#sto1').val();
					window.location="exportbs.php?supplier="+supplier+"&from="+from+"&to="+to;
				});
				$('#paymentsupp_print').click(function(){
					var supplier=$('#supplier1').val();
					var from=$('#sfrom1').val();
					var to=$('#sto1').val();
					var shop=$('#shop').val();
					window.location="print_paymentsupplierreport.php?supplier="+supplier+"&from="+from+"&to="+to+"&shop="+shop;
				});
				$('#paymentcust_print').click(function(){
					var customer=$('#customer1').val();
					var from=$('#from1').val();
					var to=$('#to1').val();
					var shop=$('#shop').val();
					window.location="print_paymentcustomerreport.php?customer="+customer+"&from="+from+"&to="+to+"&shop="+shop;
				});
				
				
            });
			
			function getCustData(){
				var customer=$('#customer').val();
				var from=$('#from').val();
				var to=$('#to').val();
				
				if(from!='' && to!=''){ $('#cexport').show(); }
				else{ $('#cexport').hide(); }
				$.ajax({
					type:"GET",
					url:"bscustlist.php",
					data:{shop:shop,customer:customer,from:from,to:to},
					success: function(data){
						$('#custlist').html(data);
					}	
				});
		  	}
			
			function getSupData(){
				var supplier=$('#supplier').val();
				var from=$('#sfrom').val();
				var to=$('#sto').val();
				if(from!='' && to!=''){ $('#sexport').show(); }
				else{ $('#sexport').hide(); }
				$.ajax({
					type:"GET",
					url:"bssuplist.php",
					data:{shop:shop,supplier:supplier,sfrom:from,sto:to},
					success: function(data){
						$('#suplist').html(data);
					}	
				});
		  	}
			
			function getDates(){
				
				var comp=$('#company').val();
				var cat=$('#category').val();
				var model=$('#model').val();
				if(comp!=""){ $("#stexport").show();}
				else{ $('#stexport').hide();}
				var shop='<?php echo $shop; ?>';
			
					$.ajax({
						type:"GET",
						url:"stock_profitlist.php",
						data:{comp:comp,cat:cat,model:model,shop:shop},
						success: function(data){
							$('#stocklist').html(data);
						}
					});
				
			}
				function viewPurchase(str){
				var id=str;
				var shop='<?php echo $shop; ?>';
				$.ajax({
						type:"GET",
						url:"productlist.php",
						data:{id:id,shop:shop},
						success: function(data){
							//alert(data);
							$('#product').html(data);
							$('#supplierDiv').hide();
						    $('#product').show();
						}
					});
					
			
			}
			
			
			function closeThis(){
				$('#supplierDiv').show();
				$('#product').hide();
			}
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
