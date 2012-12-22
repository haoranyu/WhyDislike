<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('我的互动 - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('center');?>
<?php js('jquery.min');?>
</head>
<body>
<?php include("header.php")?>
<div class="main">
	<div class="friend f14">
		<ul>
		<?php if(sizeof($firendList)>0){?>
			<?php foreach($firendList as $friend){?>
				<?php if($att){?>
					<li name="<?php p($friend['fuid']);?>">
					<div class="head"><?php getHead($friend['fuid'],'m',68);?></div>
					<div class="info">
						<span class="f14 b name"><a href="<?php p($webinfo["webhost"]);?>u/<?php p($friend['fuid']);?>/" target="_blank"><?php p(getName($friend['fuid']));?></a></span><br/>
						<span class="f12"><?php $description = subString(getDescription($friend['fuid']),0,14);p(($description=='')?'他还暂时没有写个人简介':$description);?></span><br/>
						<input class="offatt" type="submit" value="-取消关注" wd="attention" uid="<?php p($friend['fuid']);?>">
					</div>
					</li>
				<?php }else{?>
					<li>
					<div class="head"><?php getHead($friend['uid'],'m',68);?></div>
					<div class="info">
						<span class="f14 b name"><a href="<?php p($webinfo["webhost"]);?>u/<?php p($friend['uid']);?>/" target="_blank"><?php p(getName($friend['uid']));?></a></span><br/>
						<span class="f12"><?php $description = subString(getDescription($friend['uid']),0,14);p(($description=='')?'他还暂时没有写个人简介':$description);?></span><br/>
						<a class="offatt f12" href="<?php p($webinfo["webhost"]);?>u/<?php p($friend['uid']);?>/" target="_blank">他的主页</a>
					</div>
					</li>
				<?php }?>
			<?php }?>
		<?php }else{?>
			<?php if(!$att){?>
			<div style="margin:10px;line-height:28px;">
				你现在还没有被粉丝关注。<br/>你可以通过下面的方式邀请朋友们来关注你。<br/>
				<?php include("share.php")?>
			</div>
			<?php }else{?>
				<div style="margin:10px;line-height:28px;">你现在还没有关注的用户，请先去关注一些其它用户。<br/></div>
			<?php }?>
		<?php }?>
		<input id="uid" type="hidden">
		</ul>
		<div class="clear"></div>
		<?php p($pagebar);?>
	</div>
	<div class="sidebar">
		<?php include("user_info.php")?>
		<div class="share">
		</div>
	</div>
</div>
<?php include('footer.php');?>
<script>
$(document).ready(function(){
	$("[wd='attention']").click ( function () {
		$("#uid").val($(this).attr("uid"));
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/att.php');?>',
		type: 'POST',
		data:{ uid: "<?php p($_SESSION['user']['uid']);?>", fuid: $(this).attr("uid") },
		dataType: 'json',
		timeout: 3000,
		error: function(){
		 alert('很抱歉，取消关注失败！');
		},
		success: function(data){
			$("[name='"+$("#uid").val()+"']").slideToggle("slow");
			$("[wd='attCount']").html(parseInt($("[wd='attCount']").html())-1);
		}
		});
	});
});
</script>
</body>