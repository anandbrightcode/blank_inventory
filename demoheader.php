<nav class="navbar navbar-inverse">
  <div class="container-fluid" >
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php?pagename=home">
        <?php echo $_SESSION['shop_name'];?>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right" style="font-size:13px;">
        <?php if(isset($_GET['pagename'])){
  	  $pagename=$_GET['pagename'];
  	  }
  	   ?>
        <li <?php if(isset($_GET['pagename'])){ if($pagename=="home"){?> class="active"<?php }} ?>><a href="home.php?pagename=home"><i class="fa fa-home"></i>Home</a></li>
        
        <li><a href="customer?pagename=customer"><i class="fa fa-users"></i>&nbsp;Customer</a></li> 
        <li><a href="supplier?pagename=supplier"><i class="fa fa-user"></i>&nbsp;Supplier</a></li>
        <li><a href="purchase?pagename=purchase"><i class="fa fa-shopping-cart"></i>&nbsp;Purchase</a></li>
        <li><a href="stock?pagename=stock"><i class="fa fa-list-ul"></i>&nbsp;Stock</a></li>
        <li><a href="invoice?pagename=invoice"><i class="fa fa-file-text-o"></i>&nbsp;Invoice</a></li>
        <li><a href="sales?pagename=sales"> <i class="fa fa-list-alt"></i>&nbsp;Sales</a></li>
         <?php
            	if($role=='admin'){
  		  ?>
        <li><a href="reports?pagename=report"><i class="fa fa-bar-chart"></i>&nbsp;Reports</a></li>
        <?php
				}
		?>
        <li><a href="returns?pagename=returns"><i class="fa fa-undo"></i>&nbsp;G / Returns</a></li>
        <li><a href="staff?pagename=staff"><i class="fa fa-user-md"></i>&nbsp;Staff</a></li>
        <li><a href="expenses?pagename=expenses"><i class="fa fa-inr"></i>&nbsp;Expenses</a></li>
        
            <?php
            	if($role=='admin'){
  		  ?>
            <li><a href="admin?pagename=admin"><i class="fa fa-user"></i>&nbsp;Admin</a></li>
            <?php 
  			}
  		  ?>
        <li><a href="masterkey?pagename=master"><i class="fa fa-key"></i>&nbsp;Master Key</a></li>
            <li ><a href="logout.php"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
