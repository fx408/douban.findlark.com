<?php

class SearchController extends ApiController {
	
	public function actionTags() {
		$this->_end(0, BookTags::model()->tags);
	}
	
	public function actionIndex($keyword, $type = 'q') {
		$keyword = trim($keyword);
		$type = $type == 'tag' ? 'tag' : 'q';
		$url = sprintf('http://api.douban.com/v2/book/search?%s=%s', $type, $keyword);
		$this->search($url);
	}
	
	public function search($url) {
		list($page, $offset, $pageSize) = BookHelper::getStart();
		$url .= '&fields=id,author,title,tags,summary,rating,images,author_intro,catalog&start='.$offset.'&count='.$pageSize;
		$content = Curl::model()->request($url);
		
		$content && $content = CJSON::decode($content);
		if($content && isset($content['books'])) {
			if(empty($content['books'])) {
				$error = 1;
				$data = '没有更多了...';
			} else {
				$error = 0;
				
				foreach($content['books'] as &$item) {
					$item = Book::model()->formatBookData($item);
					$item['summary'] = mb_substr($item['summary'], 0, 60, 'utf8').'...';
				}
				
				$data = $content['books'];
			}
		} else {
			$error = 1;
			$data = '查询失败!点击重试。';
		}
		
		$this->_end($error, $data, array('page'=> $page, 'timeline'=> 0));
	}
}