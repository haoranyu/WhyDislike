<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p(subString($complain['complain'],0,20).' - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('center');?>
<?php js('jquery.min');?>
</head>
<body>
<?php include("header.php")?>
<div class="main">
	<div class="dislike f14">
		<div id="complain" style="margin:24px">
			<div class="bar">
			<ul class="tags f12"><?php p(getTag($complain["cid"]))?></ul>
			<?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>
				<a class="barfav" wd="fav" cid="<?php p($complain["cid"])?>"></a>
				<?php }else{?>
				<a class="barfav3" wd="fav" cid="<?php p($complain["cid"])?>"></a>
			<?php }?>
			</div>
			<?php
			p('<div class="f14 comp"><p><span class="f14 dark" style="margin-right:6px;" title="'.(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from'])).'">'.(($complain['from']==0)?'某人':'<a href="'.webhost.'u/'.$complain['from'].'/">'.getName($complain['from']).'</a>').'：</span>'.$complain['complain'].'</p></div>');
			p('<div class="f12 quote quoteplue " ><p><span class="f12 dark"  title="'.(getDescription($complain['uid'])==''?'新鲜小友一枚':getDescription($complain['uid'])).'">'.('<a href="'.webhost.'u/'.$complain['uid'].'/">'.getName($complain['uid']).'</a>').'：</span> '.(($complain['reply']=='')?'我真的已经试着进步了~':$complain['reply']).'</p></div>');
			?>
			<div class="comment">
				<ul class="f12 commentlist" id="commentList">
				<?php 
				if($commentList!=false){
				foreach($commentList as $comment){
				?>
				<li>
					<div class="left"><?php p(getHead($comment['uid'],"s",48))?></div>
					<div class="right">
						<div class="f12 namex"><span><small class="f12 b dark"><a href="<?php p(webhost.'u/'.$comment['uid'])?>"><?php p(getName($comment['uid']))?></a></small><?php p(subString(getDescription($comment['uid']),0,16))?></span><span class="time"><?php p(date("Y-m-d h:i:s",$comment['time']))?></span></div>
						<div class="content f12"><?php p($comment['content'])?><a href="#submitcom" class="rebtn" wd="replycom" to="<?php p(getName($comment['uid']))?>" ruid="<?php p($comment['uid'])?>">回复</a></div>
					</div>
					<div class="clear"></div>
				</li>
				<?php }}?>
				</ul>
				<ul class="f12" >
				<li style="border:none;">
					<div class="left"><?php p(getHead($_SESSION['user']['uid'],"s",48))?></div>
					<div class="right" style="margin-top:6px;">
						<div class="f12 namex"><span class="f12 b dark"><?php p($_SESSION['user']['name'])?></a></span><span><?php p(subString($_SESSION['user']['description'],0,20))?></span><span class="time"></span></div>
						<div class="content f12" style="margin-bottom:0"><textarea maxlength="140" id="comarea" class="comarea"></textarea></div>
						<input type="hidden" id="replyto" value="0">
						<div class="submit"><input type="submit" value="提交评论" wd="submitcom"> &nbsp;&nbsp;<span wd="wordcount">0</span>/140</div>
					</div>
					<div class="clear">
					</div>
					<A name="submitcom"></A>
				</li>
				</ul>
			</div>
			
		</div>
		<div class="clear"></div>
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
	$("[wd='fav']").mouseover(function () {
		if($(this).attr("class")=="barfav"){
			$(this).removeClass().addClass("barfav2");
		}
	});
	$("[wd='fav']").mouseout(function () {
		if($(this).attr("class")=="barfav2"){
			$(this).removeClass().addClass("barfav");
		}
	});
	$("[wd='fav']").click(function () {
		if($(this).attr("class")=="barfav3"){
			var offFav = true;
		}
		else{
			var offFav = false;
		}
		var cid = $(this).attr("cid");
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/fav.php');?>',
		type: 'POST',
		data:{uid: "<?php p($_SESSION['user']['uid']);?>", cid: $(this).attr("cid") },
		dataType: 'json',
		timeout: 3000,
		error: function(){
			alert('收藏失败！请重试。');
		},
		success: function(data){
			if(offFav==true){
			$("[cid='"+cid+"']").removeClass().addClass("barfav");
			}
			else{
			$("[cid='"+cid+"']").removeClass().addClass("barfav3");
			}			
		}
		});
	});
	$("#comarea").focus(function(){
		$(this).removeClass().addClass("comareax");
	});
	$("#comarea").blur(function(){
		if($("#comarea").val()==""){
		$(this).removeClass().addClass("comarea");
		}
	});
	$("#comarea").keyup(function(){
		$("[wd='wordcount']").html($("#comarea").val().length);
	});
	
	$("[wd='submitcom']").click(function () {
		$(this).val('提交中...');
		if($("#replyto").val()==0){
			var ruid = "<?php p($complain['uid'])?>";
		}
		else{
			var ruid = $("#replyto").val();
		}
		if($("#comarea").val().replace(/\r\n/g,"")==''){
		alert('评论信息不能为空，亲~~');
		}
		else{
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/comment.php');?>',
		type: 'POST',
		data:{cid: "<?php p($complain["cid"]);?>", uid: <?php p($_SESSION['user']["uid"])?>, ruid:ruid, content: $("#comarea").val().replace(/\r\n/g,"") },
		dataType: 'json',
		timeout: 10000,
		error: function(){
			alert('评论失败！请重试。');
		},
		success: function(json){
			$("#commentList").html($("#commentList").html()+'<?php ajaxComment($complain["cid"],1)?>'+$("#comarea").val()+'<?php ajaxComment($complain["cid"],2)?>');
			$("#comarea").val('');
			$("[wd='submitcom']").val('提交评论');
		}
		});
		
		}
	});
	$("[wd='replycom']").click(function () {
		$("#comarea").focus();
		$("#comarea").val('回复'+$(this).attr("to")+'：'+$("#comarea").val());
		$("#replyto").val($(this).attr("ruid"));
	});
	$(document).keypress(function(e){
        if(e.ctrlKey && e.which == 13 || e.which == 10) { 
                $("[wd='submitcom']").click();
        } else if (e.shiftKey && e.which==13 || e.which == 10) {
                $("[wd='submitcom']").click();
        }          
	});
});
</script>
</body>