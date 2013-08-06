<div class="book-list">
	
</div>

<pre class="loading"">
	<strong>点击加载更多...</strong>
</pre>

<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript">
	<?php
	if(isset($address)) printf("AppBook.bookListAddress = '%s'; \n", $address);
	?>
	
	$(function() {
		console.log(AppBook.bookListAddress);
		
		AppBook.getBookList();
		$(".loading").click(function() {
			AppBook.getBookList();
		});
	});
</script>