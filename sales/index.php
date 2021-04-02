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
        <title>Sales</title>
        
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
                <div class="col-md-12">
                	<div class="panel panel-info">
                    	<div class="panel-heading"><font size="+2">Sales</font></div>
                        <div class="panel-body">
                        	<div class="row">
                            	<div class="col-md-3">
                                	<input type="text" class="form-control" id="query" onkeyup="getSalesData();" value="<?php if(isset($_GET['query']))echo $_GET['query']; ?>" placeholder="Search Invoice No or Product">
                                </div>
                                <div class="col-md-2">
                                	<select id="type" class="form-control" onchange="getSalesData();">
                                    	<option value="">Type</option>
                                    	<option value="retail" <?php if(isset($_GET['type']) && $_GET['type']=='retail') echo "selected"; ?>>Retail</option>
                                    	<option value="tax" <?php if(isset($_GET['type']) && $_GET['type']=='tax') echo "selected"; ?>>Tax</option>
                                    </select>
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="from" onchange="getSalesData();" value="<?php if(isset($_GET['from']))echo $_GET['from']; ?>" >
                                </div>
                            	<div class="col-md-3">
                                	<input type="date" class="form-control" id="to" onchange="getSalesData();" value="<?php if(isset($_GET['to']))echo $_GET['to']; ?>" >
                                </div>
                                <div class="col-md-1">
                                	<button type="button" class="btn btn-primary btn-sm" id="export"
                                    	style="padding:9px 13px; <?php if(!(isset($_GET['from']) && isset($_GET['to']) && $_GET['from']!='' && $_GET['to']!='')){echo "display:none;";}?>">
                                        <i class="fa fa-download"></i></button>
                                </div>
                            </div><br />
                            <div class="row">
                            	<div class="col-md-12 table-responsive" id="saleslist"><?php include('saleslist.php'); ?></div>
                            </div>
                        </div>
                    </div>
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	var shop='<?php echo $shop; ?>';
			
			$(document).ready(function(e) {
                $('#export').click(function(){
					var from=$('#from').val();
					var to=$('#to').val();
					var type=$('#type').val();
					window.location="exporttoxsl.php?from="+from+"&to="+to+"&shop="+shop+"&type="+type;
				});
            });
			
			function getSalesData(){
				var query=$('#query').val();
				var type=$('#type').val();
				var from=$('#from').val();
				var to=$('#to').val();
				if(from!='' && to!=''){ $('#export').show(); }
				else{ $('#export').hide(); }
				$.ajax({
					type:"GET",
					url:"saleslist.php",
					data:{shop:shop,query:query,type:type,from:from,to:to},
					success: function(data){
						$('#saleslist').html(data);
					}	
				});
		  	}
			
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
