<?php
class ApiController extends Controller{
	public function getBook($bookid) {
		$data = Book::model()->findByPk($bookid);
		if(empty($data)) $data = Book::model()->addBook($bookid);
		if(empty($data)) return false;
		
		$book = CJSON::decode($data->content);
		
		$book['summary'] = $data->summary;
		$book['author_intro'] = $data->author_intro;
		$book['catalog'] = $data->catalog;
		
		return $book;
	}

	// end
	public function _end($error = 0, $msg = 'success!', $params = array()) {
		$arr = array('error'=>$error, 'msg'=>$msg, 'param'=>$params);
		echo CJSON::encode($arr);
		Yii::app()->end();
	}
}