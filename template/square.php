<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('缺点广场 - '.$webinfo["webname"]);?></title>
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
		if(sizeof($squareList)>0){
			foreach($squareList as $complain){
				p('<li name="'.$complain['cid'].'">');
					p('<span class="f12 taglist">有关 <span class="tag">'.getTag($complain['cid']).'</span>的缺点话题</span><span class="f12 taglist">•</span><span class="f12 taglist">'.date("Y-m-d H:i",$complain['time']).'</span>');
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
		<div class="f14 b dark">热门缺点话题</div>
		<ul class="topics f14">
		<?php echo $hotTag?>
		</ul>
	</div>
</div>
<?php include('footer.php');?>
<?php include("popup_login.php")?>
<script>
<?php if($isLogin){?>
$("[wd='attention']").click ( function () {
	$("#tid").val($(this).attr("tid"));
	if($("[tid='"+$(this).attr("tid")+"']").html()=='已关注'){
		var offAtt = true;
	}
	else{
		var offAtt = false;
	}
	$("[tid='"+$(this).attr("tid")+"']").html("载入...");
    $.ajax({
    url: '<?php p($webinfo["webhost"].'include/ajax/attag.php');?>',
    type: 'POST',
    data:{ uid: "<?php p($_SESSION['user']['uid']);?>", tid: $(this).attr("tid") },
    dataType: 'html',
    timeout: 3000,
    error: function(){
		alert('很抱歉，关注失败！');
		$("[tid='"+$(this).attr("tid")+"']").html("关注+");
    },
    success: function(html){
		if(offAtt==true){
		$("[tid='"+html+"']").html("+关注");
		}
		else{
		$("[tid='"+html+"']").html("已关注");
		}
    }
    });
});
<?php }else{?>
$("[wd='attention']").click ( function () {
	$("#popup").show(500);
});
<?php }?>
</script>
</body>