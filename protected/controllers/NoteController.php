<?php

class NoteController extends DoubanController {
	// 读书笔记
	public function actionIndex($bookid) {
		$book = $this->getBook($bookid, ' - 笔记');
		$data = BookHelper::getNoteList($bookid);
		$this->render('list', array('data'=> $data, 'book'=> $book));
	}
	
	// 笔记详细
	public function actionDetail($bookid, $noteid) {
		$book = $this->getBook($bookid, ' - 笔记');
		$data = BookHelper::getNoteDetail($noteid);
		$list = BookHelper::getBookReading($bookid);
		$this->render('detail', array('data'=> $data, 'book'=> $book));
	}
}