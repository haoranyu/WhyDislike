<div id="popup" class="fixie6" style="display:none">
<div class="popin">
	<div class="poptit f14">
		进步需要你的态度
	</div>
	<div class="popsubt f12">
		<div class="accept">
			<a style="margin-left:0" href="#" class="b1" id="ac">　改正不足</a>
			<a class="a2" href="#" id="ns">　不太好说</a>
			<a class="a3" href="#" id="rf">　很难认同</a>
			<div class="clear"></div>
		</div>
		<textarea class="poparea" id="reply" maxlength="140"></textarea>
		<input type="hidden" id="type" value="0">
	</div>
	<div class="popbtn f12">
		<div class="wordcount"><span wd="wordcount">0</span> / 140</div>
		<div class="popbtns">
		<input type="button" value="确定" class="f12 popsubmit" wd="submitCort"/>
		<input type="hidden" id="cid" value=""/>
		<input type="button" value="取消" class="f12 popcancel" wd="closeCort"/>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("[wd='openCort']").click(function(){
		$("#reply").val('');
		$("#popup").show(500);
		$("#cid").val($(this).attr("cid"));
		$("#type").val('0');
		$("#ac").removeClass().addClass("b1");
		$("#ns").removeClass().addClass("a2");
		$("#rf").removeClass().addClass("a3");
	});
	$("[wd='closeCort']").click(function(){
		$("#popup").hide(500);
	});
	$("[wd='submitCort']").click(function(){
		$.ajax({
		url: '<?php p($webinfo["webhost"].'include/ajax/correct.php');?>',
		type: 'POST',
		data:{ cid: $("#cid").val(), reply: $("#reply").val(), type: $("#type").val() },
		dataType: 'html',
		timeout: 3000,
		error: function(){
			alert('很抱歉，系统故障，稍后再试。');
		},
		success: function(html){
		 $("[name='"+$("#cid").val()+"']").slideToggle("slow");
		 $("#popup").hide(500);
		 $("[wd='cortCount']").html(parseInt($("[wd='cortCount']").html())+1);
		}
		});
	});
	$("#ac").click(function(){
		$("#type").val('0');
		$("#ac").removeClass().addClass("b1");
		$("#ns").removeClass().addClass("a2");
		$("#rf").removeClass().addClass("a3");
	});
	$("#ns").click(function(){
		$("#type").val('1');
		$("#ac").removeClass().addClass("a1");
		$("#ns").removeClass().addClass("b2");
		$("#rf").removeClass().addClass("a3");
	});
	$("#rf").click(function(){
		$("#type").val('2');
		$("#ac").removeClass().addClass("a1");
		$("#ns").removeClass().addClass("a2");
		$("#rf").removeClass().addClass("b3");
	});
	$("#reply").focus(function(){
		$(this).removeClass().addClass("popareax");
	});
	$("#reply").blur(function(){
		if($("#reply").val()==""){
		$(this).removeClass().addClass("poparea");
		}
	});
	$("#reply").keyup(function(){
		$("[wd='wordcount']").html($("#reply").val().length);
	});
});
</script>