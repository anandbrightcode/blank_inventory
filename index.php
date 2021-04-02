<?php
session_start();
  include_once "action/class.php";
  if(isset($_COOKIE['userdata'])){
	  $ud=explode(',',$_COOKIE['userdata']);
	  $_SESSION['user']=$ud[0];
	  $_SESSION['role']=$ud[1];
	  $_SESSION['shop']=$ud[2];
  }
  if(isset($_SESSION['user'])){
	   header("Location:home.php?pagename=home");
	   echo "<script>location='home.php?pagename=home'</script>";
  }
  $obj=new database();   
?>
<!doctype html>
<html>
<head> 
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">


    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bootstrap/css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<title>Login</title>
<body>
 <noscript>
   Please enable javascript of your browser
 </noscript>
 <div class="container">
  
   <div class="row">
 
  <div class="col-md-4" >
  </div>
    <div class="col-md-4" id="login">
	   <div class="panel panel-success">
		     <div class="panel-heading"><h4> Login</h4>	  </div>
			     <div class="panel-body">
	    <form method="post" class="bs-example bs-example-form" role="form">
		<div class="input-group">
		   <span class="input-group-addon"><i class="fa fa-user"></i></span>
		   <input type="text" name="username" placeholder="username" class="form-control">
         </div><!-- input group closed-->
         <br>
            <div class="input-group">
		   <span class="input-group-addon"><i class="fa fa-lock"></i></span>
		   <input type="password" name="password" placeholder="password" class="form-control">
            </div> 
            
            <br>
            <div class="input-group">
            	<label class="checkbox-inline"><input type="checkbox" name="remember" value="1">Remember Me</label>
            </div> <br>
            <div class="input-group center-block">
			   <input type="submit" name="login" class="btn  btn-primary  center-block"  value="Login" style="width:100%">
			 </div>
	   <div id="logstatus" style="visibility:none" class="text-danger">
	      
	   </div>
		
		</form>
		</div>
		</div>
	
	 </div>
	 
	<div class="col md-4"></div>
	</div><!-- row closed -->
 </div><!-- container closed -->
 
   <script src="bootstrap/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    
</body>
<?php
if (isset($_POST['login'])){

	     $user=$_POST['username'];
	     $pass=$_POST['password'];
		 if(isset($_POST['remember'])){	$check=1; }
		 else{ $check=0;}
		// $role=$_POST['role'];
		 $flag =false;
	     $table="`users`";
		 $run=$obj->login($table,$user,$pass);
		 if(is_array($run)){
			$flag=true;
			$_SESSION['user']=$user;
			$role=$run['role'];
			$_SESSION['role']=$role;
			$_SESSION['shop']=$run['shop'];				
			$_SESSION['check']=$check;
		 }
		    if($flag===true){
				echo "<script>location='home.php?pagename=home'</script>";
				header("Location:home.php");
			}
	       else if($flag==false){
			  
			  ?>
			     <script>
			     document.getElementById("logstatus").innerHTML="<center>Wrong username or password!!</center>";
			   </script>
			  <?php
		   }	  
	} 

?>
</html>