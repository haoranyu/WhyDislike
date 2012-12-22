<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p($tagInfo['tag'].' - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('center');?>
<?php css('popup');?>
<?php js('jquery.min');?>
</head>
<body>
<?php include("header.php")?>
<div class="main">
	<div class="feed f14">
		<ul>
		<?php
		if(sizeof($tagComplainList)>0){
			foreach($tagComplainList as $complain){
				p('<li name="'.$complain['cid'].'">');
					p('<span class="f12 b dark">'.(($complain['from']==0)?'某人':getName($complain['from'])).'</span><span class="f12 desc">'.(getDescription($complain['from'])==''?'新鲜小友一枚':subString(getDescription($complain['from']),0,8)).'</span> <span class="f12 taglist"> •</span><span class="f12 taglist">'.date("Y-m-d H:i",$complain['time']).'</span>');
					p('<div>'.$complain['complain'].'</div>');
					if($complain['correct']&&$complain['reply']!=''){
						p('<div class="quote quoteplue f12">已回应：'.$complain['reply'].'</div>');
					}
				p('</li>');
			}
		}?>
		</ul>
		<div class="clear"></div>
		<?php p($pagebar);?>
	</div>
	<div class="sidebar">
		<div class="topic">
		<span class="f14 b">话题：<?php p($tagInfo['tag'])?></span>
		<span class="f12 desc"><?php p($tagInfo['desc'])?></span>
		<?php if(getRelation($_SESSION['user']['uid'],$tagInfo['tid'])){?>
		<input class="att" type="button" value="已关注" wd="attention" >
		<?php }else{?>
		<input type="button" value="+ 关注" wd="attention"/>
		<?php }?>
		</div>
	</div>
</div>
<?php include('footer.php');?>
<script>
<?php if($isLogin){?>
$(document).ready(function(){
	$("[wd='attention']").click ( function () {
		if($("[wd='attention']").val()=="取消关注"){
			var offAtt = true;
		}
		else{
			var offAtt = false;
		}
		$("[wd='attention']").val("载入中...");
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/attag.php');?>',
		type: 'POST',
		data:{ uid: "<?php p($_SESSION['user']['uid']);?>", tid: "<?php p($tagInfo['tid']);?>" },
		dataType: 'html',
		timeout: 3000,
		error: function(){
		 alert('很抱歉，关注失败！');
		 $("[wd='attention']").val("关注+");
		},
		success: function(html){
			if(offAtt==true){
			$("[wd='attention']").val("关注+");
			}
			else{
			$("[wd='attention']").val("已关注");
			}
		}
		});
	});
	$("[wd='attention']").mouseover(function () {
		if($("[wd='attention']").val()=="已关注"){
			$("[wd='attention']").val("取消关注");
		}
	});
	$("[wd='attention']").mouseout(function () {
		if($("[wd='attention']").val()=="取消关注"){
			$("[wd='attention']").val("已关注");
		}
	});
});
<?php }?>
</script>
</body>