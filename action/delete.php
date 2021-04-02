<?php
session_start();
	include('class.php');
	$delObj=new database();
	if(isset($_GET['custDelete']) && $_GET['custDelete']=='custDelete'){
		$id=$_GET['id'];
		$table="`customer`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			$_SESSION['msg']="Deleted Successfully";
		}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../customer?pagename=customer");
	}
	elseif(isset($_GET['supDelete']) && $_GET['supDelete']=='supDelete'){
		$id=$_GET['id'];
		$table="`supplier`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			$_SESSION['msg']="Deleted Successfully";
		}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../supplier?pagename=supplier");
	}
	elseif(isset($_GET['deleteUser']) && $_GET['deleteUser']=='deleteUser'){
		$id=$_GET['id'];
		$table="`users`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	elseif(isset($_GET['del_purchase_temp']) && $_GET['del_purchase_temp']=='del_purchase_temp'){
		$id=$_GET['id'];
		$id=(int)$id;
		$shop=$_GET['shop'];
		$table="`purchase_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	elseif(isset($_GET['deleteStock']) && $_GET['deleteStock']=='deleteStock'){
		$id=$_GET['id'];
		$table="`stock`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			$_SESSION['msg']="Deleted Successfully";
		}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../stock?pagename=stock");
	}
	elseif(isset($_GET['deleteModel']) && $_GET['deleteModel']=='deleteModel'){
		$id=$_GET['id'];
		$table="`models`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			$_SESSION['msg']="Deleted Successfully";
		}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../masterkey?pagename=master");
	}
	elseif(isset($_GET['delete_temp']) && $_GET['delete_temp']=='delete_temp'){
		$id=$_GET['id'];
		$quantity=$_GET['quantity'];
		$ready_id=$_GET['ready_id'];
		$shop=$_GET['shop'];
		$table="`invoice_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			$update=$delObj->update("`stock`","`quantity`=`quantity`+'$quantity'","`id`='$ready_id'");
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	elseif(isset($_GET['delete_temp_service']) && $_GET['delete_temp_service']=='delete_temp_service'){
		$id=$_GET['id'];
		$shop=$_GET['shop'];
		$table="`service_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			//$update=$delObj->update("`stock`","`quantity`=`quantity`+'$quantity'","`id`='$ready_id'");
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	elseif(isset($_GET['delete_qtemp']) && $_GET['delete_qtemp']=='delete_qtemp'){
		$id=$_GET['id'];
		$shop=$_GET['shop'];
		$table="`quot_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	elseif(isset($_GET['delete_qtemp_service']) && $_GET['delete_qtemp_service']=='delete_qtemp_service'){
		$id=$_GET['id'];
		$shop=$_GET['shop'];
		$table="`quotservice_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}
	
	elseif(isset($_GET['delete_ptemp']) && $_GET['delete_ptemp']=='delete_ptemp'){
		$id=$_GET['id'];
		$shop=$_GET['shop'];
		$table="`performa_temp`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
			echo "Deleted Successfully";
		}
		else{
			echo $delete;
		}
	}

	elseif(isset($_GET['deleteinvoice']) && $_GET['deleteinvoice']=='deleteinvoice'){
		$id=$_GET['id'];
        $sales=$delObj->get_rows("sales","*","invoice_id='$id'");
		$table="`invoice`";
		$where=" `id` = '$id'";
		$delete=$delObj->delete($table,$where);
		if($delete===true){
            foreach($sales as $sale){
                $update=$delObj->update("stock","quantity=quantity+'$sale[quantity]'","id='$sale[product_id]'");
            }
			$_SESSION['msg']="Deleted Successfully";
		}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../reports/?pagename=report");
	}

	elseif(isset($_GET['deleteinvoiceservice']) && $_GET['deleteinvoiceservice']=='deleteinvoiceservice'){
		$id=$_GET['id'];
		//$service=$delObj->get_rows("servicelist","*","service_id='$id'");
		$table="`service`";
		$table2="`servicelist`";
		$where=" `id` = '$id'";
		$where2=" `service_id` = '$id'";
		$delete=$delObj->delete($table,$where);
		$delete2=$delObj->delete($table2,$where2);
		if($delete===true){
			if($delete2===true){
			$_SESSION['msg']="Deleted Successfully";
		}
	}
		else{
			$_SESSION['err']=$delete;
		}
		header("Location:../reports/invoiceservice.php?pagename=report");
	}
	
    elseif(isset($_GET['deleteTerm'])){
        $id=$_GET['id'];
        $delete=$delObj->delete("`add_terms`","`id`='$id'");
        if($delete===true){
            $_SESSION['msg']="Successfully Deleted!";
        }
        else{
            $_SESSION['err']=$delete;
        }
        header("Location:../masterkey/?pagename=master&section=add_terms");
    }
?>