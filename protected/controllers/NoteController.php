<?php

class NoteController extends DoubanController {
	// 读书笔记
	public function actionIndex($bookid) {
		$book = $this->getBook($bookid, ' - 笔记');
		$data = BookHelper::getNoteList($bookid);
		
		if(!$data) throw new Exception('获取读书笔记失败!');
		
		$this->render('list', array('data'=> $data, 'book'=> $book));
	}
	
	// 笔记详细
	public function actionDetail($bookid, $noteid) {
		$book = $this->getBook($bookid, ' - 笔记');
		$data = BookHelper::getNoteDetail($noteid);
		
		if(!$data) throw new Exception('获取读书笔记失败!');
		
		$this->render('detail', array('data'=> $data, 'book'=> $book));
	}
}