<?php

class NoteController extends ApiController {
	// 读书笔记
	public function actionIndex($bookid) {
		$data = BookHelper::getNoteList($bookid);
		$this->_end(0, $data);
	}
	
	// 笔记详细
	public function actionDetail($noteid) {
		$data = BookHelper::getNoteDetail($noteid);
		$this->_end(0,  $data);
	}
}