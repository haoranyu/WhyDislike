<div class="taginput">
	<div style="width:700px;">
		<div class="addedtag f12" id="addedtag"></div>
		<div class="searchtag">
		<input type="text" class="itags" style="float:left;"  wd="hint" title="添加相关的标签……" value="添加相关的标签……">
		</div>
	</div>
	<input type="hidden" id="tagarray">
</div>
<script>
$(document).ready(function(){
	$("input.itags").focus( function () {
		$.ajax({
		url: '<?php echo $webinfo["webhost"].'include/ajax/tag.php';?>',
		type: 'POST',
		data:{ content: $("#ipod").val(),taged:$("#tagarray").val()},
		dataType: 'html',
		timeout: 3000,
		error: function(){
			$("div.tagbox").fadeIn(500);
			$("div.tagbox").html('标签载入失败...');
		},
		success: function(html){
			$("div.tagbox").fadeIn(100);
			$("div.tagbox").html(html);
		}
		});
	});
	$("input.itags").keyup( function () {
		 $.ajax({
		url: '<?php echo $webinfo["webhost"].'include/ajax/tag.php';?>',
		type: 'POST',
		data:{ content: $("#ipod").val(),taged:$("#tagarray").val()},
		dataType: 'html',
		timeout: 3000,
		error: function(){
			$("div.tagbox").fadeIn(500);
			$("div.tagbox").html('标签载入失败...');
		},
		success: function(html){
			$("div.tagbox").fadeIn(500);
			if($("input.itags").val()!=''){
			$("div.tagbox").html('<span wd="addtagplus">添加“'+$("input.itags").val()+'”标签</span>'+html);
			}
			else{
			$("div.tagbox").html(html);
			}
		}
		});
	});
	$("[wd='addtagplus']").live ('click',function(){
		var temp = $(this).html();
		var tagged = $("#tagarray").val();
		temp = temp.split('“')[1].split('”')[0];
		$(".addedtag").html($(".addedtag").html()+'<span>'+temp+'</span>');
		$("#tagarray").val($("#tagarray").val()+temp+',');
		$("input.itags").val('添加相关的标签……');
	});
	$("[wd='addtag']").live ('click',function(){
		var temp = $(this).html();
		$(".addedtag").html($(".addedtag").html()+'<span>'+temp+'</span>');
		$("#tagarray").val($("#tagarray").val()+temp+',');
		$("input.itags").val('添加相关的标签……');
	});
	$("input.itags").blur(function(){
		$("div.tagbox").fadeOut(500);
	});
});
</script>