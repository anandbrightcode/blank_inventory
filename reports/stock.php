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
  include_once("../action/class.php");
  $obj=new database();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Stock Report</title>
        
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
                	<center><font size="+2">Stock Report</font></center><hr />
                    <div class="row">
                    	<div class="col-md-3"><input type="date" id="from" class="form-control" value="<?php if(isset($_GET['from'])){echo $_GET['from'];} ?>" /></div>
                    	<div class="col-md-3"><input type="date" id="to" class="form-control" value="<?php if(isset($_GET['to'])){echo $_GET['to'];} ?>" /></div>
                        <div class="col-md-3">
                            <select name="company" id="company" class="form-control" onChange="getCategory(this.value);">
                            <option value="">Select Company</option>
                            <?php
							  $table="company";
							  $columns="*";
							  $rs=$obj->get_rows($table,$columns);
							  if(is_array($rs))
							  {
								  foreach($rs as $val)
								  {
							?>
                               <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                               <?php
								  }
							  }
							   ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                           
                            <select name="category" id="category" class="form-control" onChange="getModel();">
                          
                               <option value="">Select Category</option>
                               
                            </select>
                           
                        </div>
                        
                        
                        <br /><br />
                        
                        <div class="col-md-3">
                           
                            <select name="model" id="model" class="form-control" onChange="getDates();">
                          
                               <option value="">Select Model</option>
                               
                            </select>
                           
                        </div>
                      
                    	<div class="col-md-2"><button type="button" class="btn btn-success" onclick="getDates();">Search <i class="fa fa-search"></i></button></div>
                        <div class="col-md-2">
                        	<button type="button" class="btn btn-sm btn-primary" onclick="exportstock()">Export <i class="fa fa-download"></i></button>
                        </div>
                    </div><br />
                    <div class="row">
                        <div id="query_result" class="table-responsive col-md-12">
                            <?php include "stocklist.php" ;?>	  
                        </div>
                    </div>
                </div>
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
		
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
			
			function exportstock(){
				var from=$('#from').val();
				var to=$('#to').val();
				var shop='<?php echo $shop; ?>';
				
				window.location="exportstock.php?from="+from+"&to="+to+"&shop="+shop;
			}
			
			function getDates(){
				var from=$('#from').val();
				var to=$('#to').val();	
				var comp=$('#company').val();
				var cat=$('#category').val();
				var model=$('#model').val();
				var shop='<?php echo $shop; ?>';
				
			
					$.ajax({
						type:"GET",
						url:"stocklist.php",
						data:{comp:comp,cat:cat,model:model,from:from,to:to,shop:shop},
						success: function(data){
							$('#query_result').html(data);
						}
					});
				
			}
			
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
