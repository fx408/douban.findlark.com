<?php

class BookController extends ApiController {
	// 详细
	public function actionDetail($id) {
		$data = $this->getBook($id);
		if(!$data) $this->_end(1, '获取书籍数据失败!');
		
		$reading = BookHelper::getBookReading($data->bookid);
		
		$this->_end(0, array($data, $reading));
	}
	
	// 列表
	public function actionList() {
		list($page, $data, $timeline) = Book::model()->list;
		$error =  0;
		if(empty($data)) {
			$error = 1;
			$data = '没有更多了...';
		}
		$this->_end($error, $data, array('page'=> $page, 'timeline'=> $timeline));
	}
	
	// 试读详细
	public function actionReading($bookid, $id) {
		list($title, $content) = BookHelper::getReadingDetail($id);
		
		$this->_end(0, array('title'=> $title, 'content'=> $content));
	}
}