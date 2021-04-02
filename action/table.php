<?php
	include_once("class.php");
	class table extends database{
		private $table_structure;
		
		function __construct(){
			parent::__construct();
		}
		
		function create_table_structure($columns,$class='',$align='center'){
			$table_top="<table class='$class'><thead>";
			$table_top.="<tr>";
			$i=0;
			$align="text-align:$align";
			foreach($columns as $column){
				$table_top.="<th style='$align;'";
				if(is_array($column)){
					$table_top.=" width='$column[width]'";
					$table_top.=">$column[name]";
				}
				else{
					$table_top.=">$column";
				}
				$table_top.="</th>";
			}
			$table_top.="</tr></thead>";
			return $table_top;
		}
		
		function create_table_row($array,$fields,$height,$align,$buttons){
			$colcount=sizeof($fields);
			$table_row="";
			if(is_array($array)){
				foreach($array as $row){
					$table_row.="<tr height='$height'>";
						foreach($fields as $field){
							$table_row.="<td align='$align'>";
							if($field=='button'){
								foreach($buttons as $button){
									$parameters=$button['parameter'];
									$parameterval=array();
									foreach($parameters as $key=>$val){
										if(isset($row[$val])){
											$parameterval[$key]=$row[$val];
										}else{
											$parameterval[$key]=$val;
										}
									}
									$table_row.=$this->create_button($button,$parameterval);
								}
							}
							else{ 
								if(is_array($field)){
									if($field['function']=='date'){
										if($row[$field['field']]!='' && $row[$field['field']]!='0000-00-00')
										{	
											$table_row.=date('d-m-Y',strtotime($row[$field['field']]));
										}
										else{$table_row.="----";}
									}else{
										$table_row.=$field['function']($row[$field['field']]);
									}
								}else{
									$table_row.="$row[$field]";
								}
							}
							$table_row.="</td>";
						}
					$table_row.="</tr>";
				}
			}
			else{
				$table_row="<tr><td align='center' colspan='$colcount' style='color:#af1313'>No Records Found!</td></tr>";
			}
			return $table_row;
		}
		
		function create_button($button,$parameterval){
			$tag=$button['tag'];
			$text=$button['text'];
			$class=$button['class'];
			if(isset($button['attributes'])){
				$attributes=$button['attributes'];
			}
			else{ $attributes=''; }
			if($tag=='a'){
				$href=$button['href']."?";
				foreach($parameterval as $parameter=>$value){
					$href.="$parameter=$value&";
				}
				$href=rtrim($href,'&');
				$button="<a href='$href' class='$class' $attributes>$text</a> ";
			}
			elseif($tag=='button'){
				$function=$button['function'];
				$parameters="'";
				$parameters.=implode("','",$parameterval);
				$parameters.="'";
				$button='<button type="button" class="'.$class.'" onClick="'.$function.'('.$parameters.')'.'" '.$attributes.'>';
				$button.=$text;
				$button.="</button> ";
			}
			return $button;
		}
		
		function end_table_structure(){
			$table_bottom="</table>";
			return $table_bottom;
		}
		
		function pagination($ref,$pages,$page,$pagefilters=''){
			$pagination="<div class='text-center'>";
			$current="";
			if($pages>1){
				if($page!=1){
					$pagination.=createpagelinks($ref,$page-1,"Prev",$current,$pagefilters);
				}
				for($i=1;$i<=$pages;$i++){
					if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
						if($i==$page){$current=true;}else{$current='';}
						$pagination.=createpagelinks($ref,$i,$i,$current,$pagefilters);
					}
					elseif($pages>5 && ($i==4 || $i==$pages-3)){
						$pagination.=" <ul class='pagination pagination-sm'><li><a>...</a></li></ul> ";
					}
				}
				if($page!=$pages){
					$pagination.=createpagelinks($ref,$page+1,"Next",$current,$pagefilters);
				}
			}
			$pagination.="</div>";
			return $pagination;
		}
		
		function createpagelinks($ref,$page,$link,$current,$pagefilters){
			$pagelink="<ul class='pagination pagination-sm'>";
			$pagelink.="<li";
			if($current!=''){$pagelink.=" class='active'";}
			$pagelink.="><a href='".$ref."&page=".$page.$pagefilters."'>".$link."</a></li>";
			$pagelink.="</ul> ";
			return $pagelink;
		}
		
		function create_table($array,$fields,$columns,$class='',$height='30',$align='center',$pagination="",$button=""){
			$this->table_structure.=$this->create_table_structure($columns,$class,$align);
			$this->table_structure.=$this->create_table_row($array,$fields,$height,$align,$button);
			$this->table_structure.=$this->end_table_structure();
			if($pagination!=''){
				$ref=$pagination[0];
				$pages=$pagination[1];
				$page=$pagination[2];
				if(isset($pagination[3])){
					$pagefilters=$pagination[3];
				}else{$pagefilters='';}
				$this->table_structure.=$this->pagination($ref,$pages,$page,$pagefilters);
			}
			return $this->table_structure;
		}
		
		function get_table_structure($table,$columns,$where,$order,$count,$page,$table_structure){
			$offset=($page-1)*$count;
			$limit="$offset,$count";
			$array=$this->get_rows($table,$columns,$where,$order,$limit);
			$rowcount=$this->get_count($table,$where);
			$pages=ceil($rowcount/$count);
			$fields=$table_structure[0];
			$columns=$table_structure[1];
			$class=$table_structure[2];
			$height=$table_structure[3];
			$align=$table_structure[4];
			$ref=$table_structure[5];
			if(isset($table_structure[6])){
				$pagefilters=$table_structure[6];
			}else{$pagefilters='';}
			$buttons=$table_structure['buttons'];
			$pagination=array($ref,$pages,$page,$pagefilters);
			return $this->create_table($array,$fields,$columns,$class,$height,$align,$pagination,$buttons);
		}
		
	}/*
	<!--$obj=new table();
	$count=2;
	if(isset($_GET['page'])){$page=$_GET['page'];}
	else{$page=1;}
	$offset=($page-1)*$count;
	$limit="$offset,$count";
	$pagefilters="";
	if(isset($_GET['name'])){
		$name=$_GET['name'];
		$where="`name` like '$name%'";	
		$pagefilters="&name=$name";
	}
	else{$where=1;}
	
	$button1['tag']="a";
	$button1['href']="table.php";
	$button1['class']="btn";
	$button1['text']="Table";
	$button1['parameter']['id']="id";
	$button1['parameter']['name']="name";
	
	$button2['tag']="button";
	$button2['class']="btn";
	$button2['text']="Edit";
	$button2['function']="Edit";
	$button2['parameter']['id']="id";
	$button2['parameter']['name']="name";
	
	$array=$obj->get_rows("`raw_material`","*",$where,"id","$limit");
	$rowcount=$obj->get_count("`raw_material`",$where);
	$pages=ceil($rowcount/$count);
	$fields=array("pro_id","name","type","button");
	$columns=array("Product id","Name","Type","Action");
	$pagination=array("table.php?",$pages,$page,$pagefilters);
	echo $obj->create_table($array,$fields,$columns,"",30,"center",$pagination,array($button1,$button2));	
	/*$pagefilters="";
	if(isset($_GET['name'])){
		$name=$_GET['name'];
		$where="`name` like '$name%'";	
		$pagefilters="&name=$name";
	}
	else{$where=1;}
	$fields=array("pro_id","name","type");
	$columns=array("Product id","Name","Type");
	$table_structure=array($fields,$columns,"table",50,"center","table.php?",$pagefilters);
	echo $obj->get_table_structure("`raw_material`","*",$where,"id",$count,$page,$table_structure);*/	
?>