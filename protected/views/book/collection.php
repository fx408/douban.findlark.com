<script type="text/javascript" src="/js/app.js"></script>

<ul data-role="listview" data-divider-theme="b" data-inset="true" id="collection-list">
	<li data-role="list-divider" role="heading">我的收藏</li>
	<script type="text/javascript">
		var template = '<li data-theme="c"><a href="/book/detail/id/{$bookid}" data-transition="none">{$title}</a></li>';
		var html = AppBook.createCollectionList(template);
		
		document.write(html);
	</script>
</ul>