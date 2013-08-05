<div id="book-list">
	
</div>

<pre class="loading" id="load-more">
	<strong>点击加载更多...</strong>
</pre>

<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript">
	<?php
	if(isset($address)) printf("AppBook.bookListAddress = '%s'; \n", $address);
	?>
	
	$(function() {
		AppBook.getBookList();
		$("#load-more").click(function() {
			AppBook.getBookList();
		});
	});
</script>