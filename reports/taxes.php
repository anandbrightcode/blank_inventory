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
        <title>Duties and Taxes</title>
        
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
                	<center><font size="+2">Duties and Taxes</font></center><hr />
                    <ul class="nav nav-pills">
                        <li <?php if(!isset($_GET['spage'])){ echo 'class="active"'; } ?>><a data-toggle="pill" href="#customerDiv">GSTR 1</a></li>
                        <li <?php if(isset($_GET['spage'])){ echo 'class="active"'; } ?>><a data-toggle="pill" href="#supplierDiv">GSTR 3B</a></li>
  					</ul>
  					<div class="tab-content">
                        <div id="customerDiv" class="tab-pane fade  <?php if(!isset($_GET['spage'])){ echo 'in active'; } ?>"><br />
                        	<div class="row">
                            	
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="from" onchange="getGstr1data();" value="<?php if(isset($_GET['from']))echo $_GET['from']; ?>" >
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="to" onchange="getGstr1data();" value="<?php if(isset($_GET['to']))echo $_GET['to']; ?>" >
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm" id="gstexport"
                                    	style="padding:9px 13px; <?php if(!(isset($_GET['from']) && isset($_GET['to']) && $_GET['from']!='' && $_GET['to']!='')){echo "display:none;";}?>">
                                        <i class="fa fa-download"></i></button>
                                </div>
                            </div><br />
                            <div class="row">
                            	<div class="col-md-12" id="gstr1_list">
                                	<?php include("gstr1_list.php"); ?>
                                </div>
                            </div>
                        </div>
                        <div id="supplierDiv" class="tab-pane fade <?php if(isset($_GET['spage'])){ echo 'in active'; } ?>"><br />
                        	<div class="row">
                            	
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="sfrom" onchange="getGstr3bdata();" value="<?php if(isset($_GET['sfrom']))echo $_GET['sfrom']; ?>" >
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="sto" onchange="getGstr3bdata();" value="<?php if(isset($_GET['sto']))echo $_GET['sto']; ?>" >
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm" id="sexport"
                                    	style="padding:9px 13px; <?php if(!(isset($_GET['sfrom']) && isset($_GET['sto']) && $_GET['sfrom']!='' && $_GET['sto']!='')){echo "display:none;";}?>">
                                        <i class="fa fa-download"></i></button>
                                </div>
                            </div><br />
                            <div class="row">
                            	<div class="col-md-12" id="gstr3b_list">
                                	<?php include("gstr3b_list.php"); ?>
                                </div>
                            </div>
                        </div>
  					</div>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
			var shop='<?php echo $shop; ?>';
			
			$(document).ready(function(e) {
                $('#gstexport').click(function(){
					var from=$('#from').val();
					var to=$('#to').val();
					var customer=$('#customer').val();
					window.location="gstexport.php?from="+from+"&to="+to+"&shop="+shop;
				});
            });
			
			function getGstr1data(){
				
				var from=$('#from').val();
				var to=$('#to').val();
				if(from!='' && to!=''){ $('#gstexport').show(); }
				else{ $('#gstexport').hide(); }
				$.ajax({
					type:"GET",
					url:"gstr1_list.php",
					data:{shop:shop,from:from,to:to},
					success: function(data){
						$('#gstr1_list').html(data);
					}	
				});
		  	}
			
			function getGstr3bdata(){
				
				var from=$('#sfrom').val();
				var to=$('#sto').val();
				$.ajax({
					type:"GET",
					url:"gstr3b_list.php",
					data:{shop:shop,sfrom:from,sto:to},
					success: function(data){
						$('#gstr3b_list').html(data);
					}	
				});
		  	}
			
			
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
