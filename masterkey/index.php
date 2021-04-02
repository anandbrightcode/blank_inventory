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
include('../action/class.php');  
$obj=new database();
$arr=$obj->get_rows("`category`","*","`shop`='$shop'",'name asc');
$category=array();
$array=array();
if(is_array($arr)){
	foreach($arr as $cat){
		$category[]=$cat['name'];
		$array[]=$cat;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Master Key</title>
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        
        <link href="../bootstrap/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" media="screen" />
        <link href="../typeselect/typeselect.css" rel="stylesheet" />
  		<style>
            .selectlist{
                width: inherit;
                position: absolute;
            }
            .selectlist button{
                padding: 1px 5px;
                font-size: 14px;
                line-height: 1.5;
                border-radius: 0px;
                /*border: 1px solid transparent;*/
                border-left: 1px solid #ccc;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                outline: 0;
            }
            .selectlist button:hover,
            .selectlist button:active,
            .selectlist button:focus{
                background-color: #2A7CEB;
                color: #FFFFFF;
                border: 1px solid #63cdff;
            }
            .toselect{
                width: 100%;
            }
        </style>
		<script src="../bootstrap/js/jquery.js" type="text/javascript"></script>
     <script src="../typeselect/typeselect.js" type="text/javascript"></script>
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.21/datatables.min.css"/>
 	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.21/datatables.min.js"></script>

    
	</head>

    <body>
    	<?php include "../header.php";?>
   		<div class="container">
     		<div class="row">
       			<div class="col-md-12">
					<?php if(isset($_SESSION['err'])){echo "<h4 class='text-center text-danger'>".$_SESSION['err']."</h4>";unset($_SESSION['err']);}?>
                    <?php if(isset($_SESSION['msg'])){echo "<h4 class='text-center text-success'>".$_SESSION['msg']."</h4>";unset($_SESSION['msg']);}?>
                    <div class="panel panel-success">
                        <div class="panel-heading"><font size="+2">Master Key</font></div>
                        <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li class=" <?php if(isset($_GET['cpage']) || isset($_GET['mpage'])){}else {echo "active"; }?>"><a data-toggle="pill" href="#categoryTab">Category</a></li>
                                <li><a data-toggle="pill" href="#company">Add Company</a></li>
                                <li class=" <?php if(isset($_GET['cpage'])){echo "active"; }?>"><a data-toggle="pill" href="#viewc">View Companies</a></li>
                                <li><a data-toggle="pill" href="#model">Add Model</a></li>
                                <li class=" <?php if(isset($_GET['mpage'])){echo "active"; }?>"><a data-toggle="pill" href="#viewm">View Model</a></li>
                                <li class="<?php if($section=="add_terms"){echo "active"; }?>"><a data-toggle="pill" href="#add_termsDiv">Additional Terms</a></li>
                            </ul>
          
                            <div class="tab-content">
                                <div id="categoryTab" class="tab-pane fade <?php if(isset($_GET['cpage']) || isset($_GET['mpage'])){}else {echo " in active"; }?>">
                                    <form action="../action/insert.php" method="post" class="row" id="categoryForm">
                                        <div class="col-md-12"><br />
                                            <div class="panel" id="catForm">
                                                <div class="panel-heading">
                                                    <font size="+2" class="cat_save">Add Category</font>
                                                    <font size="+2" class="cat_update" style="display:none;">Update Category</font>
                                                    <button type="button" class="btn btn-primary btn-sm pull-right" onclick="viewThis('table');">View Categories</button>
                                                </div>
                                                <div class="panel-body">
                                                    <table class="table" style="width:90%;" align="center">
                                                        <tr>
                                                            <td><b>Category Name</b></td>
                                                            <td width="80%">
                                                                <input type="text" name="name" id="cat_name" class="form-control" required="required" />
                                                                <input type="hidden" name="id" id="id"  />
                                                                <input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>"  />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>CGST</b></td>
                                                            <td width="80%"><input type="text" name="cgst" id="cgst" class="form-control" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>SGST</b></td>
                                                            <td width="80%"><input type="text" name="sgst" id="sgst" class="form-control" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>IGST</b></td>
                                                            <td width="80%"><input type="text" name="igst" id="igst" class="form-control" /></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <input type="submit" name="add_category" value="Save" class="btn btn-success cat_save" />
                                                                <input type="submit" name="update_category"  value="Update" class="btn btn-success cat_update" style="display:none;" />
                                                                <input type="reset"  value="Cancel" class="btn btn-danger cat_update" style="display:none;" onclick="viewThis('change')" />
                                                                
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="panel" id="catTable" style="display:none;">
                                                <div class="panel-heading">
                                                    <font size="+2">Category</font>
                                                    <button type="button" class="btn btn-primary btn-sm pull-right" onclick="viewThis('form');">Add Category</button>
                                                </div>
                                                <div class="panel-body">
                                                    <table class="table" style="width:90%;" align="center">
                                                        <tr>
                                                            <th style="text-align:center">Category Name</th>
                                                            <th style="text-align:center">CGST</th>
                                                            <th style="text-align:center">SGST</th>
                                                            <th style="text-align:center">IGST</th>
                                                            <th style="text-align:center">Action</th>
                                                        </tr>
                                                        <?php if(!empty($array)){foreach($array as $categories){?>
                                                        <tr>
                                                            <td align="center"><?php echo $categories['name']; ?></td>
                                                            <td align="center"><?php echo $categories['cgst']; ?></td>
                                                            <td align="center"><?php echo $categories['sgst']; ?></td>
                                                            <td align="center"><?php echo $categories['igst']; ?></td>
                                                            <td align="center">
                                                                <button type="button" class="btn btn-info btn-sm fa fa-edit" onclick="editThis('<?php echo $categories['id']; ?>');"></button>
                                                            </td>
                                                        </tr>
                                                        <?php }}else{ echo "<tr><td colspan='5' align='center' class='text-danger'>No Category Inserted!</td></tr>"; }?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="company" class="tab-pane fade ">
                                    <form action="../action/insert.php" method="post" class="row" id="companyForm">
                                        <div class="col-md-12"><br />
                            				<legend id="comp_save">Add Company</legend>
                            				<legend id="comp_update" style="display:none;">Update Company</legend>
                                            <table class="table" style="width:90%;" align="center">
                                                <tr>
                                                    <td><b>Company Name</b></td>
                                                    <td width="80%">
                                                    	<input type="text" name="name" id="name" class="form-control" required="required" />
                                                    	<input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />
                                                    	<input type="hidden" name="id" id="comp_id" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Select Categories</b></td>
                                                    <td>
                                                        <?php $i=0;
														if(is_array($arr)){
															foreach($arr as $val){ $i++; ?>
                                                        <div style="width:33%; float:left; position:relative;">
                                                            <label class="checkbox-inline">
                                                            	<input type="checkbox" name="category[]" value='<?php echo $val['id']; ?>'><?php echo $val['name']; ?>
                                                           	</label>
                                                        </div>
                                                        <?php if($i%3==0){echo "<br />";}
															}
														} 
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                    	<input type="submit" name="add_company" value="Save" class="btn btn-success comp_save" />
                                                		<input type="submit" name="update_company"  value="Update" class="btn btn-success comp_update" style="display:none;" />
                                                		<input type="reset"  value="Cancel" class="btn btn-danger comp_update" style="display:none;" onclick="viewThis('comp')" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                                <div id="viewc" class="tab-pane fade <?php if(isset($_GET['cpage']) ) {echo " in active"; }?>">
                                    <div class="row">
                                        <div class="col-md-12 table-responsive"><br />
                                            <table class="table table-hover table-striped">
                                                <tr>
                                                    <th style="text-align:center">Sl. No.</th>
                                                    <th style="text-align:center">Company Name</th>
                                                    <th style="text-align:center">Categories</th>
                                                    <th style="text-align:center">Action</th>
                                                </tr>
                                                <?php
                                                    $ccount=20;
                                                    if(isset($_GET['cpage'])){$cpage=$_GET['cpage'];}else{$cpage=1;}
                                                    $coffset=($cpage-1)*$ccount;
                                                    $crun=$obj->get_rows("`company`","*","`shop`='$shop'","`id`","$coffset,$ccount");
                                                    $crowcount=$obj->get_count("`company`","`shop`='$shop'");
                                                    $cpages=ceil($crowcount/$ccount);
                                                    if(is_array($crun)){$c=$coffset;
                                                        foreach($crun as $carray){$c++;
														
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $c; ?></td>
                                                    <td align="center"><?php echo $carray['name']; ?></td>
                                                    <td align="justify">
														<?php 
															$categoryarray=explode(',', $carray['category']); 
															$categories=array();
															foreach($categoryarray as $categoryval){
																$catarray=$obj->get_details("`category`","`name`","`id`='".$categoryval."'");
																$categories[]= $catarray['name'];
															}
															echo implode(', ',$categories);
														?>
                                                    </td>
                                                    <td align="center">
                                                    	<button type="button" class="btn btn-info btn-sm" onclick="editCompany('<?php echo $carray['id']; ?>')"><i class="fa fa-edit"></i></button>
                                                    </td>
                                                </tr>
                                                <?php		
                                                        }	
                                                    }
                                                    else{
                                                        echo "<tr><td colspan='4' class='text-center text-danger'>No Records Found!!</td></tr>";
                                                    }
                                                    if($cpages>1){ echo "<tr><td colspan='4' align='center'>";
                                                        if($cpage>1){
                                                ?>
                                                    <ul class="pagination pagination-sm">
                                                        <li><a href="../masterkey?pagename=master&cpage=<?php echo $cpage-1; ?>">Prev</a></li>
                                                    </ul>
                                                <?php		
                                                        }
                                                        for($ci=1;$ci<=$cpages;$ci++){
                                                ?>
                                                    <ul class="pagination pagination-sm">
                                                        <li <?php if($ci==$cpage) echo "class='active'"; ?>><a href="../masterkey?pagename=master&cpage=<?php echo $ci; ?>"><?php echo $ci; ?></a></li>
                                                    </ul>
                                                <?php		
                                                        }	
                                                        if($cpage!=$cpages){
                                                ?>
                                                    <ul class="pagination pagination-sm">
                                                        <li><a href="../masterkey?pagename=master&cpage=<?php echo $cpage+1; ?>">Next</a></li>
                                                    </ul>
                                                <?php		
                                                        }
                                                        echo "</td></tr>";
                                                    }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="model" class="tab-pane fade">
                                    <form action="../action/insert.php" method="post" class="row">
                                        <div class="col-md-12"><br />
                                            <legend>Add Model</legend>
                                            <table class="table" style="width:90%;" align="center">
                                                <tr>
                                                    <td><b>Select Category</b></td>
                                                    <td width="60%" colspan="2">
                                                        <select name="category" id="category" class="form-control typeselect" onchange="getCompany(this.value)" required="required">
                                                            <option value="">Select</option>
                                                            <?php if(is_array($arr)){
																foreach($arr as $val){ ?>
                                                            <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                                            <?php }
															} 
															?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Select Company</b></td>
                                                    <td colspan="2">	
                                                        <select name="company_id" id="company_id" class="form-control" required="required">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Enter Model</b></td>
                                                    <td>
                                                        <div id="modelDiv">
                                                            <input type="text" name="model[]" id="model1" class="form-control" required="required" />
                                                        </div>
                                                    	<input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />
                                                    	<input type="hidden" name="count" id="count" value="1" />
                                                    </td>
                                            		<td><button type="button" class="btn btn-primary btn-sm" onclick="addModel();" >Add Model</button></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <input type="submit" name="add_model" value="Save" class="btn btn-success" />
                                                        <input type="reset" value="Cancel" class="btn btn-danger" onclick="removeAll();" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                                <div id="viewm" class="tab-pane fade <?php if(isset($_GET['mpage']) ) {echo " in active"; }?>">
                                    <div class="row">
                                        <div class="col-md-12  table-responsive"><br />
                                            <table class="table datatable  table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center">Sl. No.</th>
                                                    <th style="text-align:center">Category</th>
                                                    <th style="text-align:center">Company Name</th>
                                                    <th style="text-align:center">Model</th>
                                                    <th style="text-align:center">Action</th>
                                                </tr>
                                                </thead>
                                                   <tbody>
                                                <?php
                                                    $mcount=20;
                                                    if(isset($_GET['mpage'])){$mpage=$_GET['mpage'];}else{$mpage=1;}
                                                    $moffset=($mpage-1)*$mcount;
                                                    $mrun=$obj->get_rows("`models`","*","`shop`='$shop'","`company_id`");
                                                    $mrowcount=$obj->get_count("`models`","`shop`='$shop'");
                                                    $mpages=ceil($mrowcount/$mcount);
                                                    if(is_array($mrun)){$m=$moffset;
                                                        foreach($mrun as $marray){$m++;
                                                        	$company_id=$marray['company_id'];
                                                        	$company=$obj->get_details("`company`","*","`id`='$company_id'");
                                                        	$category_id=$marray['category'];
                                                        	$cat_details=$obj->get_details("`category`","*","`id`='$category_id'");
															//print_r($cat_details);
                                                ?>
                                             
                                                <tr>
                                                    <td align="center"><?php echo $m; ?></td>
                                                    <td align="center"><?php echo $cat_details['name']; ?></td>
                                                    <td align="center"><?php echo $company['name']; ?></td>
                                                    <td align="center"><?php echo $marray['model']; ?></td>
                                                    <td align="center">
                                                    	<a href="../action/delete.php?deleteModel=deleteModel&id=<?php echo $marray['id']; ?>" onclick="return confirmDel()">
                                                        	<i class="btn btn-danger btn-xs fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php		
                                                        }	
                                                    }?>
                                                    
                                               
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="add_termsDiv" class="tab-pane fade <?php if($section=="add_terms") {echo " in active"; }?>"><br>
                                	<div class="row">
                                    	<div class="col-md-6">
                                            <form action="../action/insert.php" method="post" id="termsForm">
                                            	<legend class="term_head">Add Additional Terms</legend>
                                                <table class="table">
                                                	<tr id="addrow">
                                                    	<td>Terms:</td>
                                                        <td>
                                                        	<textarea name="terms[]" class="form-control terms first" rows="5" ></textarea>
                                                        </td>
                                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="addTerms();" >Add Terms</button></td>
                                                    </tr>
                                                	<tr id="updaterow" style="display:none">
                                                    	<td>Terms:</td>
                                                        <td><textarea name="uterms" id="uterms" class="form-control" rows="5" ></textarea></td>
                                                    </tr>
                                                    <tr>
                                                    	<td colspan="3">
                                                         	<input type="hidden" name="shop" value="<?php echo $shop; ?>">
                                                           	<input type="hidden" name="id" id="atid">
                                                          	<input type="submit" name="add_additionalterms" class="btn btn-sm btn-success" value="Save" >
                                                        	<button type='reset' class='btn btn-sm btn-danger' value="<?php echo $section; ?>" onclick="removeTerms();">Cancel</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                        	<legend>Additional Terms</legend>
                                            <?php
                                                include('../action/table.php');
                                                $obj4=new table();
                                                $count=10;
                                                $termpage=1;
                                                if(isset($_GET['page']) && $_GET['page']!='' && $_GET['page']>0){
                                                    $page=$_GET['page'];
                                                }
                                                else{$page=1;}
                                                $toffset=0;
                                                $tlimit="$toffset,$count";
												$terms=$obj->get_rows("`add_terms`","","","",$tlimit);
                                                if(is_array($terms)){$slno=$toffset;
                                                    foreach($terms as $key=>$val){$slno++;
                                                        $terms[$key]['slno']=$slno;
														$editbutton="<button type='button' class='btn btn-xs btn-info' onclick='editTerm($val[id],this)'><i class='fa fa-edit'></i></button>";
														$delbutton="<button type='button' class='btn btn-xs btn-danger' onclick='deleteTerm($val[id])'><i class='fa fa-trash'></i></button>";
														$terms[$key]['buttons']=$editbutton." ".$delbutton;
                                                    }
                                                }
                                                $rowcount=$obj4->get_count("`add_terms`","1");
                                                $termpages=ceil($rowcount/$count);
                                                $fields=array("slno","terms","buttons");
                                                $columns=array("Sl No.","Terms","Action");
                                                $termpagination=array("../masterkey/?pagename=master&section=add_terms",$termpages,$termpage);
												
                                                echo $obj4->create_table($terms,$fields,$columns,"table table-striped table-condensed",30,"center",$termpagination);		
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end of panel -->
       			</div><!-- end of col-md-12 -->
   			</div><!-- end of row -->
  		</div><!-- end of container -->
		<script language="javascript">
		 $(document).ready(function(){
                $('.typeselect').typeselect();
				 $('.datatable').DataTable();
            });
           	function getCompany(str){
                var category=str;
                if(category!=''){
                    $.ajax({
                        type:'POST',
                        url:"add_master.php",
                        data:{category:category,get_company:'get_company'},
                        success: function(data){
                            $('#company_id').html(data);
                        }	
                    });
                }
            }
            
            function viewThis(str){
				var div=str;
				if(div=='form'){
					$('#catForm').show();
					$('#catTable').hide();	
				}
				else if(div=='change'){
					$('#catForm').show();
					$('#catTable').hide();
					$('.cat_update').hide();
					$('.cat_save').show();
					$('#cat_name').prop('readonly', false);
				}
				else if(div=='comp'){
					$('.comp_update').hide();
					$('.comp_save').show();
					$('#name').prop('readonly', false);
					$('#viewc').addClass('in');
					$('#viewc').addClass('active');
					$('#company').removeClass('in');
					$('#company').removeClass('active');
					$('#companyForm').attr('action', '../action/insert.php');	
				}else{
					$('#catForm').hide();
					$('#catTable').show();	
				}
			}
            
            function editThis(str){
                var id=str;
				var shop='<?php echo $shop; ?>';
                $.ajax({
                    type:'POST',
                    url:"../ajax_returns.php",
                    data:{id:id,shop:shop,get_category:'get_category',page:'master'},
                    dataType:"json",
                    success: function(data){
                        $('#id').val(id);
                        var name=data['name'];
                        var cgst=data['cgst'];
                        var sgst=data['sgst'];
                        var igst=data['igst'];
                        $('#cat_name').val(name);
                        //$('#cat_name').prop('readonly', true);
                        $('#cgst').val(cgst);
                        $('#sgst').val(sgst);
                        $('#igst').val(igst);
                        $('#catForm').show();
                        $('#catTable').hide();	
                        $('.cat_update').show();
                        $('.cat_save').hide();
						$('#categoryForm').attr('action', '../action/update.php');	
                    }	
                });
            }
            function editCompany(str){
				var id=str;
				var shop='<?php echo $shop; ?>';
				$.ajax({
					type:'POST',
					url:"../ajax_returns.php",
					data:{id:id,shop:shop,get_CompDetails:'get_CompDetails',page:'master'},
					dataType:"json",
					success: function(data){
						var name=data['name'];
						var category=data['category'];
						for(var k in category) {
							var a=category[k];
							if(a!=''){
								$('input[type=checkbox][value="'+a+'"]').prop('checked', true);
							}
						}
						$('#name').val(name);
						$('#comp_id').val(id);
						//$('#name').prop('readonly', true);
						$('#viewc').removeClass('in');
						$('#viewc').removeClass('active');
						$('#company').addClass('in');
						$('#company').addClass('active');
						$('.comp_save').hide();
						$('.comp_update').show();
						$('#companyForm').attr('action', '../action/update.php');	
					}	
				});
			}
			function getCompany(str){
				var category=str;
				var company="<option value=''>Select Company</option>";
				var shop='<?php echo $shop; ?>';
				$('#company_id').html(company);
				if(category!=''){
					$.ajax({
						type:'POST',
						url:"../ajax_returns.php",
						data:{category:category,shop:shop,page:'master',get_company:'get_company'},
						success: function(data){
							$('#company_id').html(data);
						}	
					});
				}
			}
			function confirmDel(){
				if(confirm('Are you sure you want to delete this?')){
					return true
				}
				else{
					return false;	
				}	
			}
			
			function addModel(){
				var count=$('#count').val(); 
				var prev="#model"+count;
				count++;$('#count').val(count);
				var input="<input type='text' name='model[]' id='model"+count+"' class='form-control' style='margin-top:10px;' />";
				var avl=$('#modelDiv').html();
				$(input).insertAfter(prev);
			}
			function removeAll(){
				var input="<input type='text' name='model[]' id='model1' class='form-control' required='required'/>";
				$('#modelDiv').html(input);
				var count=$('#count').val('1'); 
			}
			
            function showThis(str1,str2){
                var showdiv="#"+str1;
                var hidediv="#"+str2;
                $(showdiv).show();
                $(hidediv).hide();
            }

			function editModel(str){
				/*var id=str;
				var shop='<?php echo $shop; ?>';
				$.ajax({
					type:'POST',
					url:"../ajax_returns.php",
					data:{id:id,shop:shop,get_modelDetails:'get_modelDetails',page:'master'},
					//dataType:"json",
					success: function(data){
					alert(data)
						var name=data['name'];
						var category=data['category'];
						for(var k in category) {
							var a=category[k];
							if(a!=''){
								$('input[type=checkbox][value='+a+']').prop('checked', true);
							}
						}
						$('#name').val(name);
						$('#comp_id').val(id);
						$('#name').prop('readonly', true);
						$('#viewc').removeClass('in');
						$('#viewc').removeClass('active');
						$('#company').addClass('in');
						$('#company').addClass('active');
						$('.comp_save').hide();
						$('.comp_update').show();
						$('#companyForm').attr('action', '../action/update.php');	
					}	
				});*/
			}
			function addTerms(){
				var input="<hr><textarea name='terms[]' class='form-control terms' name='terms[]' rows='5' required></textarea>";
				$(input).insertAfter($('.terms').last());
			
			}
			
			function editTerm(id,ele){
				var term=$(ele).parent().siblings(":eq(1)").text();
				$('input[name="terms[]"]').removeAttr("required");
				$('#uterms').attr("required",true);
				$('#uterms').val(term);
				$('#atid').val(id);
				$('#termsForm').attr("action","../action/update.php");
				$('input[name="add_additionalterms"]').val("Update");
				$('input[name="add_additionalterms"]').attr("name","update_additionalterms");
				$('.term_head').text("Update Additional Terms");
				var sect=$('#termsForm').find("button[type='reset']").val();
				if(sect=='add_terms'){
					$('#termsForm').find("button[type='reset']").attr("onclick","window.location.reload()");
				}
				else{
					$('#termsForm').find("button[type='reset']").attr("onclick","reloadPage('add_terms')");
				}
				showThis('updaterow','addrow');
			}
			
			function removeTerms(){
				$('#add_termsDiv hr').remove();
				$('.terms').each(function() {
                    if(!$(this).hasClass("first")){
						$(this).remove();
					}
                });
			}
			
			function deleteTerm(id){
				if(confirm("Are you sure you want to delete this?")){
					window.location="../action/delete.php?deleteTerm=deleteTerm&id="+id;
				}
			}
       	</script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
    </body>
</html>
