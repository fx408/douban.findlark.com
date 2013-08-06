<?php
class DoubanController extends Controller{
	public $title = '手机上的豆瓣';
	public $bookid = 0;
	
	// 查询图片
	public function getBook($bookid, $titleSuffix = '') {
		$data = Book::model()->findByPk($bookid);
		if(empty($data)) $data = Book::model()->addBook($bookid);
		if(empty($data)) throw new Exception('书籍已被删除或不存在!');
		
		$data = CJSON::decode($data->content, false);
		$this->title = $data->title.$titleSuffix;
		$this->bookid = $data->bookid;
		
		return $data;
	}
}