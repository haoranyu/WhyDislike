<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p($userPageArray['name'].' - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('user');?>
<?php css('popup');?>
<?php js('jquery.min')?>
<?php js('md5')?>
</head>
<body>
<?php include("header.php")?>
<div class="top" style="background-image:url(<?php p($webinfo["webhost"])?>img/theme/<?php p($userPageArray['bg'])?>.jpg);">
	<div class="center">
		<div class="left">
			<div class="head">
			<?php getHead($userPageArray['uid'],'l');?>
			</div>
			<div class="name">
				<span class="n"><?php p($userPageArray['name'])?></span>
				<span class="b">
				<?php if(!$isLogin){?>
				<input class="att" type="button" value="+关注" wd="openLogin">
				<?php }else{?>
					<?php if(getRelation($_SESSION['user']['uid'],$userPageArray['uid'])){?>
					<input class="att" type="button" value="已关注" wd="attention">
					<?php }else{?>
					<input class="att" type="button" value="+关注" wd="attention">
					<?php }?>
				<?php }?>
				</span>
			</div>
			<div class="clear"></div>
			<?php if((getRenren($userPageArray['uid'])!=false)||(getWeibo($userPageArray['uid'])!=false)){?>
			<div class="social">
				<?php if(getRenren($userPageArray['uid'])!=false){?>
				<a class="myrr" href="<?php p(getRenren($userPageArray['uid']))?>" target="_blank"></a>
				<?php }?>
				<?php if(getWeibo($userPageArray['uid'])!=false){?>
				<a class="mywb" href="<?php p(getWeibo($userPageArray['uid']))?>" target="_blank"></a>
				<?php }?>
			</div>
			<div class="clear"></div>
			<?php }?>
			<div class="profile">
				<?php if($userPageArray['vip']!=0){?>
				<li class="vip vip<?php p($userPageArray['vip'])?>" ><span class="item">认证</span> <?php p(getVip($userPageArray['uid'])?getVip($userPageArray['uid']):'认证用户');?></li>
				<?php }?>
				<?php if(''!=$userPageArray['city']){?>
				<li class="icon icon1" ><span class="item">现居</span> <?php p($userPageArray['province'].' '.$userPageArray['city'])?></li>
				<?php }?>
				<?php if(0!=$userPageArray['birthday']){?>
				<li class="icon icon2" ><span class="item">生日</span> <?php p(date('Y年m月d日',$userPageArray['birthday']))?></li>
				<?php }?>

				<?php if(''!=getUserTag($userPageArray['uid'])){?>
				<li class="icon icon3" ><span class="item">关注</span> <?php p(getUserTag($userPageArray['uid']))?></li>
				<?php }?>
				<!--<li class="icon" >更多资料</li>-->
			</div>
		</div>
		<div class="main">
			<div class="box">
				<div class="inner">
					<?php if(1==$userPageArray['verify']){?>
					<div class="tab f12 b">
						<ul>
							<li class="tab1">建议</li>
							<li class="tab2">分享</li>
						</ul>
					</div>
					<div class="clear"></div>
					<div class="input" id="complainform">
						<div class="tagbox f12" style="display:none">
						</div>
						<div>
						<textarea class="ipod f14" id="ipod" wd="hint" style="color:#999" maxlength="280" title="在这里说说你给他的建议……">在这里说说你给他的建议……</textarea>
						</div>
						<div class="tags">
							<?php include("user_tag.php")?>			
							<?php if(!$isLogin){?>
							<input class="isubmit" type="button" value="提交"  style="float:left">
							<div class="anm f12" wd="openLogin"><input type="checkbox" checked="checked" disabled="disabled"><span>匿名</span></div>
							<?php }else{?>
							<input class="isubmit" type="button" value="提交"  style="float:left">
							<div class="anm f12"><input type="checkbox" id="anonymous"><span>匿名</span></div>
							<?php }?>
						</div>
					</div>
					<?php }else{?>
					<div class="verify">账号还未激活<br/>请先去你的邮箱激活你的账号</div>
					<?php }?>
				</div>
				<div class="shw">
				</div>
			</div>
			<div class="feed">
				<ul>
					<?php if(sizeof($correctList)>0){?>
						<?php foreach($correctList as $complain){?>
							<?php if($complain['correct']!=0){?>
							<li name="<?php p($complain['cid'])?>" style="margin:6px 0;">
								<div id="centerfeed">
									<div class="heads">
										<div class="from"><?php getHead($complain['from'],'s')?></div>
										<div class="to"><?php getHead($complain['uid'],'s')?></div>
									</div>
									<div class="infos">
										<div class="complain">
											<div class="f12 inner">
											<span class="dark"  title="<?php p(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from']))?>">
												<a href="<?php p(webhost.'u/'.$complain['from'].'/')?>"><?php p(getName($complain['from'])==''?'匿名':getName($complain['from']))?></a>
											</span> <span class="f12 taglist">指出该不足</span><span class="f12 taglist">•</span><span class="f12 taglist">已被添加到 <span class="tag"><?php p(getTag($complain['cid']))?></span>话题</span>
											<?php if($isLogin){?>
											<span class="f12 taglist">•</span>
											<span class="f12 function" ><a style="cursor:pointer" wd="fav" cidx="<?php p($complain["cid"])?>"><?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>收藏<?php }else{?>已收藏<?php }?></a></span>
											<?php }?>
											</div>
											<div class="f14 b dark comp inner">
											<p><a href="<?php p(webhost.'dislike/'.$complain["cid"].'/')?>"><?php p(subString($complain['complain'],0,34))?></a></p>
											</div>
										</div>
										<div class="replyx">
											<div class="inner f12 reinner">
											<span class="light l<?php p($complain['type'])?>"></span>
											<span class="f12 dark"  title="<?php p(getDescription($complain['uid'])==''?'新鲜小友一枚':getDescription($complain['uid']))?>">
											<a href="<?php p(webhost.'u/'.$complain['uid'].'/')?>"><?php p(getName($complain['uid']))?></a></span>：
											<br/><?php p($complain['reply'])?>
											</div>
										</div>
									</div>
								</div>
								<div class="clear"></div>
								<?php if(getComment($complain['cid'])!=false){?>
									<div class="icomment">
										<div class="icommentlist">
											<ol>
												<?php foreach(getComment($complain['cid']) as $comment){?>
												<li>
												<div class="icdetail">
													<span class="f12"><span class="dark"  title="<?php p(getDescription($comment['uid'])==''?'新鲜小友一枚':getDescription($comment['uid']))?>"><a href="<?php p(webhost.'u/'.$comment['uid'].'/')?>"><?php p(getName($comment['uid']))?></a>：</span><?php p(subString($comment['content'],0,40))?></span>
												</div>
												</li>
												<?php }?>
											</ol>
										</div>
									</div>
								<?php }?>
								<div class="commore f12">
								<?php
								if(sizeof(getComment($complain['cid']))==2){
									p('<a class="morecom" href="'.webhost.'dislike/'.$complain["cid"].'/">查看更多相关讨论»</a>');
								}
								else{
									p('<a class="morecom" href="'.webhost.'dislike/'.$complain["cid"].'/">参与话题讨论»</a>');
								}
								?>
								</div>
							</li>
							<?php }else{?>
								<li name="<?php p($complain['cid'])?>">
									<div id="centerfeed">
										<div class="heads">
											<div class="to"><?php getHead($complain['from'],'s')?></div>
										</div>
										<div class="infos" >
										
											<div class="f12 inner">
											<span class="dark"  title="<?php p(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from']))?>">
												<a href="<?php p(webhost.'u/'.$complain['from'].'/')?>"><?php p(getName($complain['from'])==''?'匿名':getName($complain['from']))?></a>
											</span> <span class="f12 taglist">指出该不足</span><span class="f12 taglist">•</span><span class="f12 taglist">已被添加到 <span class="tag"><?php p(getTag($complain['cid']))?></span>话题</span>
											<?php if($isLogin){?>
											<span class="f12 taglist">•</span>
											<span class="f12 function" ><a style="cursor:pointer" wd="fav" cidx="<?php p($complain["cid"])?>"><?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>收藏<?php }else{?>已收藏<?php }?></a></span>
											<?php }?>
											</div>
											<div class="f14 dark comp inner">
											<p><?php p(subString($complain['complain'],0,34))?></p>
											</div>
										</div>
									</div>
									<div class="clear"></div>
								</li>
							<?php }?>
						<?php }?>
					<?php }?>
				</ul>
			</div>
		</div>
	</div> 
	<div class="clear"></div>
</div>

<?php include("footer.php");?>
<?php include("popup_login.php")?>
<?php include("popup_guest.php")?>
<?php js('jquery.masonry.min')?>
<script>
$(document).ready(function(){
	$(function(){
		function Arrow_Points()
		{ 
		var s = $('#container').find('.item');
		$.each(s,function(i,obj){
		var posLeft = $(obj).css("left");
		$(obj).addClass('borderclass');
		if(posLeft == "0px")
		{
		html = "<span class='rightCorner'></span>";
		$(obj).prepend(html);			
		}
		else
		{
		html = "<span class='leftCorner'></span>";
		$(obj).prepend(html);
		}
		});
		}			
		// Divs
		$('#container').masonry({itemSelector : '.item'});
		Arrow_Points();  
	});
	$("[wd='hint']").focus(function(){
		if($(this).val()=='在这里指出他的不足……'&&$(this).attr("id")=='ipod'){
		$(this).val('');
		$(this).css("color","#666");
		}
		else{
		$(this).val('');
		$(this).css("color","#666");
		}
	});
	$("[wd='hint']").blur(function(){
		if($(this).val()==''){
		$(this).val($(this).attr("title"));
		$(this).css("color","#999");
		}
	});
	$("[wd='postTag']").focus(function(){
		$(this).val('');
		$(this).css("color","#666");
	});
	$(".isubmit").click ( function () {
		$(".isubmit").val('提交中...'); 
		if(($("#ipod").val()=='')||($("#ipod").val()=='在这里指出他的不足……')){
			alert("你至少要写点内容吧！");
			$(".isubmit").val('提交'); 
		}
		else if($("#tagarray").val()==''){
			alert("你至少加一个标签吧！");
			$(".isubmit").val('提交'); 
		}
		else{
			<?php if($isLogin){?>
			$.ajax({
			url: '<?php p($webinfo["webhost"].'include/ajax/complain.php');?>',
			type: 'POST',
			data:{ complain: $("#ipod").val(), tags: $("#tagarray").val(),from: "<?php p($_SESSION['user']['uid']);?>", uid: "<?php p($userPageArray['uid']);?>",anonymous:$("#anonymous").attr("checked") },
			dataType: 'html',
			timeout: 6000,
			error: function(){
				$(".isubmit").val('提交'); 
			},
			success: function(html){
				$("#complainform").html('<div class="result">'+html+'</div>');
			}
			});
			<?php }else{?>
			$("#popupC").show(500);
			<?php }?>
		}
	});
	<?php if($isLogin){?>
	$("[wd='attention']").click ( function () {
		<?php if($_SESSION['user']['uid']==$userPageArray['uid']){?>
		alert("能不能不要这么自恋啊……关注自己可不行！");
		<?php }else{?>
		if($("[wd='attention']").val()=="取消关注"){
			var offAtt = true;
		}
		else{
			var offAtt = false;
		}
		$("[wd='attention']").val("载入中...");
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/att.php');?>',
		type: 'POST',
		data:{ uid: "<?php p($_SESSION['user']['uid']);?>", fuid: "<?php p($userPageArray['uid']);?>" },
		dataType: 'json',
		timeout: 3000,
		error: function(){
			alert('关注失败！请重试。');
			$("[wd='attention']").val("关注+");
		},
		success: function(data){
			if(offAtt==true){
			$("[wd='attention']").val("关注+");
			}
			else{
			$("[wd='attention']").val("已关注");
			}
		}
		});
		<?php }?>
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
	
	$("[wd='fav']").click(function () {
		if($(this).html()=="已收藏"){
			var offFav = true;
		}
		else{
			var offFav = false;
		}
		$(this).html("载入...");
		var cid = $(this).attr("cidx");
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/fav.php');?>',
		type: 'POST',
		data:{uid: "<?php p($_SESSION['user']['uid']);?>", cid: $(this).attr("cidx") },
		dataType: 'json',
		timeout: 3000,
		error: function(){
			alert('收藏失败！请重试。');
			$("[cidx='"+cid+"']").html("收藏");
		},
		success: function(data){
			if(offFav==true){
			$("[cidx='"+cid+"']").html("收藏");
			}
			else{
			$("[cidx='"+cid+"']").html("已收藏");
			}			
		}
		});
	});
	
	
	
	
	$("[wd='fakecom']").focus(function () {
		$(this).hide(10);
	});
<?php }?>
});
</script>
</body>