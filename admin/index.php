<?php
session_start();
  if(isset($_SESSION['user'])){
	   	$role=$_SESSION['role'];
	   	$user=$_SESSION['user'];
	   	$shop=$_SESSION['shop'];
		if($role!='admin'){
		   header("Location:../index.php");
		   echo "<script>location='../index.php'</script>";
		}
  }
  else{
	   header("Location:../index.php");
	   echo "<script>location='../index.php'</script>";
	 
  }
	include('../action/class.php');
	$obj=new database();
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Admin</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
  		<style>
			
        </style>
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
        
		<script>
			
			function addUser(){
				$("#add_user").show();	
				$("#update_user").hide();
			}
			function closeUser(){
				
				$("#add_user").hide();	
			}
			function closeUpUser(){
				
				$("#update_user").hide();	
			}
			
			
			function editUser(data){
				var id=data;
				//alert(id);
				$("#add_user").hide();	
				$("#add_course").hide();	
				$("#update_course").hide();
						$("#update_user").show();
				$.ajax({
					type: 'POST',
					url: '../ajax_returns.php',
					data: {
						id: id,editUser:'editUser'
					},
					dataType: 'json',
					success: function (data) //on recieve of reply
					{
						var uid=data['id'];
						var username=data['username']; 
						var role=data['role']; 
						var active=data['active']; 
						var shop=data['shop']; 
						//alert(name);
						$('#uid').val(uid);
						$('#up_username').val(username);
						$('#up_password').val("");
						$('#up_role').val(role);
						$('#up_active').val(active);
						$('#shop').val(shop);	
					}
				});		
			}
			
			function deleteUser(data){
				var id=data;
				//alert(uid)
				if(confirm("Are you sure you want to Delete?")){
					var xhttp = new XMLHttpRequest();
					  xhttp.onreadystatechange = function() {
						if (xhttp.readyState == 4 && xhttp.status == 200) {
							alert(xhttp.responseText);
							location.reload();
						}
					  };
					  xhttp.open("GET", "../action/delete.php?deleteUser=deleteUser&id="+id, true);
					  xhttp.send();
				}
			}
			
		</script>

	</head>

    <body>
    <?php include "../header.php";?>
   		<div class="container">
     		<div class="row">
                <div class="col-md-12">
                    
      <div class="row">
          <br />
			<legend>Welcome <?php echo ucfirst($user); ?></legend>
				<div class="container-fluid">
				  <div class="row">
						<div id="msg"></div>
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#users">Users</a></li>
						</ul>
					
						<div class="tab-content">
							<div id="users" class="tab-pane fade in active">
								<div class="row">
								  <div class="col-lg-1 col-md-1" ></div>
								  <div class="table-responsive col-lg-10 col-md-10 col-sm-12 col-xs-12">
										<table id="users_table" class="table table-striped">
										  <thead>
											  <th style="text-align:center">ID</th>
											  <th style="text-align:center">Users</th>
											  <th style="text-align:center">Role</th>
											  <th style="text-align:center; display:none;">Shop</th>
											  <th style="text-align:center">Action</th>
											</thead>
											<?php
												$array=$obj->get_rows("`users`");
												$i=1;
												foreach($array as $users){
												$shop_id=$users['shop'];
											?>
											<tr>
											  <td align="center"><?php echo $i; $i++; ?></td>
											  <td align="center"><?php echo ucfirst($users['username']); ?></td>
											  <td align="center"><?php echo ucfirst($users['role']); ?></td>
											  <td align="center" style="text-align:center; display:none;">
												<?php
													$arr=$obj->get_details("`shop`","`name`","`id`='$shop_id'");
													echo $arr['name'];
												?>
											  </td>
											  <td align="center">
													<button type="button" class="btn btn-info" onclick="editUser('<?php echo $users['id']; ?>')"><i class="fa fa-edit"></i></button>
													<?php
														if($users['username']==$user)
															continue;
													?>
													<button type="button" class="btn btn-danger" onclick="return deleteUser('<?php echo $users['id']; ?>')"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
											<?php
						  }
						?>
										</table><!--end of users table -->
										<button type="button" class="btn btn-warning" onclick="addUser();">Add User</button>
									</div>
								  <div class="col-lg-1 col-md-1" ></div>
								</div><!--end of row-->
								<br />
								<div class="row" id="add_user" style="display:none">
								  <div class="col-lg-1 col-md-1" ></div>
								  <form action="../action/insert.php" method="post" class="col-lg-10 col-md-10 col-sm-12 col-xs-12" >
									  <legend>Add User</legend>
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Username:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <input type="text" name="username" class="form-control" required="true" />
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 1-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Password:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <input type="password" name="password" class="form-control" required="true" />
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 2-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Role:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="role" class="form-control" required="true">
												  <option value="">Select</option>
												  <option value="admin">Admin</option>
												  <option value="user">User</option>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 3-->
										<div class="row" style="display:none;">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Shop:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="shop" class="form-control" required="true">
												  <option value="">Select</option>
												  <?php
													$array2=$obj->get_rows("`shop`","`id`,`name`");
													foreach($array2 as $shops){
												  ?>
												  <option value="<?php echo $shops['id'];?>"><?php echo $shops['name'];?></option>
												  <?php
													}
												  ?>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 3-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Active:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="active" class="form-control" required="true">
												  <option value="1">Yes</option>
												  <option value="0">No</option>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 4-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
											  <input type="submit" name="adduser" value="Add" class="btn btn-success" />
											</div>
										  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
											  <button type="button" class="btn btn-danger" onclick="closeUser()">Cancel</button>
											</div>
											<div class="col-lg-3 col-md-3"></div>
										</div><!--end of form row 5-->
									</form><!--end of add user form -->
								  <div class="col-lg-1 col-md-1" ></div>
								</div><!--end of row-->
								<div class="row" id="update_user" style="display:none">
								  <div class="col-lg-1 col-md-1" ></div>
								  <form action="../action/update.php" method="post"  class="col-lg-10 col-md-10 col-sm-12 col-xs-12" >
									  <legend>Update User</legend>
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>ID:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <input type="text" name="uid" id="uid" class="form-control" readonly="readonly" />
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 1-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Username:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <input type="text" name="up_username" id="up_username" class="form-control" />
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 1-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Password:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <input type="password" name="up_password" id="up_password" class="form-control" />
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 2-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Role:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="up_role" id="up_role" class="form-control">
												  <option value="">Select</option>
												  <option value="admin">Admin</option>
												  <option value="user">User</option>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 3-->
										<div class="row" style="display:none;">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Shop:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="up_shop" id="shop" class="form-control">
												  <option value="">Select</option>
												  <?php
													$array2=$obj->get_rows("`shop`","`id`,`name`");
													foreach($array2 as $shops){
												  ?>
												  <option value="<?php echo $shops['id'];?>"><?php echo $shops['name'];?></option>
												  <?php
													}
												  ?>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 3-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"><b>Active:</b></div>
										  <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
											  <select name="up_active" id="up_active" class="form-control">
												  <option value="1">Yes</option>
												  <option value="0">No</option>
												</select>
											</div>
											<div class="col-lg-5 col-md-5"></div>
										</div><!--end of form row 4-->
										<br />
										<div class="row">
										  <div class="col-lg-1 col-md-1"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4"></div>
										  <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
											  <input type="submit" name="up_user" value="Update" class="btn btn-success" />
											</div>
										  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
											  <button type="button" class="btn btn-danger" onclick="closeUpUser()">Cancel</button>
											</div>
											<div class="col-lg-3 col-md-3"></div>
										</div><!--end of form row 5-->
									</form><!--end of add user form -->
								  <div class="col-lg-1 col-md-1" ></div>
								</div><!--end of row-->
							</div><!--end of users div-->
						</div>
				  </div><!-- end of row -->
                </div><!-- end of col-md-12 -->
            </div><!-- end of row -->
        </div><!-- end of container -->
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
