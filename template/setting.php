<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="IE=8" http-equiv="X-UA-Compatible"/>
<title><?php p('账号设置 - '.$webinfo["webname"]);?></title>
<?php css('main');?>
<?php css('setting');?>
<style>
#t<?php p($_SESSION['user']['bg'])?>{
width:260px;
height:76px;
border:3px solid #0099CC;
}
</style>
</head>
<body onLoad="init()">
<?php include("header.php");?>
<div class="main f14">
	<div class="left">
		<span <?php if($tab == '1'){p('class="b"');}?>><a href="1">基本资料</a></span>
		<span <?php if($tab == '2'){p('class="b"');}?>><a href="2">修改密码</a></span>
		<span <?php if($tab == '3'){p('class="b"');}?>><a href="3">头像设置</a></span>
		<span <?php if($tab == '6'){p('class="b"');}?>><a href="6">个性化域名</a></span>
		<span <?php if($tab == '4'){p('class="b"');}?>><a href="4">个性化主页</a></span>
		<span <?php if($tab == '5'){p('class="b"');}?>><a href="5">绑定账号</a></span>
	</div>
	
	<?php if($tab == '1'){?>
	<ul class="base">
		<form method="post" name="creator">
		<li><span class="item">登录邮箱</span><span class="set"><input name="email" type="text" value="<?php p($_SESSION['user']['email'])?>" style="padding-left:5px;" readonly="readonly"></span></li>
		<li><span class="item">昵称</span><span class="set"><input name="name" type="text" value="<?php p($_SESSION['user']['name'])?>" style="padding-left:5px;"></span></li>
		<li>
		<span class="item">性别</span>
		<span class="set">
			<select name="sex">
				<option value="1" <?php if($_SESSION['user']['sex']){p('selected="selected"');}?>>男性</option>
				<option value="0" <?php if(!$_SESSION['user']['sex']){p('selected="selected"');}?>>女性</option>
			</select>
		</span>
		</li>
		<li>
		<span class="item">现居</span>
		<span class="set">
			<input id="cityinit" type="text" value="<?php p($_SESSION['user']['province'].' '.$_SESSION['user']['city'])?>" style="padding-left:5px;" >
			<div id="city" style="display:none">
				<select name="province" id="cityp" onChange = "select()"></select>
				<select name="city" id="cityc" onChange = "select()"></select>
			</div> 
		</span>
		</li>
		<li>
		<span class="item">生日</span>
		<span class="set">
			<input id="date_picker" type="text" name="birthday" class="html_date" value="<?php p(date('Y-m-d',$_SESSION['user']['birthday']))?>" readonly="readonly">
		</span>
		</li>
		<li style="height:100px;"><span class="item">个人简介</span><span class="set"><textarea name="description" style="width:360px;height:90px;padding:5px;"><?php p($_SESSION['user']['description'])?></textarea></span></li>
		<div class="clear"></div>
		<li>
		<span class="item">邮件提醒</span>
		<span class="set">
			<select name="alert">
				<option value="1" <?php if($_SESSION['user']['alert']){p('selected="selected"');}?>>开启邮件提醒</option>
				<option value="0" <?php if(!$_SESSION['user']['alert']){p('selected="selected"');}?>>禁止邮件提醒</option>
			</select>
		</span>
		</li>
		<li>
		<span class="item">我的主页显示</span>
		<span class="set">
			<select name="thide">
				<option value="0" <?php if(!$_SESSION['user']['thide']){p('selected="selected"');}?>>显示全部大家提出的意见</option>
				<option value="1" <?php if($_SESSION['user']['thide']){p('selected="selected"');}?>>仅显示我回应过的意见</option>
			</select>
		</span>
		</li>
		<li><span class="item">&nbsp;</span><span class="set"><input type="submit" name="save1" class="submit" value="保存"></span><span  class="f12" id="modify">修改已经保存</span></li>
		</form>
	</ul>
	<?php }elseif($tab == '2'){?>
	<ul class="base">
		<form method="post">
		<?php if(!isset($authcode)){?>
		<li><span class="item">原密码</span><span class="set"><input name="keyword" id="keyword" type="password" style="padding-left:5px;" ></span></li>
		<?php }else{?>
		<input name="keyword" id="keyword" type="hidden" style="padding-left:5px;" <?php if(isset($authcode)){echo 'value="'.$password.'"';}?>>
		<?php }?>
		<li><span class="item">新密码</span><span class="set"><input name="newkey" id="newkey" type="password"  style="padding-left:5px;"></span></li>
		<li><span class="item">重复新密码</span><span class="set"><input name="renewkey" id="renewkey" type="password"  style="padding-left:5px;"></span></li>
		<div class="clear"></div>
		<li><span class="item">&nbsp;</span><span class="set"><input type="submit" name="save2" class="submit" value="保存" wd="md5"></span><span  class="f12" id="modify">修改已经保存</span></li>
		</form>
	</ul>
	<?php js('md5');?>
	<script type="text/javascript">
	$("[wd='md5']").click(function(){
	<?php if(!isset($authcode)){?>
	$("#keyword").val(hex_md5($("#keyword").val()));
	<?php }?>
	$("#newkey").val(hex_md5($("#newkey").val()));
	$("#renewkey").val(hex_md5($("#renewkey").val()));
	});
	</script>
	<?php }elseif($tab == '3'){?>
	<ul >
	<iframe src="<?php p(webhost.'include/plugin/head/')?>" style="border:none;" width="520px" height="300px;" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" ></iframe>
	</ul>
	<?php }elseif($tab == '4'){?>
	<ul class="theme">
		<li id="t0" onClick="setThumb(0)"></li>
		<li id="t1" onClick="setThumb(1)"></li>
		<li id="t2" onClick="setThumb(2)" class="rt"></li>
		<li id="t3" onClick="setThumb(3)"></li>
		<li id="t4" onClick="setThumb(4)"></li>
		<li id="t5" onClick="setThumb(5)" class="rt"></li>
		<li id="t6" onClick="setThumb(6)"></li>
		<li id="t7" onClick="setThumb(7)"></li>
		<li id="t8" onClick="setThumb(8)" class="rt"></li>
		<form method="post">
		<input type="hidden" value="<?php p($_SESSION['user']['bg'])?>" name="bg" id="themeset">
		<span class="set"><input type="submit" name="save4" class="submit" value="保存"></span><span  class="f12" id="modify">修改已经保存</span>
		</form>
	</ul>
	<?php }elseif($tab == '5'){?>
	<ul class="base" style="height:160px;">
	<li>
	<?php if(getSocial('7',$_SESSION['user']['uid'])){?>
	<strong>已经绑定人人网账户 </strong> <small> [ <a href="<?php p(webhost.'include/plugin/social/unlink.php?website=renren');?>">解除账号连接</a> ]</small>
	<?php }else{?>
	<strong>还未关联人人网帐户 </strong> <small> [ <a href="http://open.denglu.cc/transfer/renren?uid=<?php p($_SESSION['user']['uid'])?>&appid=27852">与人人网连接</a> ]</small>
	<?php }?>
	</li>
	<li>
	<?php if(getSocial('3',$_SESSION['user']['uid'])){?>
	<strong>已经绑定新浪微博帐户 </strong> <small> [ <a href="<?php p(webhost.'include/plugin/social/unlink.php?website=weibo');?>">解除账号连接</a> ]</small>
	<?php }else{?>
	<strong>还未关联新浪微博帐户 </strong> <small> [ <a href="http://open.denglu.cc/transfer/sina?uid=<?php p($_SESSION['user']['uid'])?>&appid=27852">与新浪微博连接</a> ]</small>
	<?php }?>
	</li>
	<li>
	<?php if(getSocial('13',$_SESSION['user']['uid'])){?>
	<strong>已经绑定腾讯QQ帐户 </strong> <small> [ <a href="<?php p(webhost.'include/plugin/social/unlink.php?website=qzone');?>">解除账号连接</a> ]</small>
	<?php }else{?>
	<strong>还未关联腾讯QQ帐户 </strong> <small> [ <a href="http://open.denglu.cc/transfer/qzone?uid=<?php p($_SESSION['user']['uid'])?>&appid=27852">与腾讯QQ连接</a> ]</small>
	<?php }?>
	</li>
	<li>
	<?php if(getSocial('19',$_SESSION['user']['uid'])){?>
	<strong>已经绑定百度帐户 </strong> <small> [ <a href="<?php p(webhost.'include/plugin/social/unlink.php?website=baidu');?>">解除账号连接</a> ]</small>
	<?php }else{?>
	<strong>还未关联百度帐户 </strong> <small> [ <a href="http://open.denglu.cc/transfer/baidu?uid=<?php p($_SESSION['user']['uid'])?>&appid=27852">与百度连接</a> ]</small>
	<?php }?>
	</li>
	</ul>
	<?php }elseif($tab == '6'){?>
	<ul class="base" style="height:160px;">
	<form method="post">
		<li class="f12">
		记得自己的主页地址是什么吗？设置个性域名，让朋友更容易记住！
		</li>
		<?php if(getDomain($_SESSION['user']['uid'])!=false){?>
		<li class="b">
		你已经设置个性域名
		</li>
		<li>http://whydislike.com/<?php print_r(getDomain($_SESSION['user']['uid']))?></li>
		<li class="f12">如果你希望修改，请联系管理员。</li>
		<?php }else{?>
		<li class="b">
		设置个性化域名（只可设置一次）
		</li>
		<form method="post">
		<li><span class="set">http://whydislike.com/  <input name="domain" wd="domain" type="text" style="padding-left:5px;width:80px;" ></span><small wd="domainhint" style="margin-left:7px"></small></li>
		<li><span class="set"><input type="submit" name="save6" class="submit" value="保存"></span><span  class="f12" id="modify">修改已经保存</span>
		</li>
		</form>
		<?php }?>
	</ul>
	<?php }?>
	
	<div class="clear"></div>
</div>
<?php include('footer.php');?>
<?php js('jquery.min');?>
<?php js('jquery.calendar');?>
<script type="text/javascript">
function setThumb(idNum){
	var idThumb = 't' + idNum.toString();
	for(i=0;i<9;i++){
	var thumb = 't' + i.toString();
		if(idThumb==thumb){
			document.getElementById(thumb).style.border="#09c 3px solid";
			document.getElementById(thumb).innerText="√";
		}
		else{
			document.getElementById(thumb).style.border="#ddd 3px solid";
			document.getElementById(thumb).innerText="";
		}
	}
	document.getElementById('themeset').value = idNum;
}
<?php if($modify){?>
$("#modify").fadeIn(800);
$("#modify").fadeOut(1500);
<?php }?>
$("[wd='domain']").keyup( function () {
	 $.ajax({
	url: '<?php echo $webinfo["webhost"].'include/ajax/domain.php';?>',
	type: 'POST',
	data:{ domain: $("[wd='domain']").val()},
	dataType: 'html',
	timeout: 3000,
	error: function(){
	},
	success: function(html){
		$("[wd='domainhint']").html(html);
	}
	});
});
$("#cityinit").focus( function () {
	$("#city").show();
	$(this).hide();
});
$("#cityc").blur( function () {
	$("#city").hide();
	$("#cityinit").show();
	$("#cityinit").val($("#cityp").val()+' '+$("#cityc").val());
});
// 自定义参数调用
$("#date_picker").calendar({
	begin_year:1950,
	end_year:2030,
	type:"yyyy-mm-dd",
	hyphen:"-",
	wday:0
});
var where = new Array(35);
function comefrom(loca,locacity) { this.loca = loca; this.locacity = locacity; }
where[0]= new comefrom("请选择省份名","请选择城市名");
where[1] = new comefrom("北京","|东城|西城|崇文|宣武|朝阳|丰台|石景山|海淀|门头沟|房山|通州|顺义|昌平|大兴|平谷|怀柔|密云|延庆");  //欢迎来到站长特效网，我们的网址是www.zzjs.net，很好记，zz站长，js就是js特效，本站收集大量高质量js代码，还有许多广告代码下载。
where[2] = new comefrom("上海","|黄浦|卢湾|徐汇|长宁|静安|普陀|闸北|虹口|杨浦|闵行|宝山|嘉定|浦东|金山|松江|青浦|南汇|奉贤|崇明");//欢迎来到站长特效网，我们的网址是www.zzjs.net，很好记，zz站长，js就是js特效，本站收集大量高质量js代码，还有许多广告代码下载。
where[3] = new comefrom("天津","|和平|东丽|河东|西青|河西|津南|南开|北辰|河北|武清|红挢|塘沽|汉沽|大港|宁河|静海|宝坻|蓟县");
where[4] = new comefrom("重庆","|万州|涪陵|渝中|大渡口|江北|沙坪坝|九龙坡|南岸|北碚|万盛|双挢|渝北|巴南|黔江|长寿|綦江|潼南|铜梁|大足|荣昌|壁山|梁平|城口|丰都|垫江|武隆|忠县|开县|云阳|奉节|巫山|巫溪|石柱|秀山|酉阳|彭水|江津|合川|永川|南川");
where[5] = new comefrom("河北","|石家庄|邯郸|邢台|保定|张家口|承德|廊坊|唐山|秦皇岛|沧州|衡水");
where[6] = new comefrom("山西","|太原|大同|阳泉|长治|晋城|朔州|吕梁|忻州|晋中|临汾|运城");
where[7] = new comefrom("内蒙古","|呼和浩特|包头|乌海|赤峰|呼伦贝尔盟|阿拉善盟|哲里木盟|兴安盟|乌兰察布盟|锡林郭勒盟|巴彦淖尔盟|伊克昭盟");
where[8] = new comefrom("辽宁","|沈阳|大连|鞍山|抚顺|本溪|丹东|锦州|营口|阜新|辽阳|盘锦|铁岭|朝阳|葫芦岛");
where[9] = new comefrom("吉林","|长春|吉林|四平|辽源|通化|白山|松原|白城|延边");
where[10] = new comefrom("黑龙江","|哈尔滨|齐齐哈尔|牡丹江|佳木斯|大庆|绥化|鹤岗|鸡西|黑河|双鸭山|伊春|七台河|大兴安岭");
where[11] = new comefrom("江苏","|南京|镇江|苏州|南通|扬州|盐城|徐州|连云港|常州|无锡|宿迁|泰州|淮安");
where[12] = new comefrom("浙江","|杭州|宁波|温州|嘉兴|湖州|绍兴|金华|衢州|舟山|台州|丽水");
where[13] = new comefrom("安徽","|合肥|芜湖|蚌埠|马鞍山|淮北|铜陵|安庆|黄山|滁州|宿州|池州|淮南|巢湖|阜阳|六安|宣城|亳州");
where[14] = new comefrom("福建","|福州|厦门|莆田|三明|泉州|漳州|南平|龙岩|宁德");
where[15] = new comefrom("江西","|南昌市|景德镇|九江|鹰潭|萍乡|新馀|赣州|吉安|宜春|抚州|上饶");
where[16] = new comefrom("山东","|济南|青岛|淄博|枣庄|东营|烟台|潍坊|济宁|泰安|威海|日照|莱芜|临沂|德州|聊城|滨州|菏泽");
where[17] = new comefrom("河南","|郑州|开封|洛阳|平顶山|安阳|鹤壁|新乡|焦作|濮阳|许昌|漯河|三门峡|南阳|商丘|信阳|周口|驻马店|济源");
where[18] = new comefrom("湖北","|武汉|宜昌|荆州|襄樊|黄石|荆门|黄冈|十堰|恩施|潜江|天门|仙桃|随州|咸宁|孝感|鄂州");
where[19] = new comefrom("湖南","|长沙|常德|株洲|湘潭|衡阳|岳阳|邵阳|益阳|娄底|怀化|郴州|永州|湘西|张家界");
where[20] = new comefrom("广东","|广州|深圳|珠海|汕头|东莞|中山|佛山|韶关|江门|湛江|茂名|肇庆|惠州|梅州|汕尾|河源|阳江|清远|潮州|揭阳|云浮");
where[21] = new comefrom("广西","|南宁|柳州|桂林|梧州|北海|防城港|钦州|贵港|玉林|南宁地区|柳州地区|贺州|百色|河池");
where[22] = new comefrom("海南","|海口|三亚");
where[23] = new comefrom("四川","|成都|绵阳|德阳|自贡|攀枝花|广元|内江|乐山|南充|宜宾|广安|达川|雅安|眉山|甘孜|凉山|泸州");
where[24] = new comefrom("贵州","|贵阳|六盘水|遵义|安顺|铜仁|黔西南|毕节|黔东南|黔南");
where[25] = new comefrom("云南","|昆明|大理|曲靖|玉溪|昭通|楚雄|红河|文山|思茅|西双版纳|保山|德宏|丽江|怒江|迪庆|临沧");
where[26] = new comefrom("西藏","|拉萨|日喀则|山南|林芝|昌都|阿里|那曲");
where[27] = new comefrom("陕西","|西安|宝鸡|咸阳|铜川|渭南|延安|榆林|汉中|安康|商洛");
where[28] = new comefrom("甘肃","|兰州|嘉峪关|金昌|白银|天水|酒泉|张掖|武威|定西|陇南|平凉|庆阳|临夏|甘南");
where[29] = new comefrom("宁夏","|银川|石嘴山|吴忠|固原");
where[30] = new comefrom("青海","|西宁|海东|海南|海北|黄南|玉树|果洛|海西");
where[31] = new comefrom("新疆","|乌鲁木齐|石河子|克拉玛依|伊犁|巴音郭勒|昌吉|克孜勒苏柯尔克孜|博尔塔拉|吐鲁番|哈密|喀什|和田|阿克苏");
where[32] = new comefrom("香港","|香港岛|九龙|新界");
where[33] = new comefrom("澳门","|澳门半岛|澳门离岛");
where[34] = new comefrom("台湾","|台北|高雄|台中|台南|屏东|南投|云林|新竹|彰化|苗栗|嘉义|花莲|桃园|宜兰|基隆|台东|金门|马祖|澎湖");
where[35] = new comefrom("其它","|北美洲|南美洲|亚洲|非洲|欧洲|大洋洲");
function select() {
with(document.creator.province) { var loca2 = options[selectedIndex].value; }
for(i = 0;i < where.length;i ++) {
if (where[i].loca == loca2) {
loca3 = (where[i].locacity).split("|");
for(j = 0;j < loca3.length;j++) { with(document.creator.city) { length = loca3.length; options[j].text = loca3[j]; options[j].value = loca3[j]; var loca4=options[selectedIndex].value;}}
break;
}}
}
function init() {
	with(document.creator.province) {
	length = where.length;
	for(k=0;k<where.length;k++) { options[k].text = where[k].loca; options[k].value = where[k].loca; }
	options[selectedIndex].text = where[0].loca; options[selectedIndex].value = where[0].loca;
	}
	with(document.creator.city) {
	loca3 = (where[0].locacity).split("|");
	length = loca3.length;
	for(l=0;l<length;l++) { options[l].text = loca3[l]; options[l].value = loca3[l]; }
	options[selectedIndex].text = loca3[0]; options[selectedIndex].value = loca3[0];
	}
} 
</script>
</body>