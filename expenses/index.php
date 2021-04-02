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
        <title>Expenses</title>
        
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
                <div class="col-md-12">
                    <div id="formPanel" class="panel panel-primary">
                        <div class="panel-heading">
                            <font size="+2">Add Expense</font>
                            <button type="button" class="btn btn-warning pull-right" onclick="showThis('list');">View Expenses</button>
                        </div>
                        <div class="panel-body">
                            <form action="../action/insert.php" method="post" style="font-size:16px;">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Date</b></div>
                                    <div class="col-md-6"><input type="date" name="date" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Bill No</b></div>
                                    <div class="col-md-6"><input type="text" name="bill" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Name</b></div>
                                    <div class="col-md-6"><input type="text" name="name" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Particulars</b></div>
                                    <div class="col-md-6">
                                        <textarea name="particulars" style="resize:vertical;" class="form-control"></textarea>
                                        <input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                    </div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2"><b>Amount</b></div>
                                    <div class="col-md-6"><input type="text" name="amount" class="form-control" /></div>
                                    <div class="col-md-2"></div>
                                </div><br />
                                <div class="row">
                                    <center>
                                        <input type="submit" name="save_expense" value="Save" class="btn btn-success" />
                                    </center>
                                </div><br />
                            </form>
                        </div>
                    </div><!-- form panel closed-->
                    <div class="panel panel-danger" id="listPanel" style="display:none;">
                        <div class="panel-heading">
                            <font size="+2">Daily Expenses</font>
                            <button type="button" class="btn btn-info pull-right" onclick="showThis('form');">Add Expense</button>
                        </div><!-- panel-heading closed-->
                        <div class="panel-body">
                            <input type="text" placeholder="Search Expense By Name Or Bill no" class="form-control" onkeyup="getExpenseData(this.value)" id="c_search">
                            <br>
                            <div id="query_result">
                                <?php include "expenselist.php" ;?>	  
                            </div>
                        </div>
                    </div><!-- list panel closed-->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
        <script language="javascript">
        	
			function showThis(str){
				var div=str;
				if(div=='form'){
					$('#formPanel').show();
					$('#listPanel').hide();
				}
				else{
					$('#formPanel').hide();
					$('#listPanel').show();
				}
			}
			
			function getExpenseData(str){
				var query=str;
				var shop='<?php echo $shop; ?>';
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "expenselist.php?query="+query+"&shop="+shop, true);
	 			xhttp.send();	
			}
			
			function navigate(str1,str2){
				var query=str1;
				var page=str2;
				var shop='<?php echo $shop; ?>';
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4 && xhttp.status == 200) {
						document.getElementById("query_result").innerHTML = xhttp.responseText;
					}
				};
				xhttp.open("GET", "expenselist.php?query="+query+"&shop="+shop+"&page="+page, true);
	 			xhttp.send();	
			}
			
			function confirmDel(){
				if(confirm("Are sure to Delete this?")){
					return true;
				}else{
					return false;
				}
			}
        </script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
