<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
		
		<title>手机上的豆瓣读书</title>
		<link rel="stylesheet" href="/css/jquery.mobile-1.3.2.min.css" />
		<link href="/css/app.css" rel="stylesheet">
		<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/js/jquery.mobile-1.3.2.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$("body").on("click", "#menu", function(e) {
					if($(".menu_list").is(":hidden")) {
						$(".menu_list").removeClass("hide");
					} else {
						$(".menu_list").addClass("hide");
					}
					e.stopPropagation();
				}).on("click", ".menu_list a.go_top", function() {
					$(window).scrollTop(0);
				}).click(function() {
					$(".menu_list").addClass("hide");
				});
			});
		</script>
	</head>
	
	<body>
		<div data-role="page">
			<div data-role="header" data-position="fixed" >
				<a href="javascript:;" id="menu" class="ui-btn-right">菜单</a>
				<h3 id="page_title"><?php echo $this->title;?></h3>
			</div>
			
			<div data-role="content">
				<?php echo $content;?>
			</div>
			
			<div class="menu_list hide">
				<ul data-role="listview" data-theme="c" data-inset="true">
					<li><a href="/" data-transition="slide">首页</a></li>
					<li><a href="/book/collection" data-transition="slide">我的收藏</a></li>
					<li><a href="/search/index" data-transition="slide">搜索书籍</a></li>
					<?php if($this->bookid) { ?>
					<li><a href="/book/detail/id/<?php echo $this->bookid;?>" data-transition="slide">书籍详细</a></li>
					<li><a href="/note/index/bookid/<?php echo $this->bookid;?>" data-transition="slide">读书笔记</a></li>
					<?php } ?>
					<li><a href="javascript:;" data-transition="slide" class="go_top">回到顶部</a></li>
	    	</ul>
			</div>
		</div>
	</body>
</html>