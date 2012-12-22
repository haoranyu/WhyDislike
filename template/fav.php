<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('我的收藏 - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('center');?>
<?php js('jquery.min');?>
</head>
<body>
<?php include("header.php")?>
<div class="main">
	<div class="feed f14">
		<ul>
		<?php
		if(sizeof($dislikeList)>0){
			foreach($dislikeList as $complain){
				p('<li name="'.$complain['cid'].'">');
					p('<div class="name"><span class="f12 b dark" >'.(($complain['from']==0)?'某人':getName($complain['from'])).'</span><span class="f12 desc">'.(getDescription($complain['from'])==''?'新鲜小友一枚':getDescription($complain['from'])).'</span></div>');
					p('<div class="reply f12"><a href="#'.$complain['cid'].'" wd="fav" cid="'.$complain['cid'].'">取消收藏</a></div>');
					p('<div>'.$complain['complain'].'</div>');
					p('<span class="f12 taglist">已被添加到 <span class="tag">'.getTag($complain['cid']).'</span>话题</span><span class="f12 taglist">|</span><span class="f12 taglist">'.date("Y-m-d H:i",$complain['time']).'</span>');
					if($complain['reply']!=''){
						p('<div class="quote quoteplue f12">'.getName($complain['uid']).'：'.$complain['reply'].'</div>');
					}
				p('</li>');
			}
		}
		else{?>
				<div style="margin:10px;line-height:28px;">你还没有收藏过任何缺点，请先去看看大家的缺点吧。<br/></div>
		<?php }?>
		</ul>
		<div class="clear"></div>
		<?php p($pagebar);?>
	</div>
	<div class="sidebar">
		<?php include("user_info.php")?>
		<div class="share">
		<?php if(sizeof($dislikeList)!=0){include("share.php");}?>
		</div>
	</div>
</div>
<script>
$("[wd='fav']").click(function () {
	$(this).html("处理中…");
	var cid = $(this).attr("cid");
	$.ajax({
	url: '<?php p($webinfo["webhost"].'include/ajax/fav.php');?>',
	type: 'POST',
	data:{uid: "<?php p($_SESSION['user']['uid']);?>", cid: $(this).attr("cid") },
	dataType: 'json',
	timeout: 1000,
	error: function(){
		alert('处理失败！请重试。');
	},
	success: function(data){
		$("[name='"+cid+"']").hide(500);
	}
	});
});
</script>
<?php include('footer.php');?>
</body>