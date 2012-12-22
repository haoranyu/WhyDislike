<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('我的首页 - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('center',20120610);?>
<?php css('popup');?>
<?php js('jquery.min');?>
</head>
<body>
<?php include("header.php")?>
<div class="main">
	<div class="feed f14">
		<ul>
		<?php if(sizeof($dislikeList)>0){?>
			<?php foreach($dislikeList as $complain){?>
				<?php if($correct==0||($correct==2&&$complain['correct']=="0")){?>
				<li name="<?php p($complain['cid'])?>">
					<div id="centerfeed">
						<div class="heads">
							<div class="to"><?php getHead($complain['from'],'s')?></div>
						</div>
						<div class="infos" >
						<div class="inner">
							<div class="name namep" ><span class="f12 b dark" title="<?php p(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from']))?>"><?php p(($complain['from']==0)?'某人':'<a href="'.webhost.'u/'.$complain['from'].'/">'.getName($complain['from']).'</a>')?></span><span class="f12 desc">指出了一个你的缺点</span></div>
							<div class="reply f12"><a href="#<?php p($complain['cid'])?>" wd="openCort" cid="<?php p($complain['cid'])?>">回应</a></div>
							<div class="comp"><?php p($complain['complain'])?></div>
							<span class="f12 taglist">已被添加到 <span class="tag"><?php p(getTag($complain['cid']))?></span>话题</span><span class="f12 taglist">•</span>
							<span class="f12 function"><a style="cursor:pointer" wd="fav" cidx="<?php p($complain["cid"])?>"><?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>收藏<?php }else{?>已收藏<?php }?></a></span>
						</div>
						</div>
					</div>
					<div class="clear"></div>
				</li>
				<?php }elseif($correct==1){?>
				<li name="<?php p($complain['cid'])?>">
					<div><?php p($complain['complain'])?></div>
					<span class="f12 dark"  title="<?php p(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from']))?>"><?php p('<a href="'.webhost.'u/'.$complain['from'].'/">'.(getName($complain['from'])==''?'匿名':getName($complain['from'])).'</a>')?></span> <span class="f12 taglist">指出该不足</span><span class="f12 taglist">•</span><span class="f12 taglist">已被添加到 <span class="tag"><?php getTag($complain['cid'])?></span>话题</span><span class="f12 taglist">•</span>
					<span class="f12 function"><a style="cursor:pointer" wd="fav" cidx="<?php p($complain["cid"])?>"><?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>收藏<?php }else{?>已收藏<?php }?></a></span>
					<div class="quote quoteplue f12">已回应：<?php p($complain['reply'])?></div>
				</li>
				<?php }else{?>
				<li name="<?php p($complain['cid'])?>" style="margin:6px 0;">
					<div id="centerfeed">
						<div class="heads">
							<div class="from"><?php getHead($complain['from'],'s')?></div>
							<div class="to"><?php getHead($complain['uid'],'s')?></div>
						</div>
						<div class="infos">
							<div class="complain">
								<div class="f14 b dark comp inner">
								<p><a href="<?php p(webhost.'dislike/'.$complain["cid"].'/')?>"><?php p(subString($complain['complain'],0,34))?></a></p>
								</div>
								<div class="f12 inner">
								<span class="dark"  title="<?php p(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from']))?>">
									<a href="<?php p(webhost.'u/'.$complain['from'].'/')?>"><?php p(getName($complain['from'])==''?'匿名':getName($complain['from']))?></a>
								</span> <span class="f12 taglist">指出该不足</span><span class="f12 taglist">•</span><span class="f12 taglist">已被添加到 <span class="tag"><?php p(getTag($complain['cid']))?></span>话题</span><span class="f12 taglist">•</span>
								<span class="f12 function" ><a style="cursor:pointer" wd="fav" cidx="<?php p($complain["cid"])?>"><?php if(!getFav($_SESSION['user']['uid'],$complain["cid"])){?>收藏<?php }else{?>已收藏<?php }?></a></span>
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
				<?php }?>
			<?php }?>
		<?php }else{?>
			<?php if($correct!=1){?>
			<div style="margin:10px;line-height:28px;">
				你现在还没有被朋友提出的缺点，也没有关注其它用户。<br/>你可以通过下面的方式邀请朋友们为你指出你的缺点。<br/>
				<?php include("share.php")?>
			</div>
			<?php }else{?>
				<div style="margin:10px;line-height:28px;">你现在还没已改正的缺点。请先去改正一些自己的不足吧。<br/></div>
			<?php }?>
		<?php }?>
		</ul>
		<div class="clear"></div>
		<?php p($pagebar);?>
	</div>
	<div class="sidebar">
		<?php include("user_info.php")?>
		<div id="recomm">
		</div>
		<div class="share">
		<?php if(sizeof($dislikeList)!=0){include("share.php");}?>
		</div>
		
	</div>
</div>
<script>
$(document).ready(function(){
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
	
	$("#recomm").ready(function () {
		$(this).html("载入...");
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/recommendation.php');?>',
		type: 'POST',
		data:{uid: "<?php p($_SESSION['user']['uid']);?>"},
		dataType: 'json',
		timeout: 6000,
		error: function(){
			$("#recomm").html('');	
		},
		success: function(data){
			var info1 = '<div class="recomm" wd="reco"><strong>推荐用户</strong><ul wd="recoin">';
			var info2 = '</ul><div class="clear"></div></div>';
			if(false!=data[0]){
				var info = '';
				var i;
				for(i = 0;i < data[2];i++){
					info += '<li id="recu'+data[1][i].uid+'"><div class="rhead">'+data[1][i].head+'</div><div class="rinfo"><div class="dark f12"><a href="'+data[1][i].link+'">'+data[1][i].name+'</a></div><input type="button" value="关注+" wd="att" fuid="'+data[1][i].uid+'" class="reatt"></div></li>';
				}
				$("#recomm").html(info1+info+info2);	
			}
			else{
				$("#recomm").html('');
			}
		}
		});
	});
	$("[wd='att']").live("click",function() {
		var obj = $("#recu"+$(this).attr("fuid"));
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/att.php');?>',
		type: 'POST',
		data:{ uid: "<?php p($_SESSION['user']['uid']);?>", fuid: $(this).attr("fuid") },
		dataType: 'json',
		timeout: 8000,
		error: function(){
			alert('关注失败！请重试。');
		},
		success: function(data){
			obj.remove();
			if(''==$("[wd='recoin']").html().replace( /^\s*/, '')){
			$("[wd='reco']").remove();
			}
		}
		});
	});
});
</script>
<?php include('footer.php');?>
<?php include("popup_reply.php")?>
</body>