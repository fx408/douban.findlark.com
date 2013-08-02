<div class="reading-title"><?php echo $title;?></div>
<pre class="reading-content"><?php echo $data;?></pre>

<div>
<?php
foreach($list as $n => $id) {
	if($id == $readingId) {
		printf('<span class="reading bold">试读%d</span>', $n+1);
	} else {
		printf('<a href="/book/reading/bookid/%d/id/%d" class="reading" data-transition="none">试读%d</a>',
			$book->bookid, $id, $n+1);
	}
}
?>
</div>