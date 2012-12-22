<div class="header">
	<div class="logo">
	<a href="<?php p(webhost)?>"></a>
	</div>
	<div class="menu f14">
		<a href="<?php p($webinfo["webhost"])?>center/">首页</a>
		<a href="<?php p($webinfo["webhost"])?>square/">缺点广场</a>
		<?php if($isLogin){?>
		<a href="<?php p($webinfo["webhost"])?>u/<?php p($_SESSION['user']['uid'])?>/">我的主页</a>
		<?php }else{?>
		<a href="<?php p($webinfo['webhost'])?>">我的主页</a>
		<?php }?>
	</div>
	<div class="ubar f12">
		<?php if($isLogin){?>
		<span>欢迎回来，<?php p($_SESSION['user']['name'])?></span>|
		<span><a href="<?php p($webinfo["webhost"])?>fav/">收藏</a></span>|
		<span><a href="<?php p($webinfo["webhost"])?>setting/">设置</a></span>|
		<span><a href="<?php p($webinfo["webhost"])?>logout/">登出</a></span>
		<?php }else{?>
			<?php if($isUserpage){?>
			<span><a href="#" wd="openLogin">登录</a></span>
			<?php }else{?>
			<span><a href="<?php p($webinfo["webhost"])?>">登录</a></span>
			<?php }?>
		<?php }?>
	</div>
</div>
<div class="clear"></div>