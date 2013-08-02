<?php

class BookController extends DoubanController {
	// 详细
	public function actionDetail($id) {
		$data = $this->getBook($id);
		$this->render('detail', array('data'=> $data));
	}
	
	// 列表
	public function actionList() {
		list($page, $data) = Book::model()->list;
		$error =  0;
		if(empty($data)) {
			$error = 1;
			$data = '没有更多了...';
		}
		$this->_end($error, $data, array('page'=> $page));
	}
	
	// 试读详细
	public function actionReading($bookid, $id) {
		$book = $this->getBook($bookid, ' - 试读');
		list($title, $content) = BookHelper::getReadingDetail($id);
		$list = BookHelper::getBookReading($bookid);
		$this->render('reading', array(
			'data'=> $content,
			'title'=> $title,
			'list'=> $list,
			'book'=> $book,
			'readingId'=> $id
		));
	}
}