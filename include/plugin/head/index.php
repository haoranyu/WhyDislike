<?php
	include("../../core.php");
if(!$isLogin){
	header("location:".$webinfo["webhost"]);
}
	$uptypes=array('image/jpg',
	'image/jpeg',
	'image/png8',
	'image/png24',
	'image/x-png',
	'image/pjpeg',
	'image/png',
	'image/gif');
	$max_file_size=2000;   //大小限制, 单位KB
	$file_path="temp/"; //原始头像存储路径
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$condition = "SUCCESS";
		if (!is_uploaded_file($_FILES["upfile"]["tmp_name"])){
		$condition = "FILE";
		}
		else{
			$file = $_FILES["upfile"];
			if($max_file_size < $file["size"]/1024 ){
			$condition = "SIZE";
			}
			if(!in_array($file["type"], $uptypes)){
			$condition = "TYPE";
			}
			$filename= $file["tmp_name"];
			$image_size = getimagesize($filename);
			$pinfo=pathinfo($file["name"]);
			$ftype=$pinfo["extension"];
			$destination = $file_path.$_SESSION['user']['uid'].".".$ftype;
			if(!move_uploaded_file ($filename, $destination)){
			$condition = "MOVE";
			}
			$pinfo=pathinfo($destination);
			$fname=$pinfo["basename"];
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title>上传头像</title>
<?php css('head')?>
<?php js('jquery.min')?>
<?php js('jquery.Jcrop.min')?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#target").Jcrop({
			aspectRatio:1,
			onChange:showCoords,
			onSelect:showCoords
		});	
		//响应自onChange,onSelect事件
		function showCoords(obj){
			$("#x").val(obj.x);
			$("#y").val(obj.y);
			$("#w").val(obj.w);
			$("#h").val(obj.h);
			if(parseInt(obj.w) > 0){
				//计算预览区域图片缩放的比例
				var rx = $("#preview_box").width() / obj.w; 
				var ry = $("#preview_box").height() / obj.h;
				//通过比例值控制图片的样式与显示
				$("#preview").css({
					width:Math.round(rx * $("#target").width()) + "px",	//预览图片宽度为计算比例值与原图片宽度的乘积
					height:Math.round(rx * $("#target").height()) + "px",	//预览图片高度为计算比例值与原图片高度的乘积
					marginLeft:"-" + Math.round(rx * obj.x) + "px",
					marginTop:"-" + Math.round(ry * obj.y) + "px"
				});
			}
		}
		$("#crop_submit").click(function(){
			if(parseInt($("#x").val())>=0){
				$("#crop_form").submit();	
			}else{
				alert("要先在图片上划一个选区再单击确认剪裁的按钮！");	
			}
		});
	});
</script>
</head>
<body>
<div class="upload">
<form enctype="multipart/form-data" method="post" name="upform">
<input name="upfile" type="file" onchange="$('#submit').trigger('click')">
<input type="submit" id="submit" value="上传" style="display:none">
</form>
</div>
<form action="update.php" method="post" id="crop_form">
<input type="hidden" id="x" name="x" />
<input type="hidden" id="y" name="y" />
<input type="hidden" id="w" name="w" />
<input type="hidden" id="h" name="h" />
<div id="head">
<?php
if(isset($condition)){
	if($condition == "SUCCESS"){
	echo '<div class="pic"><img src='.$file_path.$fname.' id="target" alt="" /></div>';
	echo '<div class="preview"><div id="preview_box" ><img src='.$file_path.$fname.' id="preview" alt="Preview" /></div><div class="quote">头像预览</div><input type="hidden" name="src" value="'.$file_path.$fname.'" /></div>';
	echo '<div class="cut"><input type="button" value="确认剪裁" id="crop_submit" /> 请在上图中选择头像区域</div>';
	}
	elseif($condition == "FILE"){
	echo '文件上传失败';
	}
	elseif($condition == "SIZE"){
	echo '文件太大了！';
	}
	elseif($condition == "TYPE"){
	echo '仅支持Jpg、Gif、Png格式';
	}
	elseif($condition == "MOVE"){
	echo '文件移动失败';
	}
}
else{?>
<div class="pic"><div style="margin:14px;">等待你的靓照被上传……<br/>请使用2M以内的jpg、gif、png图片。</div></div>
<div class="preview"><div id="preview_box"></div><div class="quote">头像预览</div></div>
<?php }?>
</div>
</form>
</body>
</html>