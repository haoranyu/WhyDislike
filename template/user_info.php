<?php if(getNotify($_SESSION['user']['uid'])!=false){?>
<div class="notibox f12" wd="note" style="display:none">
<ul wd="notein">
</ul>
</div>

<div class="notibox f14" style="cursor:pointer;" wd="notebox">
您现在有<?php p(getNotify($_SESSION['user']['uid']))?>条新消息 +
</div>
<?php }?>
<div class="userinfo">
	<div class="head" style=""><?php getHead($_SESSION['user']['uid'],'m',78);?></div>
	<div class="info" >
	<span class="f14 dark b" style="float:left"><?php p($_SESSION['user']['name'])?></span>
	<?php if($_SESSION['user']['vip']!=0){?>
		<span class="vip vip<?php p($_SESSION['user']['vip'])?>" title="<?php p(($_SESSION['user']['vip']=='1')?'认证名人':'认证企业');?>"></span>
	<?php }?>
	<div class="clear"></div>
	<div class="desc f12">
		<?php 
		if($_SESSION['user']['description']==''){
			p('你很懒，连简介都没写');}
		else{
			p(subString($_SESSION['user']['description'],0,29));
		}
		p(' - <a href="'.$webinfo["webhost"].'setting/" target="_blank">修改</a>');
		?>
	</div>
	</div>
	<div class="clear"></div>
	<div class="stat">
	<ul class="dark f12">
		<li>
		<a href="<?php p($webinfo["webhost"])?>dislike/">
		<span class="nums"><?php p($_SESSION['user']['count'])?></span>
		<span class="nt">发现不足</span></a>
		</li>
		<li>
		<a href="<?php p($webinfo["webhost"])?>correct/">
		<span class="nums" wd="cortCount"><?php p($_SESSION['user']['correct'])?></span>
		<span class="nt">进步</span></a>
		</li>
		<li>
		<a href="<?php p($webinfo["webhost"])?>friend/">
		<span class="nums"><?php p(getFanscount($_SESSION['user']['uid']))?></span>
		<span class="nt">粉丝</span></a>
		</li>
		<li style="border:none">
		<a href="<?php p($webinfo["webhost"])?>att/">
		<span class="nums" wd="attCount"><?php p($_SESSION['user']['att'])?></span>
		<span class="nt">关注</span>
		</li></a>
	</ul>
	</div>
</div>
<script>
$("[wd='notebox']").click(function () {
	$("[wd='note']").fadeIn(1);
	$(this).fadeOut(1);
	$("[wd='notein']").html('<div style="height:27px;line-height:27px;">您的消息正在路上，有点堵车...</div>');
	$.ajax({
	url: '<?php p($webinfo["webhost"].'include/ajax/notify.php');?>',
	type: 'POST',
	data:{ uid: <?php p($_SESSION['user']['uid'])?>},
	dataType: 'json',
	timeout: 8000,
	error: function(){
		$("[wd='notein']").html('<div style="height:27px;line-height:27px;">抱歉，信使路上出事故了...</div>');
	},
	success: function(data){
			if(false!=data[0]){
				var info = '';
				var i;
				for(i = 0;i < data[2];i++){
					info += '<li wd="notify" nid="'+data[1][i].nid+'">'+data[1][i].content+'</li>';
				}
				$("[wd='notein']").html(info);
			}
			else{
				$("[wd='note']").fadeOut(150);
			}
	},
	});
});
$("[wd='notify']").live("click",function() {
	var obj = $(this);
	$.ajax({
	url: '<?php p($webinfo["webhost"].'include/ajax/notify.php');?>',
	type: 'POST',
	data:{ nid: $(this).attr("nid"),uid: <?php p($_SESSION['user']['uid'])?>},
	dataType: 'html',
	timeout: 3000,
	error: function(){
		$("[wd='notein']").html('<div style="height:27px;line-height:27px;">抱歉，信使路上出事故了...</div>');
	},
	success: function(html){
		obj.fadeOut(300);
	}
	});
	$(this).remove();
	if(''==$("[wd='notein']").html()){
		$("[wd='note']").fadeOut(150);
	}
});
</script>