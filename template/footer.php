<div class="footer f12">
	<span><a href="http://blog.whydislike.com/" target="_blank">官方博客</a></span><span><a href="http://blog.whydislike.com/aboutus" target="_blank">关于我们</a></span><span><a href="http://blog.whydislike.com/post/2012-06-03/40028304867" target="_blank">招募牛人</a></span><span><a href="http://blog.whydislike.com/post/2012-06-03/40028589233" target="_blank">用前必读</a></span><span>&copy; 2012</span>
</div>
<?php if($isLogin == 1){?>
<script type="text/javascript">
(function() {
    var $backToTopTxt = "返回顶部", $backToTopEle = $('<div class="backToTop"></div>').appendTo($("body"))
        .text($backToTopTxt).attr("title", $backToTopTxt).click(function() {
            $("html, body").animate({ scrollTop: 0 }, 120);
    }), $backToTopFun = function() {
        var st = $(document).scrollTop(), winh = $(window).height();
        (st > 0)? $backToTopEle.show(): $backToTopEle.hide();    
        //IE6下的定位
        if (!window.XMLHttpRequest) {
            $backToTopEle.css("top", st + winh - 166);    
        }
    };
    $(window).bind("scroll", $backToTopFun);
    $(function() { $backToTopFun(); });
})();
</script>
<?php }?>