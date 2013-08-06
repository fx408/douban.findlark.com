<form action="/search/word/">
	<input name="keyword" placeholder="关键词" value="" type="text">
	<input type="submit" value="查询">
</form>

<div id="tag_list">
	<?php
	foreach($tags as $tag) {
		printf('<a href="/search/tag/keyword/%s">%s</a>', $tag, $tag);
	}
	?>
</div>