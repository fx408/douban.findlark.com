<?php

class SearchController extends DoubanController {
	
	public function actionIndex() {
		$this->title = '搜索书籍';
		
		$this->render('index', array('tags'=> BookTags::model()->tags));
	}
	
	public function actionWord() {
		$this->title = '搜索书籍';
		$request = Yii::app()->request;
		$keyword = trim( $request->getParam('keyword') );
		
		if($request->isPostRequest) {
			$url = sprintf('http://api.douban.com/v2/book/search?q=%s', $keyword);
			$this->search($url);
		}
		
		$this->render('/book/index', array('address'=>'/search/word/keyword/'.$keyword));
	}
	
	public function actionTag() {
		$this->title = '搜索书籍';
		$request = Yii::app()->request;
		$keyword = trim( $request->getParam('keyword') );
		
		if($request->isPostRequest) {
			
			$url = sprintf('http://api.douban.com/v2/book/search?tag=%s', $keyword);
			$this->search($url);
		}
		
		$this->render('/book/index', array('address'=>'/search/tag/keyword/'.$keyword));
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
		
		$this->_end($error, $data, array('page'=> $page));
	}
}