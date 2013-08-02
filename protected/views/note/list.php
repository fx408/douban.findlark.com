<?php
foreach($data['list'] as $note) {
	echo <<<EOF
<div class="note">
	<div class="user_avatar">
		<img src="{$note['author_user']['avatar']}" uid="{$note['author_user']['uid']}" class="img-rounded">
	</div>
 <div class="note-info">
	<div class="bold">{$note['author_user']['name']}</div>
	<div>
		<a href="/note/detail/bookid/{$book->bookid}/noteid/{$note['id']}" class="note_detail" data-transition="none">第{$note['page_no']}页</a>
		<small class="muted">{$note['time']}</small>
	</div>
 </div>
	<div class="clear"></div>
	<pre>{$note['summary']}<br><a href="/note/detail/bookid/{$book->bookid}/noteid/{$note['id']}" class="note_detail" data-transition="none">阅读详细</a></pre>
</div>
EOF;
}

echo '<div class="pager">';
if($data['prev']) printf('<a href="/note/index/bookid/%d/page/%d" data-transition="none" class="page-prev">上一页</a>',
	$book->bookid, $data['prev']);
	
if($data['next']) printf('<a href="/note/index/bookid/%d/page/%d" data-transition="none"class="page-next">下一页</a>',
	$book->bookid, $data['next']);
echo '</div>';
?>